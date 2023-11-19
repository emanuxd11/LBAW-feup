<!-- Include home.css -->
<link rel="stylesheet" href="{{ url('css/home.css') }}">

@extends('layouts.app')

@section('content')
    <div class="container modern-home">
        <h1>Welcome to the Homepage!</h1>
        <p>Discover the power of ProjectSync.</p>
        <a class="button" href="#">Get Started</a>
    </div>
@endsection
