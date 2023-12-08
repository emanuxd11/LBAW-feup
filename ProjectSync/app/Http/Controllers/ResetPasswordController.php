<?php
// ResetPasswordController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

use Carbon\Carbon;

use App\Models\User;

class ResetPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    public function showResetForm(Request $request, $token = null)
    {
        if ($token === null || !DB::table('password_reset_tokens')->where('token', $token)->exists()) {
            return view('auth.passwords.invalid_token');
        }

        $passwordReset = DB::table('password_reset_tokens')
            ->where('token', $token)
            ->first();
        $tokenCreationTime = Carbon::parse($passwordReset->created_at);
        $tokenExpirationTime = now()->diffInMinutes($tokenCreationTime);
        if ($tokenExpirationTime > 60) {
            return view('auth.passwords.invalid_token');
        }

        return view('auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    public function resetPassword(Request $request) {
        $request->validate([
            'password' => 'required|min:8|confirmed',
            'token' => 'required'
        ]);
    
        $token = $request->token;
    
        $passwordReset = DB::table('password_reset_tokens')
            ->where('token', $token)
            ->first();
    
        if (!$passwordReset) {
            return view('auth.passwords.invalid_token');
        }
    
        $tokenCreationTime = Carbon::parse($passwordReset->created_at);
        $tokenExpirationTime = now()->diffInMinutes($tokenCreationTime);
    
        if ($tokenExpirationTime > 60 || $token === null || !DB::table('password_reset_tokens')->where('token', $token)->exists()) {
            return view('auth.passwords.invalid_token');
        }

        $user = User::where('email', $passwordReset->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();
    
        DB::table('password_reset_tokens')
            ->where('token', $token)
            ->delete();
    
        Session::flash('success', 'Password reset successfully! You can now log in.');
        return view('auth.login');
    }
}
