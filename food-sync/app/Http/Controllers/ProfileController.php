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


class ProfileController extends Controller
{
    /**
     * Display the user's profile
     */
    public function load(Request $request): View
    {
      $id = Auth::id();
      $name = User::where('id', $id)->value('name');
      $posts = RecipePost::where('author_id', $id)
      ->orderBy('created_at', 'desc')
      ->get();
        
        return view('profile', compact('name', 'posts'));
    }

    /**
     * Update the user's profile information.
     */
    public function edit(Request $request): View
    {
      $user = Auth::user();
    return view('profile.edit', compact('user'));
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

}