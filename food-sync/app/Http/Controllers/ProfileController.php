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
use Illuminate\Support\Facades\DB;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\WebpEncoder;
use Illuminate\Support\Facades\Http;


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
            'image' => 'nullable|image',
        ]);

        // Update
        $userProfile->description = strip_tags($validated['description']) ?? $userProfile->description;
        $userProfile->bio = strip_tags($validated['bio']) ?? $userProfile->bio;

        $uploadedFile = $request->file('image');
        $imageName = $this->storeImage($uploadedFile, "profile_images");

        $userProfile->image = $imageName;

        $userProfile->save();

        return redirect()->route('profile.load')->with('success', 'Profile updated successfully.');
    }

    public function storeImage($uploadedFile, $folder)
    {
        $imageName = time() . '_' . pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME) . '.webp';

        $manager = new ImageManager(new Driver());
        $image = $manager->read($uploadedFile);
        $image->scaleDown(1024);
        $encodedImage = $image->encode(new WebpEncoder(quality: 50));

        $supabaseUrl = env('SUPABASE_URL');
        $supabaseKey = env('SUPABASE_SECRET');
        $uploadUrl = "{$supabaseUrl}/storage/v1/object/{$folder}/{$imageName}";

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