document.addEventListener('DOMContentLoaded', function () {
    var upvoteButtons = document.querySelectorAll('.upvote-button, .downvote-button,.upvote-button-pressed, .downvote-button-pressed');

    upvoteButtons.forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            var action = this.getAttribute('data-action');
            var postId = this.closest('.post').getAttribute('data-id');

            var xhr = new XMLHttpRequest();
            xhr.open('POST', '/post/' + postId + '/upvote', true);
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            xhr.onload = function() {
                if (xhr.status >= 200 && xhr.status < 300) {
                    var response = JSON.parse(xhr.responseText);

                    var upvotesCount = document.querySelector('.post[data-id="' + postId + '"] .upvotes-count');
                    if (upvotesCount) {
                        upvotesCount.textContent = response.upvotes;
                    }
                    
                    if(button.getAttribute('class') == 'upvote-button'){
                        var button_old = document.querySelector('.downvote-button-pressed');
                        if(button_old != null){
                            button_old.classList.remove('downvote-button-pressed');
                            button_old.classList.add('downvote-button');
                        }

                        button.classList.remove('upvote-button');
                        button.classList.add('upvote-button-pressed');
                    }
                    else if(button.getAttribute('class') == 'upvote-button-pressed'){
                        button.classList.remove('upvote-button-pressed');
                        button.classList.add('upvote-button');
                    }
                    else if(button.getAttribute('class') == 'downvote-button-pressed'){
                        button.classList.remove('downvote-button-pressed');
                        button.classList.add('downvote-button');
                    }
                    else if(button.getAttribute('class') == 'downvote-button'){
                        var button_old = document.querySelector('.upvote-button-pressed');
                        if(button_old != null){
                            button_old.classList.remove('upvote-button-pressed');
                            button_old.classList.add('upvote-button');
                        }

                        button.classList.remove('downvote-button');
                        button.classList.add('downvote-button-pressed');
                    }
                   
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

            button.blur();
        });
    });
});