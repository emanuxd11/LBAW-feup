@extends('layouts.app')

@section('title', 'Project Changes')

@section('content')

    <h3>Project Changes - {{ $project->name }}</h3>

    <ul>
        @foreach ($changes as $change)
            <li>
                <strong>{{ $change->date }}</strong> - {!! $change->text !!} by
                @php
                    $user = \App\Models\User::find($change->user_id);
                    echo $user ? $user->username : 'Unknown User';
                @endphp

            </li>
        @endforeach
    </ul>

@endsection