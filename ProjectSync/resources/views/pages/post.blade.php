<link rel="stylesheet" href="{{ asset('css/post.css') }}">

@extends('layouts.app')

@section('title', 'Forum')

@section('content')

<div class="ForumBody">
    <div class="backButton">
        <h3><a href="/projects/{{ request('project_id') }}/forum">&larr;</a></h3>
    </div>

</div>

@endsection