<!-- resources/views/partials/members.blade.php -->

<aside class="members-sidebar">
    <nav class="nav-menu">
        <h6>Project Coordinator:</h6>
        <ul id="project-coordinator">
            <li>
                @if($project->getCoordinator() != null)
                    <a href="{{ route('profilePage', ['username' => $project->getCoordinator()->username]) }}" >
                        <p><i class="fas fa-user"></i> {{ $project->getCoordinator()->name }}</p>
                    </a>
                @endif
            </li>
        </ul>
        <h6>Project Member: (count)</h6>
        <ul id="project-members">
            <li>
                Some Guy
            </li>
            <li>
                Some Guy
            </li>
            <li>
                Some Guy
            </li>
            <li>
                Some Guy
            </li>
            <li>
                Some Guy
            </li>
        </ul>
    </nav>
</aside>
