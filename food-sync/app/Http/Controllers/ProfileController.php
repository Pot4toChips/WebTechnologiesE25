<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
use App\Models\RecipePost;
use App\Models\UserProfile;

class ProfileController extends Controller
{
    /**
     * Display the user's profile
     */
    public function load(Request $request): View
    {
      $id = Auth::id();
      $name = User::where('id', $id)->value('name');
      $userProfile = UserProfile::where('user_id', $id)->first();
      $posts = RecipePost::where('author_id', $id)
      ->orderBy('created_at', 'desc')
      ->get();
        
        return view('profile.profile', compact('name', 'posts', 'userProfile'));
    }

    /**
     * Update 
     */
    public function edit(Request $request): View
    {
    $user = Auth::user();
    $userProfile = UserProfile::where('user_id', $user->id)->first();
    return view('profile.editprofile', compact('user', 'userProfile'));
    }

 public function update(Request $request)
{
    $user = Auth::user();
$userProfile = UserProfile::firstOrCreate(
    ['user_id' => $user->id],
    ['description' => null, 'bio' => null, 'image' => null]
);

    // Validate
    $validated = $request->validate([
        'description' => 'nullable|string|max:255',
        'bio' => 'nullable|string',
        'image' => 'nullable|image|max:2048', // optional, max 2MB
    ]);

    // Update
    $userProfile->description = $validated['description'] ?? $userProfile->description;
    $userProfile->bio = $validated['bio'] ?? $userProfile->bio;

    // Handle image upload
    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('profile_images', 'public');
        $userProfile->image = $path;
    }

    $userProfile->save();

    return redirect()->route('profile.load')->with('success', 'Profile updated successfully.');
  }
      


    }