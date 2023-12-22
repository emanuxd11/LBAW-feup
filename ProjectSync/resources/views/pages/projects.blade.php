@extends('layouts.app')

@section('title', 'Projects')

@section('content')

<link href="{{ asset('css/projects.css') }}" rel="stylesheet">
<link href="{{ asset('css/sidebar.css') }}" rel="stylesheet">

@include('partials.sidebar', ['current_project_id' => -1])

<section id="projects">
    @include('partials.messages')
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
</section>

<style>
    section#content {
        grid-template-columns: 70px 1fr;
        grid-template-rows: auto;
        grid-template-areas: "sidebar content";
        height: 80vh;
    }

    @media (min-width: 2500px) {
        section#content {
            height: 100vh;
        }
    }
</style>

@endsection
