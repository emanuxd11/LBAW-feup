document.addEventListener('DOMContentLoaded', async () => {
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');
    const projectMemberList = document.getElementById('project-member-list');
    const noMembersElement = document.getElementById('no-members');

    searchInput.addEventListener('input', async () => {
        const searchTerm = searchInput.value.trim();

        if (searchTerm.length === 0) {
            searchResults.innerHTML = '';
            return;
        }

        const projectId = window.location.pathname.split('/').pop();

        try {
            const response = await fetch(`/projects/${projectId}/search_user?term=${encodeURIComponent(searchTerm)}`);
            const data = await response.json();
            displaySearchResults(data, projectId);
        } catch (error) {
            console.error('Error fetching search results:', error);
        }
    });

    function displaySearchResults(results, projectId) {
        searchResults.innerHTML = '';

        results.forEach(user => {
            const resultItem = document.createElement('div');
            resultItem.textContent = user.name;

            resultItem.addEventListener('click', async () => {
                const updatedResults = results.filter(u => u.id !== user.id);
                displaySearchResults(updatedResults, projectId);
                await addUserToProject(user, projectId);
            });

            searchResults.appendChild(resultItem);
        });

        if (results.length == 0) {
            const resultItem = document.createElement('div');
            resultItem.textContent = "There are no available users that match the search.";
            searchResults.appendChild(resultItem);
        } else {
            // Show the no-members element
            noMembersElement.style.display = 'block';
        }
    }

    async function addUserToProject(user, projectId) {
        try {
            const response = await fetch(`/projects/${projectId}/add_user`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ userId: user.id })
            });
    
            const data = await response.json();
    
            if (data.success) {
                const listItem = document.createElement('li');
                listItem.setAttribute('data-id', user.id);
                listItem.innerHTML = `
                    <div class="user-list-card">
                        <a href="/profile/${user.username}">
                            <div class="user-list-content">
                                <span id="user-name-project">${user.name} (${user.username})</span>
                            </div>
                        </a>
    
                        <button class="remove-member-button button" onclick="showPopup('remove-${user.id}-popup');">
                            <i class="fas fa-trash"></i>
                        </button>
    
                        <form class="project-form" method="POST" action="/projects/${projectId}/remove_member/${user.id}" id="removeMemberForm-${user.id}">
                            <input type="hidden" name="_method" value="DELETE">
                            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">
    
                            <div id="remove-${user.id}-popup" class="confirmation-popup hidden">
                                <p>Are you sure you want to remove ${user.name} from the project?</p>
                                <button type="button" class="button cancel-button" onclick="hidePopup('remove-${user.id}-popup')">No</button>
                                <button class="button confirm-button" onclick="document.getElementById('removeMemberForm-${user.id}').submit();">Yes</button>
                            </div>
                        </form>
                    </div>
                `;
    
                projectMemberList.appendChild(listItem);
    
                // Hide the no-members element
                noMembersElement.style.display = 'none';
            } else {
                console.error('Error adding user to the project:', data.error);
            }
        } catch (error) {
            console.error('Error adding user to the project:', error);
        }
    }
});
