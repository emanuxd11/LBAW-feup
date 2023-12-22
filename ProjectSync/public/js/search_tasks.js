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
        var tasksTodo = document.getElementById('tasks-todo');
        var tasksDone = document.getElementById('tasks-done');
        var tasksDoing = document.getElementById('tasks-doing');
    
        // Clear existing content
        tasksTodo.innerHTML = '';
        tasksDone.innerHTML = '';
        tasksDoing.innerHTML = '';
    
        data.forEach(function (task) {
            var taskItem = document.createElement('li');
            taskItem.className = 'task';
            taskItem.setAttribute('data-id', task.id);
    
            var taskCard = document.createElement('div');
            taskCard.className = 'task-card-search';
    
            var taskDetails = document.createElement('div');
    
            date = task.delivery_date === null
                ? ''
                : `<p style="font-weight: bold;text-align: start;">Deadline: ${formatDate(task.delivery_date)}</p>`
    
            date = task.status === 'Done'
                ? ""
                : date;
            
            const description = task.description.length < 100
                ? task.description
                : `${task.description.substring(0, 100) + "..."}`
    
            taskDetails.innerHTML = `
                <div class="taskPreview">
                <h3 id="taskPreviewTitle" style="font-weight: bold;">${task.name}</h3>
                <p style="text-align: start;">${description}</p>
                <p style="font-weight: bold;text-align: start;">${date}</p>
                </div>
            `;
            taskCard.appendChild(taskDetails);
            taskCard.addEventListener('click', function () {
                window.location.href = `/api/projects/${project_id}/task/${task.id}`;
            });
    
            taskItem.appendChild(taskCard);
            const list_id = `tasks-${task.status.toLowerCase().replace(/\s/g, "")}`;
            const list = document.getElementById(list_id);
            list.appendChild(taskCard);
        });
    }
});

function formatDate(inputDate) {
    const date = new Date(inputDate);
    
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return date.toLocaleDateString('en-US', options);
  }
