<?php

namespace App\Http\Controllers;

use App\Models\RecipePost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|string',
            'ingredients' => 'required|array',
            'instructions' => 'required|array',
        ]);

        $userId = auth()->id();

        $recipe = RecipePost::create([
            'title' => $request->title,
            'author_id' => $userId,
            'image' => $request->image,
            'ingredients' => $request->ingredients,
            'instructions' => $request->instructions,
        ]);

        return response()->json($recipe, 201);
    }
}
