@extends('layouts.app')

@section('title', $project->name)

@section('content')
    <section id="projects">
        @include('partials.project', ['project' => $project])
    </section>
@endsection