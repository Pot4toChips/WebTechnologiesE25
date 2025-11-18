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
});

require __DIR__ . '/auth.php';
