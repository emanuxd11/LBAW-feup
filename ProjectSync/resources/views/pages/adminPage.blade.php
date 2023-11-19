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
                    <h4><a href="{{ route('profilePage', ['username' => $user->username]) }}"><span>{{ $user->name }}</span></a></h4>
                    <h5>
                        Username: {{$user->username}} | Email: {{$user->email}}
                        <form method="POST" action="{{ route('adminPage.block') }}">
                            @csrf
                            @method('POST')
                            <input type="hidden" name="userId" value="{{ $user->id }}">
                            <button type="submit" class="button">@if(!$user->isdeactivated)Block User @else Unblock User @endif</button>
                        </form>
                    </h5>
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