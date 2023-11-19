@extends('layouts.app')

@section('title', 'Projects')

@section('content')

<section id="projects">
    <article class="project-form">
        <h3>Create New Project</h3>
        <form class="new_project_form" method="POST" action="{{ route('create_project') }}">
            @method('PUT')
            @csrf
            Project Name: <input type="text" name="name"required>
            {{-- Description: <input type="text" name="description" required> --}}
            Delivery Date: <input type="date" name="delivery_date">
            <button type="submit" class="button">+</button>
        </form>
    </article>
    
    @each('partials.project', $projects, 'project')
    
</section>

@endsection