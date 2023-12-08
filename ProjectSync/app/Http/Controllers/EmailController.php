<?php

// app/Http/Controllers/EmailController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Mail\ResetPassword;

use App\Models\User;

class EmailController extends Controller
{
    public function sendResetLink(Request $request)
    {
        // Validate the request data
        $request->validate([
            'email' => 'required|email',
        ]);

        // Get the email address from the request
        $email = $request->input('email');

        $user = User::where('email', $email)->first();
        if (!$user) {
            Session::flash('error', 'The email you provided couldn\'t be found in our database.');
            return view('auth.passwords.email');
        }
    
        $token = Password::createToken($user);

        DB::table('password_reset_tokens')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => now(),
        ]);

        Mail::to($email)->send(new ResetPassword($token));

        Session::flash('success', 'Password reset email sent! Please check ' . $email . '.');
        return view('auth.passwords.email');
    }
}