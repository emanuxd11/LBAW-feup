<!-- resources/views/partials/members.blade.php -->

@if($project->isCoordinator(Auth::user()))
    <div class="hidden modal" id="invite-users-container">
        <script src="{{ asset('js/invitations.js') }}" defer></script>

        <form class="project-form" id="addMemberForm">
                <a onclick="hideInviteUsers(event)" id="close-user-invite">
                    <i class="fa-solid fa-xmark"></i>
                </a>
                <p id="invite-members-p">Invite new team members</p>
            <input type="text" name="name" required autocomplete="off" placeholder="name or username" id="searchInput">
            
            <ul id="searchResults" class="scrollable"></ul>

            <div id="invite-popup" class="confirmation-popup hidden">
                <p>This user already has a pending invitation. Are you sure you want to resend?</p>
                <button type="button" class="button cancel-button" onclick="hidePopup('invite-popup')">No</button>
                <button class="button confirm-button">Yes</button>
            </div>
        </form>
    </div>
@endif 

<aside class="members-sidebar scrollable">
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
        <h6 id="project-member-header">
            <p>Project Member - {{ count($project->members) - 1 }} </p>
            @if($project->isCoordinator(Auth::user()))
                <div id="invite-members-activate" onclick="showInviteUsers(event)">
                    <i class="fa-solid fa-plus"></i>
                </div>
            @endif
        </h6>
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
                                <div class="context-menu-item" id="contextMenuItemProfile-{{ $user->id }}">
                                    <a class="text-button" href="{{ route('profilePage', ['username' => $user->username]) }}">
                                        Profile
                                    </a>
                                </div>
                                <div class="context-menu-item" id="contextMenuItemRevoke-{{ $user->id }}">
                                    <a class="critical-button text-button" onclick="showPopup('revoke-{{ $user->id }}-popup', event);">
                                        Cancel Invitation
                                    </a>
                                    <form class="project-form hidden-form" method="POST" action="{{ route('project.revoke.invitations', ['project_id' => $project->id, 'user_id' => $user->id]) }}">
                                        @method('DELETE')
                                        @csrf
                                        <div id="revoke-{{ $user->id }}-popup" class="confirmation-popup hidden">
                                            <p>Are you sure you want to cancel {{ $user->name }}'s invitation?</p>
                                            <button type="button" class="button cancel-button" onclick="hidePopup('revoke-{{ $user->id }}-popup')">No</button>
                                            <button class="button confirm-button">Yes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </nav>
</aside>
