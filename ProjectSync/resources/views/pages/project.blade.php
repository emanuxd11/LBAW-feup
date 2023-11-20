@extends('layouts.app')

@section('title', $project->name)

@section('content')
    <section id="project">
        <h2>{{ $project->name }}</h2>
        <p>Coordinator: <a href="{{ route('profilePage', ['username' => $project->getCoordinator()->username]) }}"><span>{{ $project->getCoordinator()->name }}</span></a></p>

        <div id="project-members">
            <h3>Project Members</h3>
            <ul id="project-member-list">
                @each('partials.member', $project->members, 'user')
            </ul>
            
            @if($project->isCoordinator(Auth::user()))
                <form class="new_member_form" id="addMemberForm">
                    Add new team members:
                    <input type="text" name="name" required placeholder="name or username" id="searchInput">
                    <ul id="searchResults"></ul>
                </form>

                <script src="{{ asset('js/search_user.js') }}" defer></script>
            @endif
        </div>
            
        <div id="tasks">
            <h3>Tasks</h3>
            <form class="new_task_form" method="POST" action="{{ route('create_task', ['project_id' => $project->id]) }}">
                @method('PUT')
                @csrf
                Name: <input type="text" name="name"required>
                Description: <input type="text" name="description" required>
                Delivery Date: <input type="date" name="delivery_date">
                <button type="submit" class="button">+</button>
            </form>

            <!-- show tasks -->
            <div class="search_task">
                <form class="search_task_form" id="search_task_form">
                    Search for task:
                    <input type="text" name="task" required placeholder="name or status" id="search_task_input">
                    <ul id="search_task_results">
                        <script>
                            window.csrf_token = "{{ csrf_token() }}";
                        </script>
                    </ul>
                </form>

                <script src="{{ asset('js/search_tasks.js') }}" defer></script>
            </div>
            <!--<ul id="task-list">
                @each('partials.task', $project->tasks, 'task')
            </ul>-->

        </div>
    </section>
@endsection