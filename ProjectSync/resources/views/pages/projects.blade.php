@extends('layouts.app')

@section('title', 'Projects')

@section('content')

<link href="{{ asset('css/projects.css') }}" rel="stylesheet">

<section id="projects">
    <article>
        <h2>Create New Project</h2>
        <form class="project-form" method="POST" action="{{ route('create_project') }}">
            @method('PUT')
            @csrf
            Project Name: <br><input type="text" name="name" class="project-form" required>
            {{-- Description: <input type="text" name="description" required> --}}
            <br>Delivery Date:<br><input type="date" name="delivery_date" class="project-form" required>
            <br>
            <button type="submit" class="button">+</button>
        </form>
    </article>

    <h2>Your active projects:</h2>    
    @if (count($projects) > 0)
        @each('partials.project', $projects, 'project')
    @else
        <p>Looks like you aren't related to any currenly active projects. You can start by creating a new one!</p>
    @endif

</section>

@endsection