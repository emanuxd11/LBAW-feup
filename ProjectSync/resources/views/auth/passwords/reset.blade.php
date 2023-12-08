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
@endsection

