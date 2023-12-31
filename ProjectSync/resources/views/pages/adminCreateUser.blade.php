@extends('layouts.app')

@section('content')
    <link href="{{ asset('css/register.css') }}" rel="stylesheet">
    <div class="container mt-2">
        <div class="text-center mb-3">
            <p class="login-section-text" style="color: #006aa7;">
                Create a new account
            </p>
        </div>
        <div class="register-container">
            <div class="card register-card">
                <div class="card-body">
                    <form method="POST" action="{{ route('adminPage.create_user') }}" class="login-form">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input id="username" type="text" name="username" value="{{ old('username') }}" required autofocus class="form-control">
                            @if ($errors->has('username'))
                                <span class="error">
                                    {{ $errors->first('username') }}
                                </span>
                            @endif
                        </div>

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
                            <label for="phonenumber" class="form-label">Phone Number</label>
                            <input id="phonenumber" type="text" name="phonenumber" value="{{ old('phonenumber') }}" class="form-control">
                            @if ($errors->has('phonenumber'))
                                <span class="error">
                                    {{ $errors->first('phonenumber') }}
                                </span>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="user-type" class="form-label">User Type</label>
                            <select id="user-type" name="user-type" class="project-form-input">
                                <option value="regular">Regular</option>
                                <option value="admin">Admin</option>
                            </select>
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
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
