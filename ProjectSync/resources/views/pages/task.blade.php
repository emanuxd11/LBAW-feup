<!-- Updated task.blade.php -->

@extends('layouts.app')

@section('title', $task->name)

@section('content')

    <link href="{{ asset('css/projects.css') }}" rel="stylesheet">

    <div class="task-profile">
        <div class="task-header">
            <h1 class="task-title">{{$task->name}}</h1>
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
                        @each('partials.member', $task->members, 'user')
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
                <input type="text" name="description" class="project-form">
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
                <input type="text" name="username" class="project-form">
                <button type="submit" class="button edit-button"><i class="fas fa-edit"></i> Edit</button>
            </form>
        

            <form method="POST" action="{{ route('delete_task',['id' => $task->id]) }}">
                @method('DELETE')
                @csrf
                <button type="submit" class="button delete-button"><i class="fas fa-trash-alt"></i> Delete</button>
            </form>
        </div>
    </div>
@endsection
