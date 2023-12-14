@extends('layouts.app')

@section('content')

    <h1>Project Invitation Accepted</h1>

    <p>Thank you for accepting the project invitation. You are now a member of the project.</p>

    <p>If you have any questions, please contact the project coordinator.</p>

    <form id="redirect-form" action="{{ route('accept.project.invitation', ['project_id' => $project_id, 'user_id' => $user_id]) }}" method="POST">
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
        // Immediately submit the form on page load
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('redirect-form').submit();
        });
    </script>

@endsection


