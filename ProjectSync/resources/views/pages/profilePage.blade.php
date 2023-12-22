<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" />

@extends('layouts.app')

@section('title', $user->name)

@section('content')

<div id="ProfilePage">
    <div class="user-profile-card">
        <div class="user-profile-header">
            <div class="user-profile-image">
                @if($user->profile_pic !== null && $user->profile_pic !== '')
                    <img src="{{ $user->profile_pic }}" alt="Profile Picture">
                @else
                    <img src="/images/avatars/default-profile-pic.jpg" alt="Default Profile Picture">
                @endif
            </div>

            <div class="user-profile-info">
                <h2>{{ $user->name }}</h2>
                <div class="user-profile-fields">
                    <p><i class="fas fa-id-card"></i> Username: {{ $user->username }}</p>
                    <p><i class="fas fa-envelope"></i> Email: {{ $user->email }}</p>
                    <p><i class="fas fa-phone"></i> Phone: {{ $user->phonenumber ?? 'N/A'}}</p>
                    <p>
                        @if($user->isdeactivated)
                            <i class="fas fa-toggle-off"></i> Status: Deactivated
                        @else
                            <i class="fas fa-toggle-on"></i> Status: Activated
                        @endif
                    </p>
                </div>
                @if(Auth::check() && (Auth::user()->is($user) || Auth::user()->isAdmin))
                    <a href="{{ route('editProfile', ['username' => $user->username]) }}" class="edit-profile-btn">
                        <i class="fas fa-edit"></i> Edit Profile
                    </a>
                @endif

            </div>
        </div>

        <div class="user-bio">
            <h3><i class="fas fa-book"></i> Bio</h3>
            <p>{{ $user->bio }}</p>
        </div>
    </div>

    <div class="currentProjects">
        @if(Auth::check() && (Auth::user()->id == $user->id || Auth::user()->isAdmin))
            <h3>Projects</h3>
            <div class="showProjects">
                @forelse ($user->projects as $project)
                    <div class="user">
                        @include('partials.project', ['project' => $project])
                    </div>
                @empty
                    <h4>No projects found</h4>
                @endforelse
            </div>
            @if(Auth::user()->isAdmin && Auth::user()->id != $user->id)
            <h3>{{$user->username}}' s archived projects</h3>
            @else
            <h3>Your Archived Projects</h3>
            @endif
            <div class="showProjects">
                @forelse ($user->archived_projects as $project)
                    <div class="user">
                        @include('partials.project', ['project' => $project])
                    </div>
                @empty
                    <h4>No projects found</h4>
                @endforelse
            </div>
        @else
            @php
                $commonProjects = Auth::user()->projects->intersect($user->projects);
            @endphp

            @if($commonProjects->count() > 0)
                <h3>Common Projects</h3>
                <div class="showProjects">
                    @foreach($commonProjects as $project)
                        <div class="user">
                            @include('partials.project', ['project' => $project])
                        </div>
                    @endforeach
                </div>
            @else
                <p>No common projects found</p>
            @endif
        @endif
    </div>
</div>

@endsection