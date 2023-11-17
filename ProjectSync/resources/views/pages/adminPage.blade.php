@extends('layouts.app')

@section('title', 'AdminPage')

@section('content')

<div id="AdminPage">
    <div class="currentUsers">
        <h3>Users</h3>
        <div class="searchUsers">
            <div class="searchBar">
                <form action="{{ route('adminPage.search') }}" method="GET">
                    <input type="text" name="user_query" placeholder="Search Users...">
                    <button type="submit">Search Users</button>
                </form>
            </div>

            <div class="showUsers">
                @forelse ($userResults as $user)
                <div class="user">
                    <h4>{{$user->name}}</h4>
                    <h4>{{$user->email}}</h4>
                </div>
                @empty
                <h4>No users found</h4>
                @endforelse
            </div>
        </div>
    </div>
    
    <div class="currentProjects">
        <h3>Projects</h3>
        <div class="searchProjects">
            <div class="searchBar">
                <form action="{{ route('adminPage.search') }}" method="GET">
                    <input type="text" name="project_query" placeholder="Search Projects...">
                    <button type="submit">Search Projects</button>
                </form>
            </div>
            <div class="showProjects">
                @forelse ($projectResults as $project)
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