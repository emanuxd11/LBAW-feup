document.addEventListener('DOMContentLoaded', function () {
    var favorite_button = document.getElementById("favorite-button");

    favorite_button.addEventListener('click', function(e) {
        e.preventDefault();

        var action = this.getAttribute('data-action');
        const project_id = window.location.pathname.split('/').pop();

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/projects/' + project_id + '/favorite', true);
        xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

        xhr.onload = function() {
            var alertDivs = document.querySelectorAll('.alert.alert-success');
            alertDivs.forEach(function(alertDiv) {
                alertDiv.remove();
            });

            if (xhr.status >= 200 && xhr.status < 300) {
                
                if (action == 'unfavorite-project') {
                    favorite_button.classList.remove('favorite-button-solid');
                    favorite_button.classList.add('favorite-button-empty');
                    favorite_button.setAttribute('data-action', 'favorite-project');
                    favorite_button.querySelector('i').classList.remove('fa-solid');
                    favorite_button.querySelector('i').classList.add('fa-regular');
                } else if (action == 'favorite-project') {
                    favorite_button.classList.remove('favorite-button-empty');
                    favorite_button.classList.add('favorite-button-solid');
                    favorite_button.setAttribute('data-action', 'unfavorite-project');
                    favorite_button.querySelector('i').classList.remove('fa-regular');
                    favorite_button.querySelector('i').classList.add('fa-solid');
                }

                // var successMessage = document.createElement('div');
                // successMessage.classList.add('alert', 'alert-success');
                // successMessage.textContent = (action == 'favorite-project') 
                //     ? 'Project added to favorites!'
                //     : 'Project removed from favorites.';
                // document.querySelector('.errors').appendChild(successMessage);
                
            } else {
                console.error('Error:', xhr.status, xhr.statusText);
            }
        };

        xhr.onerror = function() {
            console.error('Network error');
        };
        
        xhr.send(JSON.stringify({
            _token: '{{ csrf_token() }}',
            upvote: action
        }));

        favorite_button.blur();
    });
});

