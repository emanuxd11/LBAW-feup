<!-- Include header.css -->
<link rel="stylesheet" href="{{ url('css/header.css') }}">

<header class="modern-header">
    <h1 id="projectSyncLogo"><a href="{{ url('/') }}">ProjectSync</a></h1>
    <nav>
        <div class="buttons-container">
            <a class="button" href="{{ url('/faq') }}">FAQ</a>
            <a class="button" href="{{ route('aboutUs') }}">About Us</a>

            @if (Auth::check())
                <a class="button" href="{{ url('/logout') }}">Logout</a>
                <a class="profile-button" href="{{ route('profilePage', ['username' => Auth::user()->username]) }}">
                    @if(Auth::user()->profile_pic !== null && Auth::user()->profile_pic !== '')
                        <img src="{{Auth::user()->profile_pic}}" alt="Profile Picture">
                    @else
                        <img src="/images/avatars/default-profile-pic.jpg" alt="Default Profile Picture">
                    @endif
                </a>
            @else
                <a class="button" href="{{ route('login') }}">Login</a>
                <a class="button" href="{{ route('register') }}">Register</a>
            @endif
        </div>
    </nav>
</header>
