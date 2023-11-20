<link rel="stylesheet" href="{{ asset('css/profile.css') }}">

@extends('layouts.app')

@section('title', $user->name)

@section('content')

<div id="ProfilePage">
        <div class="currentUser">
            <div class="col-md-8 offset-md-2">
                <div class="card">
                    <div class="card-header">{{ $user->name }}'s Profile</div>

                    <div class="card-body">
                        <p>Username: {{ $user->username }}</p>
                        <p>Email: {{ $user->email }}</p>
                        <p>Phone Number: {{ $user->phonenumber ?? 'N/A'}}</p>

                        @if($user->isdeactivated)
                            <p>Status: Deactivated</p>
                        @else
                            <p>Status: Activated</p>
                        @endif

                        @if(Auth::check() && (Auth::user()->is($user) || Auth::user()->isAdmin))
                            <a href="{{ route('editProfile', ['username' => $user->username]) }}" class="btn btn-primary">Edit Profile</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="currentProjects">
            <div class="col-md-8 offset-md-2">
                <h3>Projects</h3>           
                <div class="showProjects">
                    @forelse ($user->projects as $project)
                        <div class="user">
                            <h4>{{$project->name}}</h4>
                        </div>
                    @empty
                        <h4>No projects found</h4>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection