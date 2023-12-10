<!-- contacts.blade.php -->

@extends('layouts.app')

@section('content')


<div class="container mt-5">
    <h2 id="about-heading" class="mb-4">Contacts</h2>
    <div id="contacts" class="contacts-container">
        <div class="contacts-card" onclick="toggleContacts(this)">
            <h2 class="contacts-page">Email <i class="fas fa-envelope"></i></h2>
            <div class="contacts-body">
                <p>Feel free to send us an email:</p>
                <ul class="contacts-list">
                    <li class="animated-icon">info@projectsync.com</li>
                </ul>
            </div>
        </div>

        <div class="contacts-card" onclick="toggleContacts(this)">
            <h2 class="contacts-page">Phone <i class="fas fa-phone"></i></h2>
            <div class="contacts-body">
                <p>Our phone number:</p>
                <ul class="contacts-list">
                    <li class="animated-icon"> +123 456 7890</li>
                    
            </div>
        </div>

        <div class="contacts-card" onclick="toggleContacts(this)">
            <h2 class="contacts-page">Address <i class="fas fa-address-card"></i>
            </h2>
            <div class="contacts-body">
                <p>Come visit us! Our current address:</p>
                <ul class="contacts-list">
                    <li class="animated-icon"> 123 Main Street</li>
                    <li class="animated-icon"> Anytown, CA 12345</li>
                </ul>
            </div>
        </div>
        <br>
        <div class="contacts-summary">
            <p class="contacts-summary-text" style="font-size: 24px; color: #006aa7; text-align: center;">
                Connect with us and discover how ProjectSync can help you achieve your project goals. Let's discuss your unique needs and find the perfect solution for your organization.
              </p>
        </div>
        <br>
        <div class="social-links" data-animation="fade-in" data-animation-delay="0.8s">
            <ul class="list-inline">
                <li><a href="#">
                    <i class="fab fa-twitter fa-3x"></i>
                </a></li>
                <li><a href="#">
                    <i class="fab fa-facebook fa-3x"></i>
                </a></li>
                <li><a href="#">
                    <i class="fab fa-linkedin fa-3x"></i>
                </a></li>
                <li><a href="#">
                    <i class="fab fa-youtube fa-3x"></i>
                </a></li>
            </ul>
        </div>
    </div>
</div>

@endsection