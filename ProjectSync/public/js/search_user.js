document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');
    const projectMemberList = document.getElementById('project-member-list');

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

            resultItem.addEventListener('click', () => {
                searchInput.value = user.name;
                searchResults.innerHTML = '';

                addUserToProject(user, projectId);
            });

            searchResults.appendChild(resultItem);
        });
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
                listItem.classList.add('project-member');
                const profilePageUrl = `/profile/${user.username}`;
                listItem.innerHTML = `
                    <a href="${profilePageUrl}">
                        <span>${user.name} (${user.username})</span>
                    </a>
                    
                    <button class="remove-member-button button" onclick="showConfirmationPopup(event);">
                        <i class="fas fa-trash"></i>
                    </button>

                    <form class="project-form" method="POST" action="/projects/${projectId}/remove_member/${user.id}" id="removeMemberForm">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">

                        <div class="confirmation-popup hidden">
                            <p>Are you sure you want to remove ${user.name} from the project?</p>
                            <button class="button" onclick="cancelRemoval(); return false;">No</button>
                            <button class="button" onclick="document.getElementById('removeMemberForm').submit();">Yes</button>
                        </div>
                    </form>
                `;

                projectMemberList.appendChild(listItem);
            } else {
                console.error('Error adding user to the project:', data.error);
            }
        } catch (error) {
            console.error('Error adding user to the project:', error);
        }
    }
});

