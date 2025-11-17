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
                $imageUrl = null;
                if (!empty($recipe->image)) {
                    // Construct local image URL from public/images folder
                    $imageUrl = '/images/' . ltrim($recipe->image, '/');
                }

                return [
                    'title' => $recipe->title,
                    'author' => $recipe->author,
                    'time' => $recipe->updated_at,
                    'image' => $recipe->image,
                    'image_url' => $imageUrl,
                    'ingredients' => json_decode($recipe->ingredients, true),
                    'instructions' => json_decode($recipe->instructions, true),
                ];
            });

        return response()->json($recipes);
    }

    public function createRecipePost(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image',
            'ingredients' => 'required',
            'instructions' => 'required',
        ]);

        $ingredients = json_decode($request->ingredients, true);
        $instructions = json_decode($request->instructions, true);
        
        $userId = auth()->id();

        $uploadedFile = $request->file('image');
        $imageName = $this->storeImage($uploadedFile);

        $recipe = RecipePost::create([
            'title' => $request->title,
            'author_id' => $userId,
            'image' => $imageName,
            'ingredients' => $ingredients,
            'instructions' => $instructions,
        ]);

        return response()->json($recipe, 201);
    }

    public function storeImage($uploadedFile)
    {
        $imageName = time() . '_' . pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME) . '.webp';

        $manager = new ImageManager(new Driver());
        $image = $manager->read($uploadedFile);
        $image->scaleDown(1024);
        $encodedImage = $image->encode(new WebpEncoder(quality: 50));

        $supabaseUrl = env('SUPABASE_URL');
        $supabaseKey = env('SUPABASE_SECRET');
        $uploadUrl = "{$supabaseUrl}/storage/v1/object/recipe_post_images/{$imageName}";

        Http::withHeaders([
            'Authorization' => "Bearer {$supabaseKey}",
            'Content-Type' => 'image/webp',
            'x-upsert' => 'true'
        ])->send('POST', $uploadUrl, [
            'body' => $encodedImage->toString()
        ]);

        return $imageName;
    }
}
