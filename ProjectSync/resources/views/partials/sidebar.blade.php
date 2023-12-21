<!-- resources/views/partials/sidebar.blade.php -->

<aside class="sidebar">
    <nav class="nav-menu">
        {{-- <h2>Your favorite projects:</h2>     --}}
        <div id="favorite-projects">
            @if (count(Auth::user()->favorite_projects()) > 0)
                <ul>
                    <li>
                        @each('partials.project_icon', Auth::user()->favorite_projects(), 'project')
                    </li>
                    <li class="separator"></li>
                </ul>
            @endif
        </div>

        {{-- <h2>Your active projects:</h2>     --}}
        <div id="active-projects">
            {{-- update this to only show the projects that aren't favorited --}}
            @if (count(Auth::user()->unfavorite_projects()) > 0)
                <ul>
                    <li>
                        @each('partials.project_icon', Auth::user()->unfavorite_projects(), 'project')
                    </li>
                    <li class="separator"></li>
                </ul>
            @endif
        </div>

        <div id="dashboard">
            <ul>
                <li>
                    <a href="{{ route('projects') }}" class="side-bar-icon">
                        <span>
                            <i class="fa-solid fa-plus"></i>
                        </span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</aside>
