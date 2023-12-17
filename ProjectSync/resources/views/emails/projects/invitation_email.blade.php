<!-- project_invitation.blade.php -->

@extends('layouts.emails')

@section('content')
    <div class="email-content">
        <h1>Project Invitation</h1>

        <p>Hello, {{ $username }}!</p>

        <p>
            You were invited to join "{{ $project_name }}" on Project Sync
        </p>

        <p>If you wish to accept this invitation, please click the link below:</p>
        <p class="centered-text">
            <a class="button" href="{{ route('accept.project.invitation.redirect', ['project_id' => $project_id, 'user_id' => $user_id, 'token' => $token]) }}">
                Join Project
            </a>
        </p>

        <p>If you are not interested in joining this project, no further action is requried.</p>

        <div class="footer">
            <p>This email was sent from ProjectSync. &copy; {{ date('Y') }}</p>
        </div>
    </div>

    <style>
        body {
           font-family: 'Arial', sans-serif;
           line-height: 1.6;
           color: #333;
           background-color: #f8f8f8;
           padding: 20px;
       }
   
       .email-content {
           max-width: 600px;
           margin: 0 auto;
           background-color: #fff;
           padding: 20px;
           border-radius: 8px;
           box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
       }
   
       h1 {
           margin-top: 0px;
           padding-top: 0px;
           color: #007BFF;
       }
   
       p {
           margin-bottom: 20px;
       }
   
       a {
           color: #007BFF;
           text-decoration: none;
       }
   
       a:hover {
           text-decoration: none;
       }
   
       .footer {
           margin-top: 20px;
           border-top: 1px solid #eee;
           padding-top: 10px;
           font-size: 0.8em;
           color: #777;
       }
   
       .button {
           display: inline-block;
           padding: 10px 20px;
           font-size: 16px;
           font-weight: bold;
           text-align: center;
           text-decoration: none;
           background-color: #007BFF;
           color: #fff;
           border-radius: 5px;
           transition: background-color 0.3s ease;
       }
   
       .centered-text {
           text-align: center;
       }
   </style>
@endsection
