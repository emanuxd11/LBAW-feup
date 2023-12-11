@extends('layouts.app')

@section('title', 'Projects')

@section('content')

<link href="{{ asset('css/projects.css') }}" rel="stylesheet">

<section id="projects">
    <div id="toggle-create-project" class="hover-card">
        <h2>Create New Project</h2>
        <form class="project-form" method="POST" action="{{ route('create_project') }}">
            @method('PUT')
            @csrf
            <div class="input-group">
                <label for="project-name">Project Name:</label>
                <input type="text" name="name" id="project-name" class="project-input" required>
            </div>
    
            <div class="input-group">
                <label for="delivery-date">Delivery Date:</label>
                <input type="date" name="delivery_date" id="delivery-date" class="project-input" required>
            </div>
            <button type="submit" id="create-project-button">
                <i class="fas fa-plus"></i> Add Project
            </button>
        </form>
    </div>

    <h2>Your active projects:</h2>    
    <div id="active-projects">
        @if (count($projects) > 0)
            @each('partials.project', $projects, 'project')
        @else
            <p>Looks like you aren't related to any currently active projects. You can start by creating a new one!</p>
        @endif
    </div>
</section>

@endsection
