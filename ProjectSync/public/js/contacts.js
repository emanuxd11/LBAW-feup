// contacts.js

function toggleContacts(card) {
  card.classList.toggle('active_contacts');

  var body = card.querySelector('.contacts-body');
  var displayStyle = window.getComputedStyle(body).getPropertyValue('display');

  if (displayStyle === 'block') {
      body.style.display = 'none';
  } else {
      body.style.display = 'block';
  }
}
