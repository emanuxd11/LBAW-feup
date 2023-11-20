<!-- home.blade.php -->

<!-- Include home.css -->
<link rel="stylesheet" href="{{ url('css/home.css') }}">

@extends('layouts.app')

@section('content')
    <div class="hero-section" id="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1>Organize Your Projects with ProjectSync</h1>
                <p>Effortless project management for teams of all sizes.</p>
                <a class="button" href="{{ route('login') }}">Get Started</a>
            </div>
        </div>
    </div>

    <div class="features-section" id="features-section">
        <div class="container">
            <div class="feature" id="feature-1">
                <i class="fas fa-tasks"></i>
                <h2>Task Management</h2>
                <p>Efficiently manage tasks and track progress with our intuitive task management system.</p>
            </div>

            <div class="feature" id="feature-2">
                <i class="fas fa-users"></i>
                <h2>Team Collaboration</h2>
                <p>Collaborate seamlessly with your team, assign tasks, and keep everyone in sync.</p>
            </div>

            <div class="feature" id="feature-3">
                <i class="fas fa-chart-pie"></i>
                <h2>Visual Analytics</h2>
                <p>Gain insights into your project's performance with powerful visual analytics tools.</p>
            </div>
        </div>
    </div>
@endsection