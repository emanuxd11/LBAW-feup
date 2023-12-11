<!-- Updated task.blade.php -->

@extends('layouts.app')

@section('title', $task->name)

@section('content')

    <link href="{{ asset('css/projects.css') }}" rel="stylesheet">
    <script type="text/javascript" src="{{ asset('js/task.js') }}" defer></script>

    <div class="task-profile">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @elseif(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="task-header">
            <div class="task-title-div">
                <div class="backButton">
                    <h3><a href="/projects/{{ request('project_id') }}">&larr;</a></h3>
                </div>
                <h1 class="task-title">{{$task->name}}</h1>
            </div>
            <p class="task-status">Status: {{$task->status}}</p>
        </div>

        <div class="task-details">
            <div class="task-card">
                <div class="card-content">
                    <p class="info-label">Description:</p>
                    <p>{{$task->description}}</p>
                </div>
            </div>

            <div class="task-card">
                <div class="card-content">
                    <p class="info-label">Task started on:</p>
                    <p>{{$task->start_date}}</p>
                </div>
            </div>

            <div class="task-card">
                <div class="card-content">
                    <p class="info-label">Needs to be done by:</p>
                    <p>{{$task->delivery_date ?? "No delivery date available"}}</p>
                </div>
            </div>

            <div class="assigned-members">
                <p class="info-label">Assigned to:</p>
                    <ul>
                        @foreach ($task->members as $user)
                            <div class="member-container">
                                @include('partials.task_member', ['user' => $user])
                                <form method="POST" action="{{ route('task.remove.user',['id' => $task->id]) }}" class="project-form">
                                    @method('DELETE')
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{$user->id}}" class="project-form">
                                    <button type="submit" class="button delete-button-user"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </div>
                        @endforeach
                    </ul>
            </div>
        </div>

        <button onclick="toggleEditTask()" id="toggle_task_edit">Show Edit Settings</button>
        <div class="task-actions" id="task-actions" style="display:none;">
            <form method="POST" action="{{ route('edit_task',['id' => $task->id]) }}" class="project-form">
                @method('POST')
                @csrf
                <p class="info-label">Name:</p>
                <input type="text" name="name" class="project-form-input" placeholder="ex: Create Articles" style="background-color: #f7f3e9; color: #172b4d;">
                <p class="info-label">Description:</p>
                <textarea name="description" class="task_description_area" placeholder="ex: Create Navbar with four different buttons ..."></textarea>
                <p class="info-label">Status:</p>
                <select id="status" name="status" class="project-form-input">
                    <option value="" selected disabled>{{$task->status}}</option>
                    <option value="To Do">To Do</option>
                    <option value="Doing">Doing</option>
                    <option value="Done">Done</option>
                </select>
                <p class="info-label">Delivery Date:</p>
                <input type="date" name="delivery_date" class="project-form-input" style="background-color: #f7f3e9; color: #172b4d;">
                <p class="info-label">Add user:</p>
                <select id="username" name="username" class="project-form-input">
                    <option value="" selected disabled>--------</option>
                    @foreach($task->members_not_in_task as $user)
                    <option value={{$user->username}}>{{ $user->name . ' (' . $user->username . ')' }}</option>
                    @endforeach
                    
                </select>
                <button type="submit" class="button edit-button"><i class="fas fa-edit"></i> Edit</button>
            </form>
        
            <form method="POST" action="{{ route('delete_task',['id' => $task->id]) }}">
                @method('DELETE')
                @csrf
                <button type="submit" class="button delete-button"><i class="fas fa-trash-alt"></i> Delete</button>
            </form>
        </div>

        <h3>Comments</h3>

        <div class="createComment">
            <form method="POST" action="{{ route('taskComment.create',['id' => $task->id]) }}">
                @method('PUT')
                @csrf
                <textarea name="comment" class="comment_creator" placeholder="ex: I can take this task..."></textarea>
                <button type="submit" id="submit-create-comment">Create</button>
            </form>
        </div>

        <div class="listOfComments">
            @forelse ($taskComments as $taskComment)
                <div class="commentCard">
                    <div class="commentHeader">
                        <p>Author:{{$taskComment->user->username}}</p>
                        <p>Date:{{$taskComment->created_at}}</p>
                        @if($taskComment->isedited)
                            <p>Edited</p>
                        @endif
                    </div>
        
                    <div class="commentBody">
                        <p>{{$taskComment->comment}}</p>
                    </div>
        
                    @if (Auth::user()->id == $taskComment->user_id || Auth::user()->isAdmin)
                    <button onclick="toggleEditComment({{$taskComment->id}})" class="toggle_comment_edit" id="toggle_comment_edit" data-id="{{ $taskComment->id }}">Edit</button>

                    <div class="editComment" id="editComment" data-id="{{ $taskComment->id }}" style="display:none;">
                        <h4>Edit Comment</h4>
                            @if (Auth::user()->id == $taskComment->user_id)
                                <form method="POST" action="{{ route('taskComment.update', ['id' => $task->id]) }}" class="updatePostForm">
                                    @csrf
                                    @method('POST')
                                    <p class="createPost">Comment:</p>
                                    <textarea name="comment" class="post-form" placeholder="{{ $taskComment->comment }}"></textarea>
                                    <input type="hidden" name="id" class="post-form" value="{{ $taskComment->id }}">
                                    <button type="submit" id="editPost">Edit</button>
                                </form> 
                            @endif

                            <form method="POST" action="{{ route('taskComment.delete', ['id' => $task->id]) }}" class="deletePostForm">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="id" class="post-form" value="{{ $taskComment->id }}">
                                <button type="submit" id="deletePost">Delete</button>
                            </form>
                    </div>
                    @endif
                </div>
            @empty
            <p class="noPosts">No posts</p>
            @endforelse
        </div>
    </div>
@endsection
