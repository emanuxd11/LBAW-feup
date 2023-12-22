document.addEventListener('DOMContentLoaded', function () {
    var searchInput = document.getElementById('search_task_input');

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
        document.getElementById('tasks-todo').innerHTML = '';
        document.getElementById('tasks-done').innerHTML = '';
        document.getElementById('tasks-doing').innerHTML = '';

        data.forEach(function (task) {
            var taskItem = document.createElement('li');
            taskItem.className = 'task';
            taskItem.setAttribute('data-id', task.id);
    
            var taskCard = document.createElement('div');
            taskCard.className = 'task-card-search';
    
            // var taskLink = document.createElement('a');
            // taskLink.href = `/api/projects/${project_id}/task/${task.id}`;
            // taskLink.innerHTML = `<h3 id="taskPreviewTitle">${task.name}</h3>`;
            // taskCard.appendChild(taskLink);
    
            var taskDetails = document.createElement('div');

            date = task.delivery_date === null
            ? "No delivery date available"
            : "Needs to be done by " + task.delivery_date;

            date = task.status === 'Done'
            ? ""
            : date;

            taskDetails.innerHTML = `
                <div class="taskPreview"">
                <h3 id="taskPreviewTitle" style="font-weight: bold;">${task.name}</h3>
                <p style="text-align: start;">${task.description.substring(0,100) + "...."}</p>
                <p style="font-weight: bold;text-align: start;">${date}</p>
                </div>
            `;
            taskCard.appendChild(taskDetails);
            taskCard.addEventListener('click', function () {
                window.location.href = `/api/projects/${project_id}/task/${task.id}`;
            });
    
            // Update the event listener to attach it to taskItem
            taskItem.addEventListener('click', function () {
                window.location.href = taskLink.href;
            });
    
            taskItem.appendChild(taskCard);
            // searchResults.appendChild(taskItem);
            const list_id = `tasks-${task.status.toLowerCase().replace(/\s/g, "")}`;
            const list = document.getElementById(list_id);
            list.appendChild(taskCard)
        });
    }
});
