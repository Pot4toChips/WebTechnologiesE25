<?php

use App\Http\Controllers\ExSettingsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecipePostController;
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

Route::get('/profile', function() { 
    return view('profile');
})->middleware(['auth', 'verified'])->name('home');

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

// Profile Routes
Route::middleware('auth')->prefix('profile')->name('profile.')->group(function () {
    Route::get('/', [ProfileController::class, 'load'])  ->name('load');
    Route::get('/edit', [ProfileController::class, 'edit']) ->middleware('verified')->name('edit');
    Route::patch('/edit', [ProfileController::class, 'update']) ->middleware('verified')->name('update');
});

// API Routes
Route::middleware('auth')->group(function () {
    Route::get('/api/recipe-posts/get-recipe-posts', [RecipePostController::class, 'getRecipePosts']);
    Route::post('/api/recipe-posts/create-recipe-post', [RecipePostController::class, 'createRecipePost']);
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
