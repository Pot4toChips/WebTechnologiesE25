<?php

use App\Http\Controllers\ExSettingsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecipePostController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\SettingsController;
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

Route::get('/settings', function () {
    return view('settings');
})->middleware(['auth', 'verified'])->name('settings');

Route::middleware('auth')->group(function () {
    Route::get('/api/user', [SettingsController::class, 'getUserData']);
    Route::post('/api/change-name', [SettingsController::class, 'changeName']);
    Route::post('/api/change-password', [SettingsController::class, 'changePassword']);
    Route::post('/api/delete-user', [SettingsController::class, 'deleteUser']);
    Route::post('/api/send-feedback', [SettingsController::class, 'sendFeedback']);
});

// Profile related Routes
Route::middleware('auth')->prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'load'])  ->name('load');
    Route::get('/edit', [ProfileController::class, 'edit']) ->middleware('verified')->name('edit');
    Route::patch('/edit', [ProfileController::class, 'update']) ->middleware('verified')->name('update');
    Route::get('/{user}', [ProfileController::class, 'show'])->name('show');
});
//follow routes
Route::middleware('auth')->group(function () {
    Route::post('/users/{user}/follow', [FollowController::class, 'follow'])->name('users.follow');
    Route::delete('/users/{user}/unfollow', [FollowController::class, 'unfollow'])->name('users.unfollow');
    Route::get('/users/{user}/followers', [ProfileController::class, 'followers'])->name('users.followers');
Route::get('/users/{user}/following', [ProfileController::class, 'following'])->name('users.following');

});


// API Routes
Route::middleware('auth')->group(function () {
    Route::get('/api/recipe-posts/get-recipe-posts', [RecipePostController::class, 'getRecipePosts']);
    Route::post('/api/recipe-posts/create-recipe-post', [RecipePostController::class, 'createRecipePost']);
    Route::post('/api/recipe-posts/edit-recipe-post', [RecipePostController::class, 'editRecipePost']);
    Route::post('/api/recipe-posts/delete-recipe-post', [RecipePostController::class, 'deleteRecipePost']);
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
