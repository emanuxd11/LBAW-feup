@extends('layouts.app')

@section('title', $project->name)

@section('content')
    <section id="project">
        <h2>{{ $project->name }}</h2>
        <p>Coordinator: <a href="{{ route('profilePage', ['username' => $project->getCoordinator()->username]) }}"><span>{{ $project->getCoordinator()->name }}</span></a></p>

        <h3>Project Members</h3>
        <ul id="project-member-list">
            @each('partials.item', $project->members, 'user')
        </ul>
            
        <h3>Tasks</h3>
        <!-- show tasks -->
        <form class="new_task">
            <input type="text" name="description" placeholder="new task">
        </form>
    </section>
@endsection