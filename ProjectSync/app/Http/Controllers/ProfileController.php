<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
}
