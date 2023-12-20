<!-- Include header.css -->
<link rel="stylesheet" href="{{ url('css/header.css') }}">

<header class="modern-header">
    <h1 id="projectSyncLogo"><a href="{{ url('/') }}">ProjectSync</a></h1>
    <nav>
        <div class="buttons-container">
            <a class="button" href="{{ route('faq') }}">FAQ</a>
            <a class="button" href="{{ route('aboutUs') }}">About Us</a>
            <a class="button" href="{{ route('contacts') }}">Contacts</a>
            @if (Auth::check())
            <a class="button" href="{{ route('projects') }}">PROJECTS</a>
                <a class="profile-button" id="profile-dropdown-toggle">            
                    @if(Auth::user()->profile_pic !== null && Auth::user()->profile_pic !== '')
                        <img src="{{Auth::user()->profile_pic}}" alt="Profile Picture">
                    @else
                        <img src="/images/avatars/default-profile-pic.jpg" alt="Default Profile Picture">
                    @endif
                </a>

                <div class="profile-dropdown" id="profile-dropdown">
                    <a href="{{ route('profilePage', ['username' => Auth::user()->username]) }}">View Profile</a>
                    <a href="{{ route('notification.show', ['username' => Auth::user()->username]) }}">Notifications</a>
                    <a href="{{ url('/logout') }}">Logout</a>
                </div>  
            @else
                <a class="button" href="{{ route('login') }}">Login</a>
                <a class="button" href="{{ route('register') }}">Register</a>
            @endif  
        </div>

    </nav>

    <script src="{{ asset('js/header.js') }}" defer></script>

</header>
