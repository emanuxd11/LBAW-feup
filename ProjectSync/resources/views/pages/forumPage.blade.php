<link rel="stylesheet" href="{{ asset('css/forum.css') }}">

@extends('layouts.app')

@section('title', 'Forum')

@section('content')

<script type="text/javascript" src="{{ asset('js/post_upvotes.js') }}" defer></script>
<script type="text/javascript" src="{{ asset('js/search_posts.js') }}" defer></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="ForumBody">
    <div class="backButton">
        <h3><a href="/projects/{{ request('id') }}">&larr;</a>List of Posts</h3>
    </div>

    <div class="createPost">
        <div class="errors">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @elseif ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        
        <form action="{{ route('post.create') }}" method="post">
            @method('PUT')
            @csrf
            <p class="createPost">Title:</p>
            <input type="text" name="title" class="title-form" placeholder="Post title...">
            <p class="createPost">Description:</p>
            <textarea name="description" class="post-form" placeholder="Post content..."></textarea>
            <input type="hidden" name="project_id" class="post-form" value="{{ request('id') }}">
            <button type="submit" class="button">POST</button>
        </form>
    </div>

    <div class="searchPosts">
        <div class="search-bar">
            <form id="searchForm" action="{{ route('forum.search', ['id' => request('id')]) }}" method="GET">
                @csrf
                <div class="filters">
                    <label>  
                      <input type="radio" name="filter" value="upvotes"> Best
                    </label>
                    
                    <label>
                      <input type="radio" name="filter" value="date_asc"> Oldest
                    </label>
                    
                    <label>
                      <input type="radio" name="filter" value="date_des"> Newest
                    </label>

                    <label>
                        <input type="radio" name="filter" value="none"> None
                    </label>
                </div>
                <div class="searchBarFlex">
                    <input type="text" class="searchPosts" id="searchInput" name="query" placeholder="Search Posts...">
                    <button type="submit"><p>Search Posts</p></button>
                </div>
            </form>
        </div>
    </div>
    
    <div class="listOfPosts" style="word-wrap: break-word;">
        <ul class="listOfPosts" id="postList">
            @forelse ($forumPosts as $post)
                @include('partials.post_preview', ['post' => $post])
            @empty
                <p class="noPosts">No posts</p>   
            @endforelse
        </ul>
    </div>

</div>

@endsection