<?php

namespace App\Http\Controllers;

use App\Models\RecipePost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;
use Illuminate\Support\Facades\Http;

class RecipePostController extends Controller
{
    public function getRecipePosts()
    {
        $recipes = DB::table('recipe_posts')
            ->join('users', 'recipe_posts.author_id', '=', 'users.id')
            ->select(
                'recipe_posts.title',
                'users.name as author',
                'recipe_posts.updated_at',
                'recipe_posts.image',
                'recipe_posts.ingredients',
                'recipe_posts.instructions'
            )
            ->orderBy('recipe_posts.updated_at', 'desc')
            ->get()
            ->map(function ($recipe) {
                return [
                    'title' => $recipe->title,
                    'author' => $recipe->author,
                    'time' => $recipe->updated_at,
                    'image' => $recipe->image,
                    'ingredients' => json_decode($recipe->ingredients, true),
                    'instructions' => json_decode($recipe->instructions, true),
                ];
            });

        return response()->json($recipes);
    }

    public function createRecipePost(Request $request)
    {
        error_log($request->file('image'));
        
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image',
            'ingredients' => 'required',
            'instructions' => 'required',
        ]);

        error_log($request->file('image'));

        $ingredients = json_decode($request->ingredients, true);
        $instructions = json_decode($request->instructions, true);

        $userId = auth()->id();

        $uploadedFile = $request->file('image');
        $imageName = time() . '_' . pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME) . '.webp';

        $manager = new ImageManager(new Driver());
        $image = $manager->read($uploadedFile);
        $image->scaleDown(1024);
        $encodedImage = $image->encode(new WebpEncoder(quality: 50));

        $supabaseUrl = env('SUPABASE_URL');
        $supabaseKey = env(key: 'SUPABASE_SECRET');
        $uploadUrl = "{$supabaseUrl}/storage/v1/object/recipe_post_images/{$imageName}";

        try {
            $response = Http::withOptions([
                'verify' => false
            ])->withHeaders([
                        'Authorization' => "Bearer {$supabaseKey}",
                        'Content-Type' => 'image/webp',
                        'x-upsert' => 'true'
                    ])->send('POST', $uploadUrl, [
                        'body' => $encodedImage->toString()
                    ]);
        } catch (\Exception $e) {
            error_log($e);
        }

        if (!$response->successful()) {
            return response()->json([
                'error' => 'Failed to upload image to Supabase',
                'details' => $response->body()
            ], 500);
        }

        $recipe = RecipePost::create([
            'title' => $request->title,
            'author_id' => $userId,
            'image' => $imageName,
            'ingredients' => $ingredients,
            'instructions' => $instructions,
        ]);

        return response()->json($recipe, 201);
    }
}
