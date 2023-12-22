<!-- resources/views/partials/sidebar.blade.php -->

<aside class="sidebar" id="side-bar-projects">
    <nav class="nav-menu">
        <div id="favorite-projects">
            @if (count(Auth::user()->favorite_projects()) > 0)
                <ul>
                    <li>
                        @foreach(Auth::user()->favorite_projects() as $project)
                            @if ($project->id == $current_project_id)
                                @include('partials.project_icon_current', ['project' => $project])
                            @else
                                @include('partials.project_icon', ['project' => $project])
                            @endif
                        @endforeach
                    </li>
                    <li class="separator"></li>
                </ul>
            @endif
        </div>

        <div id="active-projects">
            @if (count(Auth::user()->unfavorite_projects()) > 0)
                <ul>
                    <li>
                        @foreach(Auth::user()->unfavorite_projects() as $project)
                            @if ($project->id == $current_project_id)
                                @include('partials.project_icon_current', ['project' => $project])
                            @else
                                @include('partials.project_icon', ['project' => $project])
                            @endif
                        @endforeach
                    </li>
                    <li class="separator"></li>
                </ul>
            @endif
        </div>

        <div id="dashboard">
            <ul>
                <li>
                    @if ($current_project_id == -1)
                        <a href="{{ route('projects') }}" class="current-project-icon">
                            <span>
                                <i class="fa-solid fa-plus"></i>
                            </span>
                        </a>
                    @else
                        <a href="{{ route('projects') }}" class="side-bar-icon">
                            <span>
                                <i class="fa-solid fa-plus"></i>
                            </span>
                        </a>
                    @endif
                </li>
            </ul>
        </div>
    </nav>
</aside>
