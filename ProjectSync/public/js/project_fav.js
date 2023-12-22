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
                
                fetchDataAndRefreshDiv('side-bar-projects');

            } else {
                console.error('Error:', xhr.status, xhr.statusText);
            }
        };

        xhr.onerror = function() {
            console.error('Network error');
        };
        
        xhr.send(JSON.stringify({
            upvote: action
        }));

        favorite_button.blur();
    });
});

function updateDivContent(divId, jsonResponse) {
    var targetDiv = document.getElementById(divId);
    if (targetDiv && jsonResponse && jsonResponse.html) {
        var tempContainer = document.createElement('div');
        tempContainer.innerHTML = jsonResponse.html;
        tempContainer = tempContainer.querySelector(`#${divId}`);
        targetDiv.innerHTML = '';
        var navElement = document.createElement('nav');
        navElement.classList.add('nav-menu');
        targetDiv.appendChild(navElement);
        var childNodes = tempContainer.childNodes;
        if (childNodes[1]) {
            navElement.appendChild(childNodes[1].cloneNode(true));
        }
    }
}

// Example of making an AJAX request and updating a specific div
function fetchDataAndRefreshDiv(divId) {
    var xhr = new XMLHttpRequest();

    var currentUrl = window.location.href;
    var pathArray = currentUrl.split('/');
    var cleanPathArray = pathArray.filter(function (segment) {
        return segment !== '';
    });
    var project_id = cleanPathArray[cleanPathArray.length - 1];

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                // Parse the JSON response
                var jsonResponse = JSON.parse(xhr.responseText);

                // Update the specified div with the new content
                updateDivContent(divId, jsonResponse);

            } else {
                console.error('Error:', xhr.statusText);
            }
        }
    };

    // Adjust the URL to the endpoint that returns the updated HTML
    xhr.open('GET', `/projects/favorite/sidebar/reload/${project_id}`, true);
    xhr.send();
}
