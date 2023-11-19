<!-- about.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h2 id="about-heading" class="mb-4">About Us</h2>
        <div id="about" class="about-container">
            <div class="about-card" onclick="toggleAbout(this)">
                <h2 class="about-page">Who We Are</h2>
                <div class="about-body">
                    <p>We are ProjectSync, a team passionate about providing effective project management solutions. Our primary objective is to develop an efficient information system with an intuitive web interface tailored to meet intricate project requirements.</p>
                    <ul class="about-list">
                        <li class="animated-icon"><i class="fas fa-check-circle"></i> Simplifying project collaboration for enhanced productivity.</li>
                        <li class="animated-icon"><i class="fas fa-check-circle"></i> Delivering a user-friendly and reliable project management platform.</li>
                        <li class="animated-icon"><i class="fas fa-check-circle"></i> Seamlessly overseeing multiple projects with ease.</li>
                    </ul>
                </div>
            </div>

            <div class="about-card" onclick="toggleAbout(this)">
                <h2 class="about-page">Our Mission</h2>
                <div class="about-body">
                    <p>Our mission at ProjectSync is to simplify project collaboration and organization for teams of all sizes. We aim to provide users with a robust yet user-friendly and reliable project management platform. Within the application, organizations can seamlessly oversee multiple projects simultaneously.</p>
                    <ul class="about-list">
                        <li class="animated-icon"><i class="fas fa-bullseye"></i> Simplify project collaboration and organization for teams of all sizes.</li>
                        <li class="animated-icon"><i class="fas fa-bullseye"></i> Provide a robust, user-friendly, and reliable project management platform.</li>
                        <li class="animated-icon"><i class="fas fa-bullseye"></i> Facilitate seamless oversight of multiple projects.</li>
                    </ul>
                </div>
            </div>

            <!-- Centered and larger font text paragraph -->
            <div class="about-summary">
                <p class="about-summary-text" style="font-size: 24px; color: #006aa7; text-align: center;">ProjectSync is dedicated to providing a cohesive and efficient project management solution. Our commitment lies in enhancing productivity, fostering collaboration, and simplifying project management processes for organizations of all sizes.</p>
            </div>
        </div>
    </div>
@endsection
