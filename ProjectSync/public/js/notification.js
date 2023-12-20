document.addEventListener('DOMContentLoaded', function () {
    var pusher = new Pusher({
        key: '{{ config('broadcasting.connections.pusher.key') }}',
        cluster: '{{ config('broadcasting.connections.pusher.options.cluster') }}',
        encrypted: true
    });

    var channel = pusher.subscribe('task-channel');
    channel.bind('task-done-event', function (data) {
        // Replace this with your notification system
        showNotification(data.message);
    });

    function showNotification(message) {
        // Implement your notification logic here
        // You may use a library like Toastr, SweetAlert, or your custom notification component
        console.log('Notification:', message);
    }
});