<!-- faq.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div id="FAQ" class="faq-container">
            <h2 id="faq-heading">FAQ</h2>
            <div class="faq-card" onclick="toggleFAQ(this)">
                <h2 class="faq-page">What is ProjectSync?</h2>
                <div class="faq-body">
                    <p>ProjectSync is a project management tool that helps you organize and collaborate on projects efficiently.</p>
                </div>
            </div>

            <div class="faq-card" onclick="toggleFAQ(this)">
                <h2 class="faq-page">How do I create a new project?</h2>
                <div class="faq-body">
                    <p>To create a new project, go to your dashboard and click on the "New Project" button. Fill in the required information and click "Create."</p>
                </div>
            </div>

            <div class="faq-card" onclick="toggleFAQ(this)">
                <h2 class="faq-page">How can I invite team members to my project?</h2>
                <div class="faq-body">
                    <p>You can invite team members by navigating to the project settings and selecting "Invite Members." Enter their email addresses and send invitations.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
