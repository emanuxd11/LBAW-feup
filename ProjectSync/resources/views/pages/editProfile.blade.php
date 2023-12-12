@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')

<div id="EditProfile">
    <div class="col-md-8 offset-md-2">
        <div class="card">
            <div class="card-header">Edit Profile: {{ $user->name }}</div>

            <div class="card-body">
                <form method="post" action="{{ route('updateProfile', ['username' => $user->username]) }}" enctype="multipart/form-data">
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

                    <div class="form-group">
                        <label for="bio">Change Bio:</label>
                        <textarea name="bio" id="bio" rows="5" style="height: 300px; resize: none;" placeholder="{{ $user->bio }}"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="profile_pic">Profile Picture:</label>
                        <input type="file" name="profile_pic" id="profile_pic">
                    </div>

                    <button type="submit" class="button">Save Changes</button>
                </form>

                <div class="deleteButton">
                    <form method="post" action="{{ route('delete.account', ['username' => $user->username]) }}" enctype="multipart/form-data">
                        @method('delete')
                        @csrf
                        <button type="submit" class="button delete-button"><i class="fas fa-trash-alt"></i> Delete</button>
                    </form>
                </div>

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