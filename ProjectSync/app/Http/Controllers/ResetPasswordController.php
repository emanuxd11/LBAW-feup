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
        // Check if the token is null or doesn't exist in the database
        if ($token === null || !DB::table('password_reset_tokens')->where('token', $token)->exists()) {
            // Invalid token, redirect to an error page or show an error message
            return view('auth.passwords.invalid_token');
        }

        return view('auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    public function resetPassword(Request $request) {
        $request->validate([
            'password' => 'required|min:8|confirmed',
            'token' => 'required' // Add a validation rule for the token
        ]);
    
        $token = $request->token;
    
        // Validate the token against the database
        $passwordReset = DB::table('password_reset_tokens')
            ->where('token', $token)
            ->first();
    
        if (!$passwordReset) {
            // Token is not found
            Session::flash('error', 'Invalid password reset token.');
            return redirect()->route('password.reset'); // Redirect to the password reset form
        }
    
        // Check if the token is still valid (e.g., within a certain time frame)
        $tokenCreationTime = Carbon::parse($passwordReset->created_at);
        $tokenExpirationTime = now()->diffInMinutes($tokenCreationTime);
    
        if ($tokenExpirationTime > 60) {
            // Token has expired
            DB::table('password_reset_tokens')->where('token', $token)->delete();
            Session::flash('error', 'Expired password reset token. Please request a new one.');
            return redirect()->route('password.reset'); // Redirect to the password reset form
        }
    
        // Update the user's password
        $user = User::where('email', $passwordReset->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();
    
        // Remove the used token from the database
        DB::table('password_reset_tokens')
            ->where('token', $token)
            ->delete();
    
        Session::flash('success', 'Password reset successfully! You can now log in.');
        return view('auth.login');
    }
    
}
