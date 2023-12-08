@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="email-container">
            <div class="card email-card">
                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}" class="login-form" onsubmit="return validateForm()">
                        {{ csrf_field() }}

                        <h3>Reset password</h3>
                        <p>
                            You can now enter a new password for your account.
                        </p>

                        <!-- Add a hidden input field for the token -->
                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="mb-3">
                            <label for="password" class="form-label">@yield('name')New Password</label>
                            <input id="password" type="password" name="password" required class="form-control" minlength="8" oninput="validatePassword()" onblur="validatePassword()">
                            <span id="password-error" class="error"></span>
                        </div>

                        <div class="mb-3">
                            <label for="password-confirm" class="form-label">Confirm Password</label>
                            <input id="password-confirm" type="password" name="password_confirmation" required class="form-control" oninput="validatePassword()" onblur="validatePassword()">
                            <span id="password-confirm-error" class="error"></span>
                        </div>

                        <button type="submit" id="submit-button" class="btn btn-primary" disabled>
                            Reset Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // let passwordFieldTouched = false;
        // let confirmationFieldTouched = false;

        // function validateForm() {
        //     // Additional validation logic can be added here if needed
        //     return validatePassword();
        // }

        // function validatePassword() {
        //     var password = document.getElementById('password').value;
        //     var confirmPassword = document.getElementById('password-confirm').value;

        //     var passwordError = document.getElementById('password-error');
        //     var confirmPasswordError = document.getElementById('password-confirm-error');

        //     // Reset error messages
        //     passwordError.innerHTML = "";
        //     confirmPasswordError.innerHTML = "";

        //     // Check password length only if the password field has been touched
        //     if (passwordFieldTouched && password.length < 8) {
        //         passwordError.innerHTML = "Password must be at least 8 characters long.";
        //         disableSubmitButton();
        //         return false;
        //     }

        //     // Check if passwords match only if the confirmation field has been touched
        //     if (confirmationFieldTouched && password !== confirmPassword) {
        //         confirmPasswordError.innerHTML = "Passwords do not match.";
        //         disableSubmitButton();
        //         return false;
        //     }

        //     enableSubmitButton();
        //     return true;
        // }

        // function disableSubmitButton() {
        //     document.getElementById('submit-button').disabled = true;
        // }

        // function enableSubmitButton() {
        //     document.getElementById('submit-button').disabled = false;
        // }

        // document.getElementById('password').addEventListener('blur', function () {
        //     passwordFieldTouched = true;
        //     validatePassword();
        // });

        // document.getElementById('password-confirm').addEventListener('blur', function () {
        //     confirmationFieldTouched = true;
        //     validatePassword();
        // });
    </script>
@endsection

