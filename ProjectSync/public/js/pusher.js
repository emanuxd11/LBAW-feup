const pusherAppKey = '86d630f278978f79fed7';
const pusherCluster = 'eu';

document.addEventListener('DOMContentLoaded', function () {
    const pusher = new Pusher(pusherAppKey, {
        cluster: pusherCluster,
        encrypted: true
    });

    const channel = pusher.subscribe('ProjectSync');
    channel.bind('notification-notificationevent', function (data) {

        const toastContainer = document.getElementById('toastContainer');
        const toast = document.createElement('div');
        toast.classList.add('toast', 'larger-toast');
        toast.classList.add('hide');
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');

        const header = document.createElement('div');
        header.classList.add('toast-header', 'larger-header');
        const title = document.createElement('strong');
        title.classList.add('mr-auto');
        title.innerText = 'Notification';
        header.appendChild(title);

        const body = document.createElement('div');
        body.classList.add('toast-body', 'larger-body');
        body.innerText = data.message;

        toast.appendChild(header);
        toast.appendChild(body);

        toastContainer.appendChild(toast);

        const bootstrapToast = new bootstrap.Toast(toast);
        bootstrapToast.show();

        setTimeout(function () {
            bootstrapToast.dispose();
        }, 4000);
    });
});
