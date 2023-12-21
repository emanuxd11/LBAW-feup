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
            const resultItem = document.createElement('li');
            resultItem.textContent = user.name + (user.hasPendingInvitation ? " (pending)" : "");
            resultItem.addEventListener('click', async () => {
                const updatedResults = results.filter(u => u.id !== user.id);
                displaySearchResults(updatedResults, projectId);
                await inviteUserToProject(user, projectId);
            });

            searchResults.appendChild(resultItem);
        });

        if (results.length == 0) {
            const resultItem = document.createElement('div');
            resultItem.textContent = "There are no available users that match the search.";
            searchResults.appendChild(resultItem);
        } else if(noMembersElement) {
            // Show the no-members element
            noMembersElement.style.display = 'block';
        }
    }

    async function showCustomPopup(user, popup_html) {
        // Add modal overlay
        const modalOverlay = document.createElement('div');
        modalOverlay.classList.add('modal-overlay');
        document.body.appendChild(modalOverlay);
    
        return new Promise((resolve, reject) => {
            const popUp = document.createElement('div');
            popUp.setAttribute('id', `invite-popup-${user.id}`);
            popUp.setAttribute('class', 'orphan-popup');
            popUp.style.display = 'block';
            popUp.innerHTML = popup_html;
    
            document.body.appendChild(popUp);
    
            document.getElementById('cancelButton').addEventListener('click', () => {
                hidePopup(`invite-popup-${user.id}`);
                removeModalOverlay();
                reject(false);
            });
    
            document.getElementById('confirmButton').addEventListener('click', () => {
                hidePopup(`invite-popup-${user.id}`);
                removeModalOverlay();
                resolve(true);
            });
        });
    
        function removeModalOverlay() {
            const modalOverlays = document.querySelectorAll('.modal-overlay');
            modalOverlays.forEach(modalOverlay => {
                document.removeChild(modalOverlay);
            });
        }
    }
    
    async function inviteUserToProject(user, projectId) {
        if (user.hasPendingInvitation) {
            try {
                const popup_html = `
                    <p>${user.name} already has a pending invitation.<br>Are you sure you want to resend?</p>
                    <button type="button" class="button cancel-button" id="cancelButton">No</button>
                    <button class="button confirm-button" id="confirmButton">Yes</button>
                `;
                await showCustomPopup(user, popup_html);
                console.log("Resending invitation.");
            } catch {
                console.log("Not resending invitation.");
                return;
            }
        }

        try {
            const response = await fetch(`/projects/${projectId}/invite_user`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ userId: user.id })
            });
    
            const data = await response.json();
    
            if (data.success) {
                const userList = document.getElementById('invited-users-list');
                if (!userList) {
                    console.error('Invited users list element not found.');
                    return;
                }
    
                const listItem = document.createElement('li');
                listItem.setAttribute('data-id', user.id);
    
                if (!user.hasPendingInvitation) {
                    listItem.innerHTML = `
                        <div class="user-list-card">
                            <a href="/profile/${user.username}">
                                <div class="user-list-content">
                                    <span id="user-name-project">${user.name} (${user.username})</span>
                                </div>
                            </a>

                            <button class="revoke-invitation-button button" onclick="showPopup('revoke-${user.id}-popup');">
                                <i class="fa-solid fa-xmark"></i>
                            </button>

                            <form class="project-form" method="POST" action="/projects/revoke/invitations/${projectId}/${user.id}" id="revokeInvitationForm-${user.id}">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').getAttribute('content')}">

                                <div id="revoke-${user.id}-popup" class="confirmation-popup hidden">
                                    <p>Are you sure you want to cancel ${user.name}'s invitation?</p>
                                    <button type="button" class="button cancel-button" onclick="hidePopup('revoke-${user.id}-popup')">No</button>
                                    <button class="button confirm-button" onclick="document.getElementById('revokeMemberForm-${user.id}').submit();">Yes</button>
                                </div>
                            </form>
                        </div>

                    `;
                    userList.appendChild(listItem);
                }
                
                let successMessage = document.createElement('div');
                successMessage.classList.add('alert', 'alert-success');
                successMessage.textContent = `Project invitation sent to ${user.name}!`;
                document.querySelector('.errors').appendChild(successMessage);
    
                const noMembersElement = document.getElementById('no-invited-members');
                if (noMembersElement) {
                    noMembersElement.style.display = 'none';
                }
            } else {
                console.error('Error inviting user to the project:', data.error);
            }
        } catch (error) {
            console.error('Error inviting user to the project:', error);
        }
    }
});
