@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')

<link href="{{ asset('css/editProfile.css') }}" rel="stylesheet">


<div id="EditProfile">
    <div class="edit-profile-container col-md-8 offset-md-2">
        <div class="edit-profile-card">
            <div class="edit-profile-card-header">{{ $user->name }}</div>
            <div class="edit-profile-card-body">
                <form method="post" action="{{ route('updateProfile', ['username' => $user->username]) }}" enctype="multipart/form-data">
                    @csrf
                    @method('post')

                    <div class="edit-profile-form-group">
                        <label for="name">Name:</label>
                        <input type="text" name="name" placeholder="ex: John Bill" id="name" placeholder="{{ $user->name }}">
                    </div>

                    <div class="edit-profile-form-group">
                        <label for="username">Username:</label>
                        <input type="text" placeholder="ex: john1234" name="username" id="username" placeholder="{{ $user->username }}">
                    </div>

                    <div class="edit-profile-form-group">
                        <label for="phonenumber">Phone Number:</label>
                        <input type="text" name="phonenumber" placeholder="ex: 917918916" id="phonenumber" placeholder="{{ $user->phonenumber }}">
                    </div>

                    <div class="edit-profile-form-group">
                        <label for="password">New Password:</label>
                        <input type="password" name="password" placeholder="ex: 12345678" id="password">
                    </div>

                    <div class="edit-profile-form-group">
                        <label for="bio">Change Bio:</label>
                        <textarea  name="bio" id="bio" placeholder="ex: Adept at utilizing project management software to streamline processes, track progress, and maintain transparency. Excels in fostering collaboration, motivating team members, and ensuring a positive work environment." 
                        rows="5" style="height: 100px; resize: none;" placeholder="{{ $user->bio }}"></textarea>
                    </div>

                    <div class="edit-profile-form-group">
                        <label for="profile_pic">Profile Picture:</label>
                        <input type="file" name="profile_pic" id="profile_pic">
                    </div>

                    <button type="submit" class="edit-profile-button">Save Changes</button>
                </form>

                <div class="edit-profile-deleteButton">
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

@endsection