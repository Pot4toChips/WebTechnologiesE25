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
use HTMLPurifier;
use HTMLPurifier_Config;


class ProfileController extends Controller
{
    /**
     * Display the user's profile
     */
    public function load(): View
    {
        $user = Auth::user();
        $id = Auth::id();
        $name = User::where('id', $id)->value('name');
        $userProfile = UserProfile::where('user_id', $id)->first();
        $posts = RecipePost::where('author_id', $id)
        ->orderBy('created_at', 'desc')
        ->paginate(3);

            $postCount = $posts->count();
            $followersCount = $user->followers()->count();
            $followingCount = $user->following()->count();

            
         return view('profile.profile', [
        'name' => $name,
        'userProfile' => $userProfile,
        'posts' => $posts,
        'isOwner' => true,
         'postCount' => $postCount,
        'followersCount' => $followersCount,
        'followingCount' => $followingCount,

    ]);

    }

public function show(User $user)
{
    $name = User::where('id', $user->id)->value('name');
    $userProfile = UserProfile::where('user_id', $user->id)->first();
    $posts = RecipePost::where('author_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->paginate(3);

        $isOwner = Auth::id() === $user->id;

        $isFollowing = false;
    if (Auth::check()&& !$isOwner) {
        $isFollowing = Auth::user()->following->contains($user->id);
    }
    $postCount = RecipePost::where('author_id', $user->id)->count();
    $followersCount = $user->followers()->count();
    $followingCount = $user->following()->count();


        return view('profile.profile', [
        'name' => $name,
        'userProfile' => $userProfile,
        'posts' => $posts,
        'isOwner' => $isOwner,
        'isFollowing' => $isFollowing,
        'postCount' => $postCount,
        'followersCount' => $followersCount,
        'followingCount' => $followingCount,
    ]);
}
public function followers(User $user)
{
    $followers = $user->followers()->get(); // list of users following this user
    return view('profile.followers', compact('user', 'followers'));
}

public function following(User $user)
{
    $following = $user->following()->get(); // list of users this user follows
    return view('profile.following', compact('user', 'following'));
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
    
    // Explicitly ensure user is authenticated
    if (!$user) {
        abort(403, 'Unauthorized action.');
    }

    // Retrieve or create the user's profile
    $userProfile = UserProfile::firstOrCreate(
        ['user_id' => $user->id],
        ['description' => null, 'bio' => null, 'image' => null]
    );

    // Validate input
    $validated = $request->validate([
        'description' => 'nullable|string|max:255',
        'bio' => 'nullable|string',
        'image' => 'nullable|image|max:5120', // max 5MB
    ]);

    // Sanitize text inputs to prevent HTML injection / XSS
$purifierConfig = HTMLPurifier_Config::createDefault();
$purifierConfig->set('HTML.Allowed', ''); 

$purifier = new HTMLPurifier($purifierConfig);

    $userProfile->description = $validated['description'] 
        ? $purifier->purify($validated['description']) 
        : $userProfile->description;

    $userProfile->bio = $validated['bio'] 
        ? $purifier->purify($validated['bio']) 
        : $userProfile->bio;

    if ($request->hasFile('image')) {
        $uploadedFile = $request->file('image');
        $imageName = $this->storeImage($uploadedFile, "profile_images");
        $userProfile->image = $imageName;
    }

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
