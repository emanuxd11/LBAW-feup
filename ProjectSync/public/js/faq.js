// faq.js

function toggleFAQ(card) {
    card.classList.toggle('active_faq');

    var body = card.querySelector('.faq-body');
    var displayStyle = window.getComputedStyle(body).getPropertyValue('display');

    if (displayStyle === 'block') {
        body.style.display = 'none';
    } else {
        body.style.display = 'block';
    }
}
