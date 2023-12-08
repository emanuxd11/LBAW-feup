document.addEventListener('DOMContentLoaded', function () {
    var upvoteButtons = document.querySelectorAll('.upvote-button, .downvote-button');

    upvoteButtons.forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            var action = this.getAttribute('data-action');
        
            //var postId = document.querySelector('.PostBody').getAttribute('data-id');
    

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