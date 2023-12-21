@extends('layouts.app')

@section('title', $project->name)

@section('content')

<script type="text/javascript" src="{{ asset('js/project_fav.js') }}" defer></script>
<script type="text/javascript" src="{{ asset('js/project_func.js') }}" defer></script>
<script src="{{ asset('js/search_tasks.js') }}" defer></script>
<script type="text/javascript" src="{{ asset('js/project_changes.js') }}" defer></script>


<link href="{{ asset('css/project.css') }}" rel="stylesheet">
<link href="{{ asset('css/sidebar.css') }}" rel="stylesheet">

@include('partials.sidebar')
@include('partials.members')

@if($project->archived)
    <h1>This project is has been archived by the coordinator.</h1>
@endif

{{-- Hidden div for project coordinator settings --}}
@if($project->isCoordinator(Auth::user()))
    <div id="project-settings-container" class="hidden modal project-info-card scrollable opaque-project-container">
        <h2>Project Settings</h2>

        {{-- change description --}}
        <form method="POST" action="{{ route('project.update',['project_id' => $project->id]) }}" class="project-form">
            @method('POST')
            @csrf
            <p class="info-label">Description:</p>
            <textarea name="description" class="project_description_area" placeholder="{{$project->description}}"></textarea>
            <input type="date" name="delivery_date" class="project_delivery_date">
            <button type="submit" class="button edit-button"><i class="fas fa-edit"></i> Edit</button>
        </form>

        {{-- changes record --}}

        <form class="project-form">
            <a href="{{ route('project_changes', ['project_id' => $project->id]) }}" id="changes-button" class="button"><i class="fas fa-history"></i></a>
        </form>

        {{-- archive --}}
        <button class="archive-button button" onclick="showPopup('archive-popup');">
            <i class="fa-solid fa-box-archive"></i>
        </button>
        <form class="project-form" method="POST" 
                action="{{ route('archive', ['project_id' => $project->id]) }}" 
                id="archiveProjectForm">
            @method('POST')
            @csrf
            <div id="archive-popup" class="confirmation-popup hidden">
                <p>Are you sure you want to archive "{{ $project->name }}"?<br>(This action cannot be undone!)</p>
                <button type="button" class="button cancel-button" onclick="hidePopup('archive-popup')">No</button>
                <button class="button confirm-button">Yes</button>
            </div>
        </form>

        {{-- change coordinator --}}
        <form class="project-form" method="POST" 
                action="{{ route('assign.new.coordinator', ['project_id' => $project->id]) }}" 
                id="newProjectCoordinatorForm">
            @method('POST')
            @csrf
            <select id="username" name="new_id" class="project-form-input">
                <option value="" selected disabled>--------</option>
                @foreach($project->members as $user)
                    @if (!$project->isCoordinator($user))
                        <option value={{ $user->id }}>{{ $user->name . ' (' . $user->username . ')' }}</option>
                    @endif
                @endforeach
            </select>
            <input type="hidden" name="old_id" value="{{$project->getCoordinator()->id}}">
            <button type="submit" class="project-submit-button">Submit</button>
        </form>
    </div> 
@endif

{{-- Hidden div for creating tasks --}}
<div id="create-task-container" class="hidden modal project-info-card opaque-project-container">
    <h2>Create New Task</h2>
    <form class="task-form project-form" method="POST" action="{{ route('create_task', ['project_id' => $project->id]) }}">
        @method('PUT')
        @csrf

        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" required placeholder="ex: Create New Navbar">

        <label for="description">Description:</label><br>
        <input type="text" id="description" name="description" required placeholder="ex: Create Navbar with four different buttons ...">
        
        <label for="delivery_date">Delivery Date:</label><br>
        <input type="date" id="delivery_date" name="delivery_date">
        
        <button type="submit" class="button"><i class="fas fa-plus"></i></button>
    </form>
</div>

<section id="project">
    @include('partials.messages')

    <div class="project-info-card">
        <div id="project-links">
            @if(!Auth::user()->isadmin)
                <div id="favorite-button-container">
                    <form method="POST" action="{{ route('project.favorite', ['project_id' => $project->id]) }}">
                        @csrf
                        @if($project->isFavoriteOf(Auth::user()))
                            <a type="submit" id="favorite-button" class="favorite-button-solid scales-on-hover" data-action="unfavorite-project">
                                <i class="fa-solid fa-star"></i>
                            </a>
                        @else
                            <a type="submit" id="favorite-button" class="favorite-button-empty scales-on-hover" data-action="favorite-project">
                                <i class="fa-regular fa-star"></i>
                            </a>
                        @endif
                        {{ $project->name }}
                    </form>
                </div>
            @endif

            <div class="" id="search-task-container">
                <form class="task-form project-form" id="search_task_form">
                    <input type="text" name="task" 
                        required placeholder="Search tasks by name or status" 
                        id="search_task_input" class="project-form">
                </form>
            </div>
            
            <div id="forumLinkContainer">
                <a href="{{ route('forum.show', ['id' => $project->id]) }}" class="scales-on-hover" type="submit">
                    <i class="fas fa-comments"></i>
                </a>
                Forum
            </div>

            <a type="submit" id="project-settings-activate" class="scales-on-hover" onclick="showProjectSettings(event)">
                <i class="fa-solid fa-gear"></i>
            </a>
        </div>
    </div>
        
    <div id="tasks">
        <div id="task-container">
            <div class="task-list-container" id="task-todo-container">
                <h3>To Do</h3>
                <div class="scrollable task-list-inner-container">
                    <ul id="tasks-todo" class="task-list"></ul>
                </div>
                <div id="addTaskButton">
                    <i class="fa-solid fa-plus scales-on-hover" onclick="showCreateTask(event)"></i>
                    Add Task
                </div>
            </div>

            <div class="task-list-container scrollable" id="task-doing-container">
                <h3>Doing</h3>
                <div class="scrollable task-list-inner-container">
                    <ul id="tasks-doing" class="task-list"></ul>
                </div>
                <div id="addTaskButton">
                    <i class="fa-solid fa-plus scales-on-hover" onclick="showCreateTask(event)"></i>
                    Add Task
                </div>
            </div>

            <div class="task-list-container scrollable" id="task-done-container">
                <h3>Done</h3>
                <div class="scrollable task-list-inner-container">
                    <ul id="tasks-done" class="task-list"></ul>
                </div>
                <div id="addTaskButton">
                    <i class="fa-solid fa-plus scales-on-hover" onclick="showCreateTask(event)"></i>
                    Add Task
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
