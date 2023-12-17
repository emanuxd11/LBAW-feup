const showPopup = (element_id) => {
    const modalOverlay = document.createElement('div');
    modalOverlay.classList.add('modal-overlay');
    document.body.appendChild(modalOverlay);

    const currentPopup = document.getElementById(element_id);
    currentPopup.style.display = 'block';
};

const hidePopup = (element_id) => {
    const currentPopup = document.getElementById(element_id);
    currentPopup.style.display = 'none';

    const modalOverlay = document.querySelector('.modal-overlay');
    if (modalOverlay) {
        document.body.removeChild(modalOverlay);
    }
};
