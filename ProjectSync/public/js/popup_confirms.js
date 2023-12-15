const showPopup = (element_id) => {
    const currentPopup = document.getElementById(element_id);
    currentPopup.style.display = 'block';
};

const hidePopup = (element_id) => {
    const currentPopup = document.getElementById(element_id);
    currentPopup.style.display = 'none';
};
