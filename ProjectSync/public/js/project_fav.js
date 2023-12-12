document.addEventListener('DOMContentLoaded', function () {
    var favoriteButtons = document.querySelectorAll('.favorite-button');

    favoriteButtons.forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            var projectId = this.getAttribute('data-project-id');
            var userId = this.getAttribute('data-user-id');

            var xhr = new XMLHttpRequest();
            xhr.open('POST', '/projects/' + projectId + '/favorite', true);
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            xhr.setRequestHeader('Content-Type', 'application/json;charset=UTF-8');

            xhr.onload = function () {
                if (xhr.status >= 200 && xhr.status < 300) {
                    var response = JSON.parse(xhr.responseText);

                    var favoriteIcon = document.querySelector('.favorite-button i');
                    if (favoriteIcon) {
                        if (response.is_favorite) {
                            favoriteIcon.classList.remove('fa-regular');
                            favoriteIcon.classList.add('fa-solid');
                        } else {
                            favoriteIcon.classList.remove('fa-solid');
                            favoriteIcon.classList.add('fa-regular');
                        }
                    }
                } else {
                    console.error('Error:', xhr.status, xhr.statusText);
                }
            };

            xhr.onerror = function () {
                console.error('Network error');
            };

            xhr.send(JSON.stringify({
                _token: '{{ csrf_token() }}',
                favorite: action
            }));

            favoriteButton.blur();
        });
    }
});
