@extends('layouts.app')

@section('title', 'Project Changes')

@section('content')

<link rel="stylesheet" href="{{ asset('css/project_changes.css') }}">

    <h3>Project Changes - {{ $project->name }}</h3>

    <ul>
        @foreach ($changes as $change)
            <li class="changes">
                           
                <strong>{{ $change->date }}</strong> - {!! $change->text !!}
                
            </li>
        @endforeach
    </ul>

@endsection
