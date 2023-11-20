document.addEventListener('DOMContentLoaded', function () {
    // Get references to the button and task wrapper div
    var toggleButton = document.getElementById('toggleTaskWrapper');
    var taskWrapper = document.querySelector('.edit-task-wrapper');

    // Add click event listener to the button
    toggleButton.addEventListener('click', function () {
        // Toggle the 'hidden' class on the task wrapper div
        taskWrapper.classList.toggle('hidden');
    });
});