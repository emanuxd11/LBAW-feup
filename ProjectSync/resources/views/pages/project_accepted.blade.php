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
            Redirecting in <span id="countdown">5</span> seconds...
        </p>
    </div>

    <script>
        // Countdown timer
        let seconds = 5; // Set the initial countdown time
        const countdownElement = document.getElementById('countdown');

        function updateCountdown() {
            countdownElement.textContent = seconds;
            if (seconds === 0) {
                document.getElementById('redirect-form').submit();
            } else {
                seconds--;
                setTimeout(updateCountdown, 1000);
            }
        }

        // Start the countdown
        updateCountdown();
    </script>

@endsection

