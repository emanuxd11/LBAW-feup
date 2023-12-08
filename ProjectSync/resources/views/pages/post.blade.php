<link rel="stylesheet" href="{{ asset('css/post.css') }}">

@extends('layouts.app')

@section('title', 'Forum')

@section('content')

<script type="text/javascript" src="{{ asset('js/post_upvotes.js') }}" defer></script>
<div class="post" data-id="{{$post->id}}">
    <div class="PostBody">
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
        <div class="backButton">
            <h3><a href="/projects/{{ request('project_id') }}/forum">&larr;</a></h3>
        </div>
    
        <div class="postTitle">
            <h3>{{$post->title}}</h3>
        </div>
    
        <div class="upvote-downvote">
            <form method="POST" action="{{ route('post.upvote', ['id' => $post->id]) }}">
                @csrf
                <input type="hidden" name="upvote" class="post-form" value="{{ 'up' }}">
                <button type="submit" class="upvote-button" data-action="up">&#9650;</button>
            </form>
            <span class="upvotes-count">{{ $post->upvotes }}</span>
            <form method="POST" action="{{ route('post.upvote', ['id' => $post->id]) }}">
                @csrf
                <input type="hidden" name="upvote" class="post-form" value="{{ 'down' }}">
                <button type="submit" class="downvote-button" data-action="down">&#9660;</button>
            </form>
        </div>
    
        <div class="author">
            <p>{{$post->author->username}}</p>
        </div>
    
        <div class="postDescription">
            <p>{{$post->description}}</p>
        </div>
    
        @if (Auth::user()->id == $post->author_id || Auth::user()->isAdmin)
        <div class="alterPostOptions">
            @if (Auth::user()->id == $post->author_id)
            <div class="editPost">
                <form method="POST" action="{{ route('post.update', ['id' => $post->id]) }}" class="updatePostForm">
                    @csrf
                    @method('POST')
                    <p class="createPost">Description:</p>
                    <textarea name="description" class="post-form" placeholder="{{ $post->description }}"></textarea>
                    <input type="hidden" name="project_id" class="post-form" value="{{ $post->project_id }}">
                    <button type="submit" class="editPost">Edit</button>
                </form> 
            </div>
            @endif
            <div class="deletePost">
                <form method="POST" action="{{ route('post.delete', ['id' => $post->id]) }}" class="deletePostForm">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="project_id" class="post-form" value="{{ $post->project_id }}">
                    <button type="submit" class="deletePost">Delete</button>
                </form>            
            </div>
        </div>
        @endif
    
        <div class="createComment">
            <form method="POST" action="{{ route('postComment.create') }}" class="createPostCommentForm">
                @csrf
                @method('PUT')
                <h3>Create comment</h3>
                <textarea name="comment" class="post-form"></textarea>
                <input type="hidden" name="project_id" class="post-form" value="{{ $post->project_id }}">
                <input type="hidden" name="post_id" class="post-form" value="{{ $post->id }}">
                <button type="submit" class="deletePost">Create</button>
            </form>   
        </div>
    
        <div class="showComments">
            @foreach ($postComments as $postComment)
            <div class="commentCard">
                <div class="commentHeader">
                    <p>Author:{{$postComment->author->username}}</p>
                    @if($postComment->isedited)
                        <p>Edited</p>
                    @endif
                    <p>Date:{{$postComment->date}}</p>
                </div>
    
                <div class="commentBody">
                    <h4>Comment</h4>
                    <p>{{$postComment->comment}}</p>
                </div>
    
                <div class="editComment">
                    @if (Auth::user()->id == $postComment->author_id || Auth::user()->isAdmin)
                        @if (Auth::user()->id == $postComment->author_id)
                            <form method="POST" action="{{ route('postComment.update', ['id' => $postComment->id]) }}" class="updatePostForm">
                                @csrf
                                @method('POST')
                                <p class="createPost">Comment:</p>
                                <textarea name="comment" class="post-form" placeholder="{{ $postComment->comment }}"></textarea>
                                <input type="hidden" name="project_id" class="post-form" value="{{ request('project_id') }}">
                                <button type="submit" class="editPost">Edit</button>
                            </form> 
                        @endif
    
                        <form method="POST" action="{{ route('postComment.delete', ['id' => $postComment->id]) }}" class="deletePostForm">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="project_id" class="post-form" value="{{ request('project_id') }}">
                            <button type="submit" class="deletePost">Delete</button>
                        </form>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    
    </div>
</div>

@endsection