<!-- register.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="text-center mb-3">
            <p class="login-section-text" style="color: #006aa7;">
                Create an account to get started and enjoy our services.
            </p>
        </div>
        <div class="login-container">
            <div class="card login-card">
                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}" class="login-form">
                        {{ csrf_field() }}

                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus class="form-control">
                            @if ($errors->has('name'))
                                <span class="error">
                                    {{ $errors->first('name') }}
                                </span>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required class="form-control">
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

                        <div class="mb-3">
                            <label for="password-confirm" class="form-label">Confirm Password</label>
                            <input id="password-confirm" type="password" name="password_confirmation" required class="form-control">
                        </div>

                        <button type="submit" class="btn btn-primary">
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
