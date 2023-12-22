@extends('layouts.app')

@section('title', $project->name)

@section('content')

<script type="text/javascript" src="{{ asset('js/project_fav.js') }}" defer></script>
<script type="text/javascript" src="{{ asset('js/project_func.js') }}" defer></script>
<script src="{{ asset('js/search_tasks.js') }}" defer></script>
<script type="text/javascript" src="{{ asset('js/project_changes.js') }}" defer></script>

<link href="{{ asset('css/project.css') }}" rel="stylesheet">
<link href="{{ asset('css/sidebar.css') }}" rel="stylesheet">

@include('partials.sidebar', ['current_project_id' => $project->id])
@include('partials.members')

@if($project->archived)
    <h1>This project is has been archived by the coordinator.</h1>
@endif

{{-- Hidden div for project coordinator settings --}}
@if($project->isCoordinator(Auth::user()))
    <div id="project-settings-container" class="hidden modal opaque-project-container scrollable">
        <h2 id="projectSettingsTitle">Project Settings</h2>

        {{-- change coordinator --}}
        <h3 class="changeCordinator" id="changeCoordinator">Change Coordinator</h3>
        <form class="project-form" method="POST" 
                action="{{ route('assign.new.coordinator', ['project_id' => $project->id]) }}" 
                id="newProjectCoordinatorForm">
            @method('POST')
            @csrf
            <div id="changeCoordinatorContainer">
                <select id="username" name="new_id" class="project-form-input">
                    <option value="" selected disabled>{{ $project->getCoordinator()->name }}</option>
                    @foreach($project->members as $user)
                        @if (!$project->isCoordinator($user))
                            <option value={{ $user->id }}>{{ $user->name . ' (' . $user->username . ')' }}</option>
                        @endif
                    @endforeach
                </select>
            
                <input type="hidden" name="old_id" value="{{$project->getCoordinator()->id}}">
                <button type="submit" class="project-submit-button">Confirm</button>
            </div>
        </form>

        {{-- changes record --}}
        <div style="display: flex;">
            <div id="changesAndArchiveWrapper">
                <form class="project-form-top">
                    <a href="{{ route('project_changes', ['project_id' => $project->id]) }}" id="changes-button" class="button">TimeLine  <i class="fas fa-history"></i></a>
                    <p class="info-label">View project change history</p>
                </form>
            </div>
    
            <div id="changesAndArchiveWrapper">
                <form class="project-form-top" method="POST" 
                        action="{{ route('archive', ['project_id' => $project->id]) }}" 
                        id="archiveProjectForm">
                    @method('POST')
                    @csrf
                    <a class="archive-button button" id="archive-button" onclick="showPopup('archive-popup');">
                        Archive <i class="fa-solid fa-box-archive"></i>
                    </a>
                    <p class="info-label">Archive this project</p>
                    
                    <div id="archive-popup" class="confirmation-popup hidden">
                        <p>Are you sure you want to archive "{{ $project->name }}"?<br>(This action cannot be undone!)</p>
                        <button type="button" class="button cancel-button" onclick="hidePopup('archive-popup')">No</button>
                        <button class="button confirm-button">Yes</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- change description --}}
        <div id="changeDescWrapper">
            <form method="POST" action="{{ route('project.update',['project_id' => $project->id]) }}" class="project-form">
                @method('POST')
                @csrf
                <p class="info-label">Description:</p>
                <textarea name="description" class="project_description_area" placeholder="{{$project->description}}"></textarea>
                <p class="info-label">Delivery Date (currently {{ strval($project->delivery_date) }}):</p>
                <input type="date" name="delivery_date" class="project_delivery_date">
            
                <div id="submitWrapper">
                    <button type="submit" class="button edit-button" id="submitChangesButton"><i class="fas fa-edit"></i> Save</button>
                    <button type="button" class="exit-button" onclick="hideProjectSettings(event)" id="close-project-settings">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div> 
@endif

{{-- Hidden div for creating tasks --}}
<div id="create-task-container" class="hidden modal opaque-project-container">
    <h2 id="projectSettingsTitle">Create New Task</h2>
    
    <form class="task-form project-form" method="POST" action="{{ route('create_task', ['project_id' => $project->id]) }}">
        @method('PUT')
        @csrf

        <div style="align-items:baseline;width:100%">
            <p class="info-label">Name:</p>
            <input type="text" id="name" name="name" required placeholder="ex: Create New Navbar">
    
            <p class="info-label">Description:</p>
            <textarea type="text" id="description" name="description" class="project_description_area" required placeholder="ex: Create Navbar with four different buttons ..."></textarea>
        
            <p class="info-label">Delivery Date:</p>
            <input type="date" id="delivery_date" name="delivery_date">
            
            <div id="submitWrapper">
                <button type="submit" class="button edit-button" id="submitChangesButton">Create</button>
                <button type="button" class="exit-button" onclick="hideCreateTask(event)" id="close-project-settings">
                    Cancel
                </button>
            </div>
        </div>
        
    </form>
</div>

{{-- Hidden div for project description --}}
<div id="project-description-container" class="hidden modal opaque-project-container">
    <h2 id="projectSettingsTitle">Project Description</h2>
    
    <div id="innerDescriptionContainer" class="scrollable">
        {{ $project->description }}
    </div>

    <div id="submitWrapper">
        <button type="button" class="button edit-button" id="submitChangesButton" onclick="hideProjectDescription(event)">Back</button>
    </div>
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
                            <a type="submit" id="favorite-button" class="favorite-button-solid scales-on-hover" data-action="unfavorite-project" title="Unfavorite Project">
                                <i class="fa-solid fa-star"></i>
                            </a>
                        @else
                            <a type="submit" id="favorite-button" class="favorite-button-empty scales-on-hover" data-action="favorite-project" title="Favorite Project">
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
                        id="search_task_input" class="project-form"
                        title="Search For Tasks">
                </form>
            </div>
            
            <div id="forumLinkContainer">
                <a href="{{ route('forum.show', ['id' => $project->id]) }}" class="scales-on-hover" type="submit" title="Open Forum">
                    <i class="fas fa-comments"></i>
                </a>
                Forum
            </div>

            @if($project->isCoordinator(Auth::user()))
                <div id="forumLinkContainer">
                    <a class="scales-on-hover" onclick="showProjectSettings(event)" title="Project Settings">
                        <i class="fa-solid fa-gear"></i>
                    </a>
                </div>
            @else
                <div id="forumLinkContainer">
                    <a class="scales-on-hover" onclick="showProjectDescription(event)" title="View Description">
                        <i class="fa-solid fa-scroll"></i>
                    </a>
                    Description
                </div>
            @endif
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

<style>
    @media (min-width: 2300px) {
        section#content {
            height: 100vh;
        }
    }
</style>

@endsection
