<?php
// ProfileController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Project;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\Message;
use App\Models\Changes;
use App\Models\TaskComments;

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
        
        // Check if the authenticated user is the same as the user being edited and if is not an admin
        if (Auth::user()->id !== $user->id && !Auth::user()->isAdmin) {
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
        if (Auth::user()->id !== $user->id && !Auth::user()->isAdmin) {
            // If not the same user, you might want to handle unauthorized access
            return redirect()->route('profilePage', ['username' => $user->username]);
        }

        // Validate the form data
        $request->validate([
            'name' => 'nullable|string|max:255',
            'username' => 'nullable|string|max:255|unique:User,username,' . $user->id,
            'phonenumber' => 'nullable|string|max:9|min:9',
            'password' => 'nullable|string|min:8',
            'bio' => 'nullable|string|max:500',
            'profile_pic' => 'image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $altered = false;

        if ($request->filled('username')) {
            $user->username = $request->username;
            $altered = true;
        }

        if ($request->filled('name')) {
            $user->name = $request->name;
            $altered = true;
        }

        if ($request->filled('phonenumber')) {
            $user->phonenumber = $request->phonenumber;
            $altered = true;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
            $altered = true;
        }

        if ($request->filled('bio')) {
            $user->bio = $request->bio;
            $altered = true;
        }

        if ($request->hasFile('profile_pic')) {
            $file = $request->file('profile_pic');
            $fileNameExtension = ".jpg";
            $user->profile_pic = '/images/avatars/' . ($user->username). '.jpg';
            $file->move(public_path('/images/avatars'), ($user->username) . $fileNameExtension);
            $altered = true;
        }

        
        if($altered){
            $user->save();
            return redirect()->route('editProfile', ['username' => $user->username])->with('success', 'Profile updated successfully.');
        }
        else{
            return redirect()->route('editProfile', ['username' => $user->username])->with('error', 'Failed to update profile. Please try again.');
        }

    }

    public function deleteAccount(Request $request, $username)
    {
        if (!Auth::check()) {
            return redirect("/login");
        }

        $user = User::where('username', $username)->first();

        if (Auth::user()->id !== $user->id && !Auth::user()->isAdmin) {
            return redirect()->route('profilePage', ['username' => $user->username]);
        }

        DB::table('projectmembertask')->where('user_id', $user->id)->delete();
        DB::table('projectmember')->where('iduser', $user->id)->delete();
        DB::table('projectmemberinvitation')->where('iduser', $user->id)->delete();
        Post::where('author_id', $user->id)->update(['author_id' => null]);
        PostComment::where('author_id', $user->id)->update(['author_id' => null]);
        Message::where('sender_id', $user->id)->update(['sender_id'=> null]);
        Message::where('receiver_id', $user->id)->update(['receiver_id'=> null]);
        Changes::where('user_id', $user->id)->update(['user_id'=> null]);
        DB::table('postupvote')->where('user_id', $user->id)->delete();
        DB::table('usernotification')->where('user_id', $user->id)->delete();
        TaskComments::where('user_id', $user->id)->update(['user_id' => null]);
        
        $user->delete();

        if(Auth::user()->isAdmin){
            return redirect()->route('adminPage')->with('success','User deleted successfully');
        }

        return redirect("/");
    }

}
