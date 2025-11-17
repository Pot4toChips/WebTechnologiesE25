<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecipePostController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function () {
    return view('home.home');
})->middleware(['auth', 'verified'])->name('home');


Route::get('/explore', function () {
    return view('explore');
})->middleware(['auth', 'verified'])->name('explore');

Route::get('/profile', function() { 
    return view('profile');
})->middleware(['auth', 'verified'])->name('home');

Route::get('/settings', function () {
    return view('settings');
})->middleware(['auth', 'verified'])->name('settings');


Route::middleware('auth')->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// API Routes
Route::middleware('auth')->group(function () {
    Route::get('/api/recipe-posts/get-recipe-posts', [RecipePostController::class, 'getRecipePosts']);
    Route::post('/api/recipe-posts/create-recipe-post', [RecipePostController::class, 'createRecipePost']);
});

require __DIR__ . '/auth.php';

// Debug route - only enabled in local environment to inspect recipe_posts
if (app()->environment('local')) {
    Route::get('/debug/recipe-posts', function () {
        $rows = \Illuminate\Support\Facades\DB::table('recipe_posts')->orderBy('updated_at', 'desc')->take(10)->get();
        $count = \Illuminate\Support\Facades\DB::table('recipe_posts')->count();
        $supabaseUrl = env('SUPABASE_URL');

        $mapped = $rows->map(function ($r) use ($supabaseUrl) {
            $imageUrl = null;
            if (!empty($r->image) && $supabaseUrl) {
                $imageUrl = rtrim($supabaseUrl, '/') . '/storage/v1/object/public/recipe_post_images/' . ltrim($r->image, '/');
            }

            return [
                'id' => $r->id ?? null,
                'title' => $r->title,
                'author_id' => $r->author_id ?? null,
                'image' => $r->image ?? null,
                'image_url' => $imageUrl,
                'updated_at' => $r->updated_at ?? null,
            ];
        });

        return response()->json(['count' => $count, 'sample' => $mapped]);
    });
}
