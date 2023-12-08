<?php

// app/Http/Controllers/EmailController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use App\Mail\ResetPassword;


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

        // Logic to send a password reset email
        Mail::to($email)->send(new ResetPassword());

        Session::flash('status', 'Password reset email sent! Please check ' . $email . '.');

        return view('auth.passwords.email');
    }
}