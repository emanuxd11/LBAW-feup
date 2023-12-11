document.addEventListener('DOMContentLoaded', function () {
    const removeButtons = document.querySelectorAll('.remove-member-button');

    removeButtons.forEach(button => {
        button.addEventListener('click', showConfirmationPopup);
    });
});

const showPopup = (element_id) => {
    const currentPopup = document.getElementById(element_id);
    currentPopup.style.display = 'block';

    const cancelButton = currentPopup.querySelector('.cancel-button');
    const confirmButton = currentPopup.querySelector('.confirm-button');

    cancelButton.addEventListener('click', cancelRemoval);
    confirmButton.addEventListener('click', confirmRemoval);
};

const hidePopup = (element_id) => {
    const currentPopup = document.getElementById(element_id);
    currentPopup.style.display = 'none';
};

const cancelRemoval = () => {
    const currentPopup = document.getElementById('confirmation-popup');
    currentPopup.style.display = 'none';
};

const confirmRemoval = () => {
    // You can add logic here to submit the form if needed
    const form = document.getElementById('removeMemberForm');
    form.submit();
};
