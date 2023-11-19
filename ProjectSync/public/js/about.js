// about.js

function toggleAbout(card) {
    card.classList.toggle('active_about');

    var body = card.querySelector('.about-body');
    var displayStyle = window.getComputedStyle(body).getPropertyValue('display');

    if (displayStyle === 'block') {
        body.style.display = 'none';
    } else {
        body.style.display = 'block';
    }
}
