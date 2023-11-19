@extends('layouts.app')

@section('title', $project->name)

@section('content')
    <section id="project">
        <h2>{{ $project->name }}</h2>
        <p>Coordinator: <a href="{{ route('profilePage', ['username' => $project->getCoordinator()->username]) }}"><span>{{ $project->getCoordinator()->name }}</span></a></p>

        <h3>Project Members</h3>
        <ul id="project-member-list">
            @each('partials.member', $project->members, 'user')
        </ul>
            
        <div id="tasks">
            <h3>Tasks</h3>
            <form class="new_task_form" method="POST" action="{{ route('create_task', ['project_id' => $project->id]) }}">
                @method('PUT')
                @csrf
                Name: <input type="text" name="name"required>
                Description: <input type="text" name="description" required>
                Delivery Date: <input type="date" name="delivery_date">
                <button type="submit" class="button">+</button>
            </form>

            <!-- show tasks -->
            <ul id="task-list">
                @each('partials.task', $project->tasks, 'task')
            </ul>

        </div>
    </section>
@endsection