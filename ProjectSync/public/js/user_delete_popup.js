const showConfirmationPopup = (event) => {
    const popups = document.querySelectorAll('.confirmation-popup');
    popups.forEach(popup => popup.classList.add('hidden'));

    const currentPopup = event.currentTarget.parentElement.querySelector('.confirmation-popup');
    currentPopup.classList.remove('hidden');
};

const cancelRemoval = () => {
    const popups = document.querySelectorAll('.confirmation-popup');
    popups.forEach(popup => popup.classList.add('hidden'));
};