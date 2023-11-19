<!-- Include header.css -->
<link rel="stylesheet" href="{{ url('css/header.css') }}">

<header class="modern-header">
    <h1 id="projectSyncLogo"><a href="{{ url('/') }}">ProjectSync</a></h1>
    <nav>
        @if (Auth::check())
            <a class="button" href="{{ url('/logout') }}">Logout</a>
            <a href="{{ route('profilePage', ['username' => Auth::user()->username]) }}">
                <span>{{ Auth::user()->name }}</span>
            </a>
        @else
            <a class="button" href="{{ route('login') }}">Login</a>
            <a class="button" href="{{ route('register') }}">Register</a>
        @endif
        <a class="button" href="{{ url('/faq') }}">FAQ</a>
        <a class="button" href="{{ route('aboutUs') }}">About Us</a>
    </nav>
</header>
