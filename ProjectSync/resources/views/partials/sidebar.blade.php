<!-- resources/views/partials/sidebar.blade.php -->

<aside class="sidebar">
    <nav class="nav-menu">
        <div id="dashboard">
            <ul>
                <li>
                    <a href="{{ route('projects') }}" class="side-bar-icon">
                        <span>
                            <i class="fa-solid fa-plus"></i>
                        </span>
                    </a>
                </li>
                <li class="separator"></li>
            </ul>
        </div>
        
        {{-- <h2>Your favorite projects:</h2>     --}}
        <div id="favorite-projects">
            @if (count(Auth::user()->favorite_projects()) > 0)
                <ul>
                    <li>
                        @each('partials.project', Auth::user()->favorite_projects(), 'project')
                    </li>
                    <li class="separator"></li>
                </ul>
            @else
                {{-- <p>Looks like you haven't marked any projects as favorite yet. You can do this by clicking the star icon on a project's page!</p> --}}
            @endif
        </div>

        {{-- <h2>Your active projects:</h2>     --}}
        <div id="active-projects">
            {{-- update this to only show the projects that aren't favorited --}}
            @if (count(Auth::user()->projects) > 0)
                <ul>
                    <li>
                        @each('partials.project', Auth::user()->projects, 'project')
                    </li>
                </ul>
            @else
                <p>Looks like you aren't related to any currently active projects. You can start by creating a new one!</p>
            @endif
        </div>
    </nav>
</aside>
