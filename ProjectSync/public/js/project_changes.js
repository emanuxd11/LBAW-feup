function openModal() {
    document.getElementById('changesModal').style.display = 'block';
}

function closeModal() {
    document.getElementById('changesModal').style.display = 'none';
}

// Close modal if the user clicks outside the content
window.onclick = function(event) {
    var modal = document.getElementById('changesModal');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
};