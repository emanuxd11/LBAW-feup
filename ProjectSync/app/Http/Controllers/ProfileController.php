<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class ProfileController extends Controller
{
    public function showProfilePage($username)
    {
        if (!Auth::check()){
            return redirect("/login");
        }

        $user = User::where('username', $username)->first();

        return view('pages.profilePage', compact('user'));

    }

    public function editProfile($username)
    {
        if (!Auth::check()) {
            return redirect("/login");
        }

        $user = User::where('username', $username)->first();
        
        // Check if the authenticated user is the same as the user being edited
        if (Auth::user()->id !== $user->id) {
            // If not the same user, you might want to handle unauthorized access
            return redirect()->route('profilePage', ['username' => $user->username]);
        }
        
        // Logic for editing the profile
        return view('pages.editProfile', compact('user'));
    }

    public function updateProfile(Request $request, $username)
    {
        if (!Auth::check()) {
            return redirect("/login");
        }

        $user = User::where('username', $username)->first();

        // Check if the authenticated user is the same as the user being edited
        if (Auth::user()->id !== $user->id) {
            // If not the same user, you might want to handle unauthorized access
            return redirect()->route('profilePage', ['username' => $user->username]);
        }

        // Validate the form data
        $request->validate([
            'name' => 'nullable|string|max:255',
            'username' => 'required|string|max:255|unique:User,username,' . $user->id,
            'phonenumber' => 'nullable|string|max:9|min:9',
            'password' => 'nullable|string|min:8',
        ]);

        $user->username = $request->username;

        if ($request->filled('name')) {
            $user->name = $request->name;
        }

        if ($request->filled('phonenumber')) {
            $user->phonenumber = $request->phonenumber;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
/*
        // Update the user
        $passwordUpdated = $user->update([
            'username' => $request->username,
            
            'password' => $request->filled('password') ? Hash::make($request->password) : $user->password,
        ]);
*/
        $userUpdated = $user->save();

        if ($userUpdated) {
            return redirect()->route('profilePage', ['username' => $user->username])->with('success', 'Profile updated successfully.');
        } else {
            return redirect()->route('profilePage', ['username' => $user->username])->with('error', 'Failed to update profile. Please try again.');
        }
    }

}
