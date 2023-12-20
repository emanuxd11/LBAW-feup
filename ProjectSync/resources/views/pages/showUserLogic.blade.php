@extends('layouts.app')

@section('content')
    <link href="{{ asset('css/adminLogicUser.css') }}" rel="stylesheet">
    <div class="body">
        <div class="header">
            <p class="login-section-text" style="color: #006aa7;">
                Change coordinators in order to delete/block this user
            </p>
        </div>
        <div class="assign-container">
            @foreach($coordinatedProjects as $project)
                <div class="card-body">
                    <h2>{{$project->name}}</h2>
                    <p>Select a new coordinator</p>
                    <form class="project-form" method="POST" action="{{ route('admin.assign.new.coordinator', ['project_id' => $project->id]) }}" id="newProjectCoordinatorForm">
                        @method('POST')
                        @csrf
                        <select id="username" name="new_id" class="project-form-input">
                            <option value="" selected disabled>--------</option>
                            @foreach($project->members as $member)
                                @if ($member->id != $user->id)
                                    <option value={{ $member->id }}>{{ $member->name . ' (' . $member->username . ')' }}</option>
                                @endif
                            @endforeach
                        </select>
                        <input type="hidden" name="old_id" value="{{$project->getCoordinator()->id}}">
                        <input type="hidden" name="projectId" value="{{$project->id}}">
                        <input type="hidden" name="userId" value="{{$user->id}}">
                        <input type="hidden" name="action" value="{{$action}}">
                        <button type="submit" class="project-submit-button">Submit</button>
                    </form>
                </div>
            @endforeach
           
        </div>
    </div>
@endsection
