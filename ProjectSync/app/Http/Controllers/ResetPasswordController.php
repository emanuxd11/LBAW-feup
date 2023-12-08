<?php
// ResetPasswordController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

use App\Models\User;

class ResetPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    public function resetPassword($email, $pwrd, $pwrd_cnfrm) {
        if ($pwrd !== $pwrd_cnfrm) {
            Session::flash('status', 'Password reset successfully! You can now log in.');
            return view('auth.l');
        }

        $user = User::where('email', $email)->first();
        if (!$user) {
            
        }

        $user->password = Hash::make($request->password);
        $user->save();
        
        Session::flash('status', 'Password reset successfully! You can now log in.');
        return view('auth.login');
    }
}
