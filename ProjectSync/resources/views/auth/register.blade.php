<!-- register.blade.php -->

@extends('layouts.app')

@section('content')

    <script type="text/javascript" src="{{ asset('js/password_forms.js') }}" defer></script>
    <link href="{{ asset('css/register.css') }}" rel="stylesheet">
    
    <div class="container mt-2">
        <div class="text-center mb-3">
            <p class="login-section-text" style="color: #006aa7;">
                Create an account to get started and enjoy our services.
            </p>
        </div>
        <div class="register-container">
            <div class="card register-card">
                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}" class="login-form" onsubmit="return validateForm()">
                        {{ csrf_field() }}

                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input id="username" placeholder="ex: john1234" type="text" name="username" value="{{ old('username') }}" required autofocus class="form-control">
                            @if ($errors->has('username'))
                                <span class="error">
                                    {{ $errors->first('username') }}
                                </span>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input id="name" placeholder="ex: John Bill" type="text" name="name" value="{{ old('name') }}" required autofocus class="form-control">
                            @if ($errors->has('name'))
                                <span class="error">
                                    {{ $errors->first('name') }}
                                </span>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input id="email" placeholder="ex: abc123@email.com" type="email" name="email" value="{{ old('email') }}" required class="form-control">
                            @if ($errors->has('email'))
                                <span class="error">
                                    {{ $errors->first('email') }}
                                </span>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="phonenumber" class="form-label">Phone Number (optional)</label>
                            <input id="phonenumber" placeholder="ex: 917918916" type="text" name="phonenumber" value="{{ old('phonenumber') }}" class="form-control">
                            @if ($errors->has('phonenumber'))
                                <span class="error">
                                    {{ $errors->first('phonenumber') }}
                                </span>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input id="password" placeholder="ex: 12345678" type="password" name="password" required class="form-control" minlength="8" oninput="validatePassword()">
                            <span id="password-error" class="error"></span>
                        </div>

                        <div class="mb-3">
                            <label for="password-confirm" class="form-label">Confirm Password</label>
                            <input id="password-confirm" placeholder="ex: 12345678" type="password" name="password_confirmation" required class="form-control" oninput="validatePassword()" onblur="validatePassword()">
                            <span id="password-confirm-error" class="error"></span>
                        </div>

                        <button type="submit" id="submit-button" class="btn btn-primary" disabled>
                            Register
                        </button>

                        <div class="separator text-center">
                            <hr>
                            <span id="no-account">Already have an account?</span>
                            <hr>
                        </div>

                        <a class="btn btn-primary btn-register register-button" href="{{ route('login') }}">
                            Login
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
