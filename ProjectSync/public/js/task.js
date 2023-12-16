function toggleEditComment(commentId) {
    var editCommentDiv = document.querySelector('.editComment[data-id="' + commentId + '"]');

    if (editCommentDiv.style.display === 'none') {
        editCommentDiv.style.display = 'block';
        document.querySelector('.toggle_comment_edit[data-id="' + commentId + '"]').textContent = 'Hide';
    } else {
        editCommentDiv.style.display = 'none';
        document.querySelector('.toggle_comment_edit[data-id="' + commentId + '"]').textContent = 'Edit';
    }
}

function toggleEditTask() {
    var editTaskDiv = document.getElementById('task-actions');

    if (editTaskDiv.style.display === 'none') {
        editTaskDiv.style.display = 'block';
        document.getElementById('toggle_task_edit').textContent = 'Hide';
    } else {
        editTaskDiv.style.display = 'none';
        document.getElementById('toggle_task_edit').textContent = 'Show Edit Settings';
    }
}