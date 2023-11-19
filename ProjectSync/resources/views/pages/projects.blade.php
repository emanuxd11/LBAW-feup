@extends('layouts.app')

@section('title', 'Projects')

@section('content')

<section id="projects">
    <article class="project-form">
        <form class="new_project">
            <input type="text" name="name" placeholder="new project">
        </form>
    </article>
    
    @each('partials.project', $projects, 'project')
    
</section>

@endsection