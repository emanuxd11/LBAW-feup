@extends('layouts.app')

@section('title', 'Projects')

@section('content')

<link href="{{ asset('css/projects.css') }}" rel="stylesheet">

<section id="projects">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @elseif($errors->all())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
    <div id="toggle-create-project" class="hover-card">
        <h2>Create New Project</h2>
        <form class="project-form" method="POST" action="{{ route('create_project') }}">
            @method('PUT')
            @csrf
            <div class="input-group">
                <label for="project-name">Project Name:</label>
                <input type="text" name="name" id="project-name" class="project-input" required placeholder="ex: Project X">
            </div>
            
            <div class="input-group">
                <label for="delivery-date">Delivery Date:</label>
                <input type="date" name="delivery_date" id="delivery-date" class="project-input" required placeholder="ex: 2023-12-31">
            </div>

            <div class="bottomHalf">
                <div class="input-group-bottom">
                    <label for="description">Description:</label>
                    <textarea name="description" id="description" class="project-input" required placeholder="ex: The objective of this project is to...."></textarea>
                </div>
    
                <button type="submit" id="create-project-button">
                    <i class="fas fa-plus"></i> Add Project
                </button>
            </div>
        </form>
    </div>

    <h2>Your favorite projects:</h2>    
    <div id="favorite-projects">
        @if (count(Auth::user()->favorite_projects()) > 0)
            @each('partials.project', Auth::user()->favorite_projects(), 'project')
        @else
            <p>Looks like you haven't marked any projects as favorite yet. You can do this by clicking the star icon on a project's page!</p>
        @endif
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
