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
                    @method('post')

                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" name="name" id="name" placeholder="{{ $user->name }}">
                    </div>

                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" name="username" id="username" placeholder="{{ $user->username }}">
                    </div>

                    <div class="form-group">
                        <label for="phonenumber">Phone Number:</label>
                        <input type="text" name="phonenumber" id="phonenumber" placeholder="{{ $user->phonenumber }}">
                    </div>

                    <div class="form-group">
                        <label for="password">New Password:</label>
                        <input type="password" name="password" id="password">
                    </div>

                    <button type="submit" class="button">Save Changes</button>
                </form>

                @if (session('success'))
                    <div class="error_message">
                        <p>{{session('success')}}</p>
                    </div>
                @else
                    <div class="error_message">
                        <p>{{session('error')}}</p>
                    </div>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach 
                @endif
            </div>
        </div>
    </div>
</div>

@endsection