<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class SettingsController extends Controller
{
    public function getUserData(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    public function changeName(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $user = $request->user();
        $user->name = $request->name;
        $user->save();

        return response()->json([
            'message' => 'Name updated successfully',
            'name' => $user->name,
        ]);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'error' => 'Current password is incorrect.'
            ], 422);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'message' => 'Password updated successfully'
        ]);
    }

    public function deleteUser(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);

        $user = $request->user();

        Auth::guard('web')->logout();
        
        $user->delete();

        $request->session()->invalidate();

        return response()->json([
            'message' => 'User successfully deleted.'
        ]);
    }

    public function sendFeedback(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'feedback' => 'required'
        ]);

        return response()->json([
            'message' => 'Feedback succesfully sent.'
        ]);
    }
}
