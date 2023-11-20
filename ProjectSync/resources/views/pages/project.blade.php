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

                <!-- Add this part inside the <script> tag in your project.blade.php -->
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                    var form = document.getElementById('addMemberForm');
                    var searchInput = document.getElementById('searchInput');
                    var searchResults = document.getElementById('searchResults');
                    var projectMemberList = document.getElementById('project-member-list');

                    searchInput.addEventListener('input', function () {
                        var searchTerm = searchInput.value.trim();

                        if (searchTerm.length === 0) {
                            searchResults.innerHTML = '';
                            return;
                        }

                        const projectId = window.location.pathname.split('/').pop();

                        fetch(`/projects/${projectId}/search_user?term=${encodeURIComponent(searchTerm)}`)
                            .then(response => response.json())
                            .then(data => {
                                displaySearchResults(data);
                            })
                            .catch(error => {
                                console.error('Error fetching search results:', error);
                            });
                    });

                    function displaySearchResults(results) {
                        searchResults.innerHTML = '';

                        results.forEach(function (user) {
                            var resultItem = document.createElement('div');
                            resultItem.textContent = user.name;

                            resultItem.addEventListener('click', function () {
                                searchInput.value = user.name;
                                searchResults.innerHTML = '';

                                addUserToProject(user.id);
                            });

                            searchResults.appendChild(resultItem);
                        });
                    }

                    function addUserToProject(userId) {
                        fetch(`/projects/${projectId}/add_user`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ userId: userId })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // If addition was successful, append the user to the project-member-list
                                var listItem = document.createElement('li');
                                listItem.textContent = data.userName; // Adjust based on your user model
                                projectMemberList.appendChild(listItem);
                            } else {
                                console.error('Error adding user to the project:', data.error);
                            }
                        })
                        .catch(error => {
                            console.error('Error adding user to the project:', error);
                        });
                    }
                });
                </script>
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
            <ul id="task-list">
                @each('partials.task', $project->tasks, 'task')
            </ul>

        </div>
    </section>
@endsection