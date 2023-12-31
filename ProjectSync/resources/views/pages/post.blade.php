<link rel="stylesheet" href="{{ asset('css/post.css') }}">

@extends('layouts.app')

@section('title', 'Forum')

@section('content')

<script type="text/javascript" src="{{ asset('js/post_upvotes.js') }}" defer></script>
<script type="text/javascript" src="{{ asset('js/post.js') }}" defer></script>
<div class="post" data-id="{{$post->id}}">
    <div class="Body">
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
            <h3><a href="/projects/{{ request('project_id') }}/forum">&larr;Go Back</a></h3>
        </div>

        <div class="actualPost">
            <div class="postHeader">
                <div class="upvote-downvote">
                    <form method="POST" action="{{ route('post.upvote', ['id' => $post->id]) }}">
                        @csrf
                        <input type="hidden" name="upvote" class="post-form" value="{{ 'up' }}">
                        @if ($post->project->archived)
                        <button type="submit" class="upvote-button" data-action="up" disabled>&#9650;</button>
                        @elseif ($post->user_upvoted(Auth::user()))
                        <button type="submit" class="upvote-button-pressed" data-action="up">&#9650;</button>
                        @else
                        <button type="submit" class="upvote-button" data-action="up">&#9650;</button>
                        @endif
                    </form>
                    <span class="upvotes-count">{{ $post->upvotes }}</span>
                    <form method="POST" action="{{ route('post.upvote', ['id' => $post->id]) }}">
                        @csrf
                        <input type="hidden" name="upvote" class="post-form" value="{{ 'down' }}">
                        @if ($post->project->archived)
                        <button type="submit" class="downvote-button" data-action="down" disabled>&#9660;</button>
                        @elseif ($post->user_downvoted(Auth::user()))
                        <button type="submit" class="downvote-button-pressed" data-action="down">&#9660;</button>
                        @else
                        <button type="submit" class="downvote-button" data-action="down">&#9660;</button>
                        @endif
                    </form>
                </div>
        
                <div class="postInfo">
                    <div class="author">
                        <p>Created by: {{$post->author->username ?? "deleted"}}</p>
                        <p>Date: {{$post->date}}</p>
                        @if ($post->isedited)
                           <p>(edited)</p>
                        @endif
                    </div>

                    <div class="postTitle">
                        <h3>{{$post->title}}</h3>
                    </div>

                    <div class="postDescription">
                        <p>{{$post->description}}</p>
                    </div>
                </div>
            </div>

            <div class="postSettings">

                @if(!$post->project->archived)
                @if (Auth::user()->id == $post->author_id || Auth::user()->isAdmin)
                <button onclick="toggleEditPost()" id="toggle_post_edit">Show Edit Settings</button>
                <div id= "alterPostOptions" class="alterPostOptions" style="display: none;">
                    <h3 class="title">Edit Post</h3>
                    @if (Auth::user()->id == $post->author_id)
                    <div class="editPost">
                        <form method="POST" action="{{ route('post.update', ['id' => $post->id]) }}" class="updatePostForm">
                            @csrf
                            @method('POST')
                            <p class="createPost">Description:</p>
                            <textarea name="description" class="post-form-textarea" placeholder="{{ $post->description }}"></textarea>
                            <input type="hidden" name="project_id" class="post-form" value="{{ $post->project_id }}">
                            <button type="submit" class="button edit-button"><i class="fas fa-edit"></i> Edit</button>
                        </form> 
                    </div>
                    @endif
                    <div class="deletePost">
                        <form method="POST" action="{{ route('post.delete', ['id' => $post->id]) }}" class="deletePostForm">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="project_id" class="post-form" value="{{ $post->project_id }}">
                            <button type="submit" class="deleteButton" class="button delete-button"><i class="fas fa-trash-alt"></i> Delete</button>
                        </form>            
                    </div>
                </div>
                @endif
                @endif
            </div>
        </div>

        @if(!$post->project->archived)
        <div class="createComment">
            <form method="POST" action="{{ route('postComment.create') }}" class="createPostCommentForm">
                @csrf
                @method('PUT')
                <h3>Create comment</h3>
                <textarea name="comment" class="comment-form-textarea" placeholder="Ex: This post was very helpfull thx"></textarea>
                <input type="hidden" name="project_id" class="post-form" value="{{ $post->project_id }}">
                <input type="hidden" name="post_id" class="post-form" value="{{ $post->id }}">
                <button type="submit" class="CreatePost">Create</button>
            </form>   
        </div>
        @endif
    
        <div class="showComments">
            <p class="commentCount">Found {{count($postComments)}} comments.</p>
            @forelse ($postComments as $postComment)
            <div class="commentCard">
                <div class="commentHeader">
                    <p>Author:{{$postComment->author->username ?? "deleted"}}</p>
                    <p>Date:{{$postComment->date}}</p>
                    @if($postComment->isedited)
                        <p>Edited</p>
                    @endif
                </div>
    
                <div class="commentBody">
                    <p>{{$postComment->comment}}</p>
                </div>
                @if(!$post->project->archived)
                @if (Auth::user()->id == $postComment->author_id || Auth::user()->isAdmin)
                    <button onclick="toggleEditComment({{$postComment->id}})" class="toggle_comment_edit" id="toggle_comment_edit" data-id="{{ $postComment->id }}">Edit</button>
                    <div class="editComment" id="editComment" data-id="{{ $postComment->id }}" style="display:none;">
                        <h4 class="edit">Edit Comment</h4>
                        @if (Auth::user()->id == $postComment->author_id)
                            <form method="POST" action="{{ route('postComment.update', ['id' => $postComment->id]) }}" class="updatePostForm">
                                @csrf
                                @method('POST')
                                <p class="createPost">Comment:</p>
                                <textarea name="comment" class="post-form" placeholder="{{ $postComment->comment }}"></textarea>
                                <input type="hidden" name="project_id" class="post-form" value="{{ request('project_id') }}">
                                <button type="submit" class="button edit-button"><i class="fas fa-edit"></i> Edit</button>
                            </form> 
                        @endif

                        <form method="POST" action="{{ route('postComment.delete', ['id' => $postComment->id]) }}" class="deletePostForm">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" name="project_id" class="post-form" value="{{ request('project_id') }}">
                            <button type="submit" class="button delete-button"><i class="fas fa-trash-alt"></i> Delete</button>
                        </form>
                    </div>
                @endif
                @endif
            </div>
            @empty
               <p class="noComments">No comments</p>
            @endforelse
        </div>
    
    </div>
</div>

@endsection