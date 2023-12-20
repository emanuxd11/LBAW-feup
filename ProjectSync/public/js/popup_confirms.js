const showPopup = (element_id, event) => {
    if (event) {
        event.stopPropagation();
    }

    const modalOverlay = document.createElement('div');
    modalOverlay.classList.add('modal-overlay');
    document.body.appendChild(modalOverlay);

    const currentPopup = document.getElementById(element_id);
    currentPopup.style.display = 'block';
};

/* test */
// const showOrphanPopup = (element_id, event) => {
//     if (event) {
//         event.stopPropagation();
//     }

//     // Assuming you have a target element where you want to insert the form
//     const targetElement = document.getElementById(element_id);

//     // Example HTML content for the form
//     const formHTML = `
//         <form class="project-form" method="POST" action="{{ route('project.revoke.invitations', ['project_id' => $project->id, 'user_id' => $user->id]) }}" id="revokeInvitationForm-{{ $user->id }}">
//             @method('DELETE')
//             @csrf
//             <div id="revoke-{{ $user->id }}-popup" class="confirmation-popup hidden">
//                 <p>Are you sure you want to cancel {{ $user->name }}'s invitation?</p>
//                 <button type="button" class="button cancel-button" onclick="hidePopup('revoke-{{ $user->id }}-popup')">No</button>
//                 <button class="button confirm-button">Yes</button>
//             </div>
//         </form>
//     `;

//     // Set the HTML content of the target element
//     targetElement.insertAdjacentHTML('beforeend', formHTML);
//     targetElement.style.display = 'block';
// };

const hidePopup = (element_id) => {
    const currentPopup = document.getElementById(element_id);
    currentPopup.style.display = 'none';

    const modalOverlay = document.querySelector('.modal-overlay');
    if (modalOverlay) {
        document.body.removeChild(modalOverlay);
    }
};

