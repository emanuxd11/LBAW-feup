<!-- passwords.email.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="email-container">
            <div class="card email-card">
                <div class="card-body">
                    <form method="POST" action="{{ route('password.email') }}" class="login-form">
                        {{ csrf_field() }}

                        <h3>Forgot your password?</h3>
                        <p>
                            Please enter your email address. You will receive a link to
                            create a new password via email.
                        </p>

                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="form-control">
                            @if ($errors->has('email'))
                                <span class="error">
                                    {{ $errors->first('email') }}
                                </span>
                            @endif
                        </div>
                        
                        <button type="submit" class="btn btn-primary">
                            Send Email
                        </button>

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
