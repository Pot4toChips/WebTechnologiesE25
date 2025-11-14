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
