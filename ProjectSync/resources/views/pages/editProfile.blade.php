@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')

<div id="EditProfile">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">Edit Profile: {{ $user->name }}</div>

            <div class="card-body">
                <form method="post" action="{{ route('updateProfile', ['username' => $user->username]) }}">
                    @csrf
                    @method('put')

                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" name="name" id="name" value="{{ $user->name }}" required>
                    </div>

                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" name="username" id="username" value="{{ $user->username }}" required>
                    </div>

                    <div class="form-group">
                        <label for="phonenumber">Phone Number:</label>
                        <input type="text" name="phonenumber" id="phonenumber" value="{{ $user->phonenumber }}">
                    </div>

                    <div class="form-group">
                        <label for="password">New Password:</label>
                        <input type="password" name="password" id="password">
                    </div>

                    <button type="submit">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection