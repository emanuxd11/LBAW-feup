<!-- Updated task.blade.php -->

@extends('layouts.app')

@section('title', $task->name)

@section('content')

    <link href="{{ asset('css/projects.css') }}" rel="stylesheet">

    <div class="task-profile">

        <div class="task-header">
            <div class="task-title-div">
                <div class="backButton">
                    <h3><a href="/projects/{{ request('project_id') }}">&larr;</a></h3>
                </div>
                <h1 class="task-title">{{$task->name}}</h1>
            </div>
            <p class="task-status">Status: {{$task->status}}</p>
        </div>

        <div class="task-details">
            <div class="task-card">
                <div class="card-content">
                    <p class="info-label">Description:</p>
                    <p>{{$task->description}}</p>
                </div>
            </div>

            <div class="task-card">
                <div class="card-content">
                    <p class="info-label">Task started on:</p>
                    <p>{{$task->start_date}}</p>
                </div>
            </div>

            <div class="task-card">
                <div class="card-content">
                    <p class="info-label">Needs to be done by:</p>
                    <p>{{$task->delivery_date}}</p>
                </div>
            </div>

            <div class="assigned-members">
                <p class="info-label">Assigned to:</p>
                    <ul>
                        @foreach ($task->members as $user)
                            <div class="member-container">
                                @include('partials.member', ['user' => $user])
                                <form method="POST" action="{{ route('task.remove.user',['id' => $task->id]) }}" class="project-form">
                                    @method('DELETE')
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{$user->id}}" class="project-form">
                                    <button type="submit" class="button delete-button-user"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </div>
                        @endforeach
                    </ul>
            </div>
        </div>

        <div class="task-actions">
            <h3 class="edit-task">Edit Task</h3>
            <form method="POST" action="{{ route('edit_task',['id' => $task->id]) }}" class="project-form">
                @method('POST')
                @csrf
                <p class="info-label">Name:</p>
                <input type="text" name="name" class="project-form">
                <p class="info-label">Description:</p>
                <textarea name="description" class="task_description"></textarea>
                <p class="info-label">Status:</p>
                <select id="status" name="status" class="">
                    <option value="" selected disabled>{{$task->status}}</option>
                    <option value="To Do">To Do</option>
                    <option value="Doing">Doing</option>
                    <option value="Done">Done</option>
                </select>
                <p class="info-label">Delivery Date:</p>
                <input type="date" name="delivery_date" class="">
                <p class="info-label">Add user:</p>
                <select id="username" name="username" class="project-form">
                    <option value="" selected disabled>--------</option>
                    @foreach($task->members_not_in_task as $user)
                    <option value={{$user->username}}>{{ $user->name . ' (' . $user->username . ')' }}</option>
                    @endforeach
                    
                </select>
                <button type="submit" class="button edit-button"><i class="fas fa-edit"></i> Edit</button>
            </form>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @elseif(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
        
            <form method="POST" action="{{ route('delete_task',['id' => $task->id]) }}">
                @method('DELETE')
                @csrf
                <button type="submit" class="button delete-button"><i class="fas fa-trash-alt"></i> Delete</button>
            </form>
        </div>
    </div>
@endsection
