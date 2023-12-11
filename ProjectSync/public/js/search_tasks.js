document.addEventListener('DOMContentLoaded', function () {
    var searchInput = document.getElementById('search_task_input');
    var searchResults = document.getElementById('search_task_results');

    const projectId = window.location.pathname.split('/').pop();
    fetchTasks(projectId, '');

    searchInput.addEventListener('input', function () {
        var searchTerm = searchInput.value.trim();
        fetchTasks(projectId, searchTerm);
    });

    function fetchTasks(projectId, searchTerm) {
        const url = searchTerm
            ? `/projects/${projectId}/search_task?term=${encodeURIComponent(searchTerm)}`
            : `/projects/${projectId}/search_task`;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                displaySearchResults(data, projectId);
            })
            .catch(error => {
                console.error('Error fetching search results:', error);
            });
    }

    function displaySearchResults(data, project_id) {
        searchResults.innerHTML = '';
        data.forEach(function (task) {
            var taskItem = document.createElement('li');
            taskItem.className = 'task';
            taskItem.setAttribute('data-id', task.id);
    
            var taskCard = document.createElement('div');
            taskCard.className = 'task-card-search';
    
            var taskLink = document.createElement('a');
            taskLink.href = `/api/projects/${project_id}/task/${task.id}`;
            taskLink.innerHTML = `<h3>${task.name}</h3>`;
    
            taskCard.appendChild(taskLink);
    
            var taskDetails = document.createElement('div');
            taskDetails.innerHTML = `
                <p>Description: ${task.description}</p>
                <p>Task started on ${task.start_date}</p>
                <p>Needs to be done by ${task.delivery_date ?? "No delivery date available"}</p>
                <p>Status: ${task.status}</p>
            `;
    
            taskCard.appendChild(taskDetails);
    
            // Update the event listener to attach it to taskItem
            taskItem.addEventListener('click', function () {
                window.location.href = taskLink.href;
            });
    
            taskItem.appendChild(taskCard);
            searchResults.appendChild(taskItem);
        });
    }
});
