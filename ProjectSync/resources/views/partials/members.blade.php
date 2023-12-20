<!-- resources/views/partials/members.blade.php -->

<aside class="members-sidebar">
    <nav class="nav-menu">
        <h6>Project Coordinator</h6>
        <ul id="project-coordinator">
            <li>
                @if($project->getCoordinator() != null)
                    <a href="{{ route('profilePage', ['username' => $project->getCoordinator()->username]) }}" id="user-name-project">
                        <p><i class="fas fa-user"></i> {{ $project->getCoordinator()->name }}</p>
                    </a>
                @endif
            </li>
        </ul>
        <h6>Project Member - {{ count($project->members) - 1 }}</h6>
        <ul id="project-member-list">
            {{-- can't use forelse here since there is always one element (coordinator) --}}
            @if(count($project->members) <= 1)
                {{-- <p id="no-members">Looks like nobody has been added to the project yet.</p> --}}
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

                        {{-- @if($user->id === Auth::user()->id)
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
                        @endif --}}
                    </div>
                </li>
            @endforeach
        </ul>

        @if($project->isCoordinator(Auth::user()))
            <h6>Pending Invitations - {{ count($project->pending_users()) }}</h6>
            <ul id="invited-users-list">
                @foreach($project->pending_users() as $user)
                    <li data-id="{{ $user->id }}" class="pending-user-right-clickable" onclick="showContextMenu({{ $user->id }})">
                        <div class="user-list-card pending-user">
                            <div class="pending-user-list-content" oncontextmenu="showContextMenu(event, {{ $user->id }})">
                                <span id="user-name-project">{{ $user->name . ' (' . $user->username . ')' }}</span>
                            </div>
                            <div class="context-menu" id="contextMenu-{{ $user->id }}">
                                <div class="context-menu-item" id="contextMenuItem-{{ $user->id }}">
                                    <a id="context-menu-profile-{{ $user->id }}" href="{{ route('profilePage', ['username' => $user->username]) }}">Profile</a>
                                </div>
                                <div class="context-menu-item" id="contextMenuItem-{{ $user->id }}">
                                    <button class="revoke-invitation-button text-button" onclick="showPopup('revoke-{{ $user->id }}-popup');">
                                        {{-- <i class="fa-solid fa-xmark"></i> --}}
                                        Cancel Invitation
                                    </button>
                                    <form class="project-form" method="POST" action="{{ route('project.revoke.invitations', ['project_id' => $project->id, 'user_id' => $user->id]) }}" id="revokeInvitationForm">
                                        @method('DELETE')
                                        @csrf
                                        <div id="revoke-{{ $user->id }}-popup" class="confirmation-popup hidden">
                                            <p>Are you sure you want to cancel {{ $user->name }}'s invitation?</p>
                                            <button type="button" class="button cancel-button" onclick="hidePopup('revoke-{{ $user->id }}-popup')">No</button>
                                            <button class="button confirm-button">Yes</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="context-menu-item" id="contextMenuItem-{{ $user->id }}">
                                    should hide menu
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif

        <script>
            function showContextMenu(userId) {
                const contextMenu = document.getElementById(`contextMenu-` + userId);
                contextMenu.style.left = `${event.pageX}px`;
                contextMenu.style.top = `${event.pageY}px`;
                contextMenu.style.display = 'block';
                console.log("Opened new context menu")

                document.getElementById(`contextMenuItem-${userId}`).addEventListener('click', function() {
                    console.log(`Clicked context menu item for user ID: ${userId}`);
                    contextMenu.style.display = 'none';
                });

                document.addEventListener('keydown', function(event) {
                    if (event.key === 'Escape') {
                        console.log('Hiding context menu on escape')
                        contextMenu.style.display = 'none';
                    }
                });
            }
        </script>

        <style>
            .context-menu {
                display: none;
                position: fixed;
                background-color: #5f5f5f;
                border: 1px solid #ddd;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                padding: 5px;
                z-index: 1000;
            }

            .context-menu-item {
                cursor: pointer;
                padding: 5px;
                color: #fbfbfb;
            }

            .text-button {
                background: none;
                border: none;
                padding: 0;
                margin: 0;
                font: inherit;
                cursor: pointer;
                color: #3498db; /* Change the text color as needed */
                transition: color 0.3s ease; /* Add a smooth transition effect for the color change */
            }

            .text-button:hover {
                color: #e74c3c; /* Change the hover color as needed */
            }
        </style>
    </nav>
</aside>
