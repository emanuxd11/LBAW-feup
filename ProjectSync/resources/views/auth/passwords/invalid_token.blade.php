@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <h1>Password Reset Token Expired</h1>
        <h4>If you're seeing this message, you've probably:</h4>
        <ul>
            <li>
                Already changed your password.
            </li>
            <li>
                Made another password reset request.
            </li>
            <li>
                Made your request over 60 minutes ago.
            </li>
        </ul>
        <p>
            To solve this, check your email for a new password reset link. 
            If that doesn't work, you can try generating a new request.
        </p>
    </div>
@endsection
