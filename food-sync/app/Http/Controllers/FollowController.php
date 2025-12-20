<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
use App\Models\RecipePost;
use App\Models\UserProfile;
    
class FollowController extends Controller
{
    public function follow(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot follow yourself.');
        }
        Auth::user()->following()->syncWithoutDetaching($user->id);

        return back()->with('success', 'You are now following ' . $user->name);
    }

    public function unfollow(User $user)
    {
        Auth::user()->following()->detach($user->id);

        return back()->with('success', 'You have unfollowed ' . $user->name);
    }

}