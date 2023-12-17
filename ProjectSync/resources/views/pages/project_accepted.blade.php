@extends('layouts.app')

@section('content')
    <h1>Project Invitation Redirect</h1>

    <form id="redirect-form" action="{{ route('accept.project.invitation', ['project_id' => $project_id, 'user_id' => $user_id, 'token' => $token]) }}" method="POST">
        @csrf
        @method('POST')
        <p>
            If you are not automatically redirected, 
            <button type="submit">click here</button>.
        </p>
    </form>

    <div id="redirect-message">
        <p>
            Redirecting...
        </p>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('redirect-form').submit();
        });
    </script>

@endsection


