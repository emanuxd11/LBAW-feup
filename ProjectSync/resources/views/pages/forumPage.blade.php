@extends('layouts.app')

@section('title', 'Forum')

@section('content')

<div class="ForumBody">
    <div class="backButton">
        <h3><a href="/projects/{{ request('id') }}">Back</a></h3>
    </div>
    <div class="listOfPosts">
        <ul class="listOfPosts">
            @forelse ($forumPosts as $post)
                @include('partials.post_preview', ['post' => $post])
            @empty
                <p>No posts</p>   
            @endforelse
        </ul>
    </div>
</div>

@endsection