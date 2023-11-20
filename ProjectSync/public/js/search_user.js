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
                displaySearchResults(data, projectId);
            })
            .catch(error => {
                console.error('Error fetching search results:', error);
            });
    });

    function displaySearchResults(results, projectId) {
        searchResults.innerHTML = '';

        results.forEach(function (user) {
            var resultItem = document.createElement('div');
            resultItem.textContent = user.name;

            resultItem.addEventListener('click', function () {
                searchInput.value = user.name;
                searchResults.innerHTML = '';

                addUserToProject(user.id, projectId);
            });

            searchResults.appendChild(resultItem);
        });
    }

    function addUserToProject(userId, projectId) {
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