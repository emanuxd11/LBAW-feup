<link rel="stylesheet" href="{{ asset('css/admin.css') }}">

@extends('layouts.app')

@section('title', 'AdminPage')

@section('content')
    <div class="container admin-page">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @elseif(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <div class="current-users">
            <h3>
                Users
                <a href="{{ route('adminPage.create_user_form') }}" class="addUser-link">
                    <button class="addUserButton">+</button>
                </a>
            </h3>
            <div class="search-users">
                <div class="search-bar">
                    <form action="{{ route('adminPage.search') }}" method="GET">
                        <input type="text" name="user_query" placeholder="Search Users...">
                        <button type="submit">Search Users</button>
                    </form>
                </div>

                <div class="show-users">
                    @forelse ($userResults as $user)
                    <div class="user">
                        
                        <div class="profile-image">
                            @if($user->profile_pic !== null && $user->profile_pic !== '')
                                <img src="{{ asset('storage/' . $user->profile_pic) }}" alt="Profile Picture">
                            @else
                                <img src="{{ asset('storage/profile_pictures/default-profile-pic.jpg') }}" alt="Default Profile Picture">
                            @endif
                        </div>

                        <h4><a href="{{ route('profilePage', ['username' => $user->username]) }}"><span>{{ $user->name }}</span></a></h4>
                        <h5 style="align-items: center;">
                            Username: {{$user->username}} | Email: {{$user->email}}
                            <div class="deleteOrBlock">
                                <form method="POST" action="{{ route('adminPage.block') }}">
                                    @csrf
                                    @method('POST')
                                    <input type="hidden" name="userId" value="{{ $user->id }}">
                                    <button type="submit" class="button">@if(!$user->isdeactivated)Block User @else Unblock User @endif</button>
                                </form>
                                <form method="POST" action="{{ route('adminPage.delete') }}">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="userId" value="{{ $user->id }}">
                                    <button type="submit" class="button">Delete User</button>
                                </form>
                            </div>
                        </h5>
                    </div>
                    @empty
                        <h4>No users found</h4>
                    @endforelse

                    <div class="linksToPage">
                        {{ $userResults->links('pagination::bootstrap-4') }}
                    </div>

                </div>
            </div>
        </div>

        <div class="current-projects">
            <h3>Projects</h3>
            <div class="search-projects">
                <div class="search-bar">
                    <form action="{{ route('adminPage.search') }}" method="GET">
                        <input type="text" name="project_query" placeholder="Search Projects...">
                        <button type="submit">Search Projects</button>
                    </form>
                </div>
                <div class="show-projects">
                    @forelse ($projectResults as $project)
                        <div class="project">
                            @include('partials.project', ['project' => $project])
                        </div>
                    @empty
                        <h4>No projects found</h4>
                    @endforelse

                    <div class="linksToPage">
                        {{ $projectResults->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection