document.addEventListener('DOMContentLoaded', function () {
    var searchInput = document.getElementById('search_task_input');
    var searchResults = document.getElementById('search_task_results');

    searchInput.addEventListener('input', function () {
        var searchTerm = searchInput.value.trim();

        const projectId = window.location.pathname.split('/').pop();

        fetch(`/projects/${projectId}/search_task?term=${encodeURIComponent(searchTerm)}`)
            .then(response => response.json())
            .then(data => {
                displaySearchResults(data,projectId);
            })
            .catch(error => {
                console.error('Error fetching search results:', error);
            });
    });

    function displaySearchResults(data,project_id) {
        searchResults.innerHTML = '';
        data.forEach(function (task) {
            var taskItem = document.createElement('li');
            taskItem.className = 'task';
            taskItem.setAttribute('data-id', task.id);

            var taskLink = document.createElement('a');
            taskLink.href = `/api/projects/${project_id}/task/${task.id}`;
            taskLink.innerHTML = `<h3>${task.name}</h3>`;

            taskLink.addEventListener('click', function(event) {
                event.preventDefault();
                window.location.href = taskLink.href;
            });

            taskItem.appendChild(taskLink);

            // Set the HTML content for the task element
            taskItem.innerHTML += `
                <p>Description: ${task.description}</p>
                <p>Task started on ${task.start_date}</p>
                <p>Needs to be done by ${task.delivery_date}</p>
                <p>Status: ${task.status}</p>
            `;
            
            searchResults.appendChild(taskItem);
        });
    }
});