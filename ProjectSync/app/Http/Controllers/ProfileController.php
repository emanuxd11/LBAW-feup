<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\User;

class ProfileController extends Controller
{
    public function showProfilePage(User $user)
    {
        if (!Auth::check()){
            return redirect("/login");
        }

        return view('pages.profilePage', compact('user'));

    }
}
