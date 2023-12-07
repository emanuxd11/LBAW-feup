<!-- login.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="text-center mb-3">
            <p class="login-section-text" style="color: #006aa7;">
                Welcome to our website! Please log in to access your account and explore our services.
            </p>
        </div>
        <div class="login-container">
            <div class="card login-card">
                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}" class="login-form">
                        {{ csrf_field() }}

                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="form-control">
                            @if ($errors->has('email'))
                                <span class="error">
                                    {{ $errors->first('email') }}
                                </span>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input id="password" type="password" name="password" required class="form-control">
                            @if ($errors->has('password'))
                                <span class="error">
                                    {{ $errors->first('password') }}
                                </span>
                            @endif
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} class="form-check-input">
                            <label class="form-check-label remember-text">Remember Me</label>
                            <a class="forgot-text" href="{{ route('password.request') }}">
                                Forgot Password?
                            </a>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            Login
                        </button>

                        <div class="separator text-center">
                            <hr>
                            <span id="no-account">Don't have an account?</span>
                            <hr>
                        </div>

                        <a class="btn btn-primary btn-register register-button" href="{{ route('register') }}">
                            Register
                        </a>
                        @if (session('success'))
                            <p class="success">
                                {{ session('success') }}
                            </p>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
