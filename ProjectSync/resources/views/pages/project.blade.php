@extends('layouts.app')

@section('title', $project->name)

@section('content')

    <script type="text/javascript" src="{{ asset('js/project_fav.js') }}" defer></script>
    <link href="{{ asset('css/project.css') }}" rel="stylesheet">

    @if($project->archived)
        <h1>This project is has been archived by the coordinator.</h1>
    @endif
    <section id="project">
        @include('partials.messages')

        <div id="project-info-card">
            <h2>{{ $project->name }}</h2>
            <div id="project-links">
                <a href="{{ route('profilePage', ['username' => $project->getCoordinator()->username]) }}" class="link">
                    <i class="fas fa-user"></i>
                    <p>{{ $project->getCoordinator()->name }}</p>
                </a>

                @if(!Auth::user()->isadmin)
                    <div class="favorite-button">
                        <form method="POST" action="{{ route('project.favorite', ['project_id' => $project->id]) }}">
                            @csrf
                            @if($project->isFavoriteOf(Auth::user()))
                                <button type="submit" id="favorite-button" class="favorite-button-solid" data-action="unfavorite-project">
                                    <i class="fa-solid fa-star"></i>
                                </button>
                            @else
                                <button type="submit" id="favorite-button" class="favorite-button-empty" data-action="favorite-project">
                                    <i class="fa-regular fa-star"></i>
                                </button>
                            @endif
                        </form>
                    </div>
                @endif
                
                @if($project->isCoordinator(Auth::user()))
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
                        <button type="submit">Submit</button>
                    </form>
                @endif

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

                            @if($user->id === Auth::user()->id)
                                <button class="member-leave-button button" onclick="showPopup('member-leave-popup');">
                                    <i class="fa-sharp fa-solid fa-arrow-right-from-bracket"></i>
                                </button>
                                <form class="project-form" method="POST"
                                        action="{{ route('member_leave', ['project_id' => $project->id, 'user_id' => $user->id]) }}" 
                                        id="memberLeaveForm">
                                    @method('DELETE')
                                    @csrf
                                    <div id="member-leave-popup" class="confirmation-popup hidden">
                                        <p>Are you sure you want to leave "{{ $project->name }}"?</p>
                                        <button type="button" class="button cancel-button" onclick="hidePopup('member-leave-popup')">No</button>
                                        <button class="button confirm-button">Yes</button>
                                    </div>
                                </form>
                            @elseif($project->isCoordinator(Auth::user()))
                                <button class="remove-member-button button" onclick="showPopup('remove-{{ $user->id}}-popup');">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <form class="project-form" method="POST" action="{{ route('remove_member', ['project_id' => $project->id, 'user_id' => $user->id]) }}" id="removeMemberForm">
                                    @method('DELETE')
                                    @csrf
                                    <div id="remove-{{ $user->id }}-popup" class="confirmation-popup hidden">
                                        <p>Are you sure you want to remove {{ $user->name }} from the project?</p>
                                        <button type="button" class="button cancel-button" onclick="hidePopup('remove-{{ $user->id }}-popup')">No</button>
                                        <button class="button confirm-button">Yes</button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </li>
                @endforeach

            </ul>
            
            @if($project->isCoordinator(Auth::user()))
                <script src="{{ asset('js/search_user.js') }}" defer></script>

                <form class="project-form" id="addMemberForm">
                    Invite new team members<br>
                    <input type="text" name="name" required placeholder="name or username" id="searchInput">
                    <ul id="searchResults"></ul>

                    <div id="invite-popup" class="confirmation-popup hidden">
                        <p>This user already has a pending invitation. Are you sure you want to resend?</p>
                        <button type="button" class="button cancel-button" onclick="hidePopup('invite-popup')">No</button>
                        <button class="button confirm-button">Yes</button>
                    </div>
                </form>
            @endif
        </div>
            
        <div id="tasks">
            <div class="task-card">
                <h3>Create New Task</h3>
                <form class="task-form project-form" method="POST" action="{{ route('create_task', ['project_id' => $project->id]) }}">
                    @method('PUT')
                    @csrf
                    <label for="name">Name:</label><br>
                    <input type="text" id="name" name="name" required placeholder="ex: Create New Navbar">
                    <br>
                    <label for="description">Description:</label><br>
                    <input type="text" id="description" name="description" required placeholder="ex: Create Navbar with four different buttons ...">
                    <br>
                    <label for="delivery_date">Delivery Date:</label><br>
                    <input type="date" id="delivery_date" name="delivery_date">
                    <br>
                    <button type="submit" class="button"><i class="fas fa-plus"></i></button>
                </form>
            </div>
        
            <h3>Your pending tasks:</h3>
        
            <!-- task search -->
            <div class="task-card search_task">
                <form class="task-form project-form" id="search_task_form">
                    <p hidden id="hidden_task_attr">Looks like there are no tasks.</p>
                    <label id="hidden_task_search" for="search_task_input">Search for task:</label>
                    <input type="text" name="task" required placeholder="Search by name or status" id="search_task_input" class="project-form">
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