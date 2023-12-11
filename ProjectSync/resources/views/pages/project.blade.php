@extends('layouts.app')

@section('title', $project->name)

@section('content')

    <link href="{{ asset('css/project.css') }}" rel="stylesheet">
    <section id="project">
        <div id="project-info-card">
            <h2>{{ $project->name }}</h2>
            <div id="project-links">
                <a href="{{ route('profilePage', ['username' => $project->getCoordinator()->username]) }}" class="link">
                    <i class="fas fa-user"></i>
                    <p>{{ $project->getCoordinator()->name }}</p>
                </a>
                <a href="{{ route('forum.show', ['id' => $project->id]) }}" class="link">
                    <i class="fas fa-comments"></i>
                    <p>Forum</p>
                </a>
            </div>
        </div>
        <div id="project-members">
            <h3>Project Members</h3>
            <ul id="project-member-list">
                @if(count($project->members) <= 1)
                    <p id="no-members">Looks like nobody has been added to the project yet.</p>
                @endif
        
                @foreach($project->members as $user)
                    @if($project->isCoordinator($user))
                        @continue
                    @endif
                    <li data-id="{{ $user->id }}">
                        <div class="user-list-card">
                            <a href="{{ route('profilePage', ['username' => $user->username]) }}">
                                <div class="user-list-content">
                                    <span id="user-name-project">{{ $user->name . ' (' . $user->username . ')' }}</span>
                                </div>
                            </a>
                            @if($project->isCoordinator(Auth::user()))
                                <button class="remove-member-button button" onclick="showConfirmationPopup(event);">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <form class="project-form" method="POST" action="{{ route('remove_member', ['project_id' => $project->id, 'user_id' => $user->id]) }}" id="removeMemberForm">
                                    @method('DELETE')
                                    @csrf
                                    
                                </form>
                                <div id="confirmation-popup" class="confirmation-popup hidden">
                                    <p>Are you sure you want to remove {{ $user->name }} from the project?</p>
                                    <button class="button cancel-button">No</button>
                                    <button class="button confirm-button">Yes</button>
                                </div>
                            @endif
                        </div>
                    </li>

                @endforeach
            </ul>
            
            @if($project->isCoordinator(Auth::user()))
                <form class="project-form" id="addMemberForm">
                    Add new team members<br>
                    <input type="text" name="name" required placeholder="name or username" id="searchInput">
                    <ul id="searchResults"></ul>
                </form>
        
                <script src="{{ asset('js/search_user.js') }}" defer></script>
            @endif
        </div>
            
        <div id="tasks">
            <div class="task-card">
                <h3>Create New Task</h3>
                <form class="task-form project-form" method="POST" action="{{ route('create_task', ['project_id' => $project->id]) }}">
                    @method('PUT')
                    @csrf
                    Name: <br><input type="text" name="name" required>
                    <br>Description: <br><input type="text" name="description" required>
                    <br>Delivery Date: <br><input type="date" name="delivery_date">
                    <br><button type="submit" class="button"><i class="fas fa-plus"></i></button>
                </form>
            </div>
        
            <h3>Your pending tasks:</h3>
        
            <!-- task search -->
            <div class="task-card search_task">
                <form class="task-form project-form" id="search_task_form">
                    <p hidden id="hidden_task_attr">Looks like there are no tasks.</p>
                    <label id="hidden_task_search" for="search_task_input">Search for task:</label>
                    <input type="text" name="task" required placeholder="name or status" id="search_task_input" class="project-form">
                    <ul id="search_task_results"></ul>
                </form>
        
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        // Function to check and show/hide the message
                        function checkAndDisplayMessage() {
                            var ulElement = document.getElementById('search_task_results');
                            var pElement = document.getElementById('hidden_task_attr');
                
                            // Check if the ul is empty
                            if (ulElement && ulElement.childElementCount === 0) {
                                // If it's empty, unhide the p element
                                pElement.removeAttribute('hidden');
                            } else {
                                // If it's not empty, hide the p element
                                pElement.setAttribute('hidden', 'true');
                            }
                        }
                
                        // Call the function initially
                        checkAndDisplayMessage();
                
                        // Listen for changes in the ul (e.g., when AJAX content is added)
                        var observer = new MutationObserver(checkAndDisplayMessage);
                        observer.observe(document.getElementById('search_task_results'), { childList: true });
                    });
                </script>

                <script src="{{ asset('js/search_tasks.js') }}" defer></script>
            </div>
        </div>
    </section>
@endsection