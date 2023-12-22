function showContextMenu(userId) {
    function hideAllContextMenus() {
        document.querySelectorAll('.context-menu').forEach(el => {
            el.style.display = 'none';
        });
    }
    hideAllContextMenus();

    const contextMenu = document.getElementById(`contextMenu-${userId}`);
    const pageX = event.pageX;
    const pageY = event.pageY;
    
    contextMenu.style.display = 'block';
    const menuWidth = contextMenu.offsetWidth;
    const screenWidth = window.innerWidth;
    if (pageX + menuWidth <= screenWidth) {
        contextMenu.style.left = `${pageX}px`;
    } else {
        contextMenu.style.left = `${pageX - menuWidth}px`;
    }
    contextMenu.style.top = `${pageY}px`;
    console.log("Opened new context menu")

    document.getElementById(`contextMenuItemProfile-${userId}`).addEventListener('click', function(event) {
        event.stopPropagation();
        console.log(`Clicked Profile context menu item for user ID: ${userId}`);
        hideAllContextMenus();
    });

    document.getElementById(`contextMenuItemRevoke-${userId}`).addEventListener('click', function(event) {
        event.stopPropagation();
        console.log(`Clicked Revoke context menu item for user ID: ${userId}`);
        hideAllContextMenus();
    });

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && contextMenu.style.display !== 'none') {
            console.log('Hiding context menu on escape')
            hideAllContextMenus();
        }
    });

    document.body.addEventListener('mousedown', function(event) {
        const isClickInsideContextMenu = contextMenu.contains(event.target);
        if (!isClickInsideContextMenu) {
            console.log('Clicked outside context menu. Hiding context menu');
            contextMenu.style.display = 'none';
        }
    });
}

window.addEventListener('resize', function() {
    document.querySelectorAll('.context-menu').forEach(el => {
        el.style.display = 'none'
    });
});

const removeModalOverlay = () => {
    const modalOverlay = document.querySelector('.modal-overlay');
    if (modalOverlay) {
        document.body.removeChild(modalOverlay);
    }
}

const showInviteUsers = (event) => {
    if (event) {
        event.stopPropagation();
    }

    const modalOverlay = document.createElement('div');
    modalOverlay.classList.add('modal-overlay');
    modalOverlay.style.zIndex = 99999;
    document.body.appendChild(modalOverlay);

    const currentPopup = document.getElementById("invite-users-container");
    currentPopup.style.display = 'block';
    currentPopup.style.zIndex = 100000;
    currentPopup.style.position = 'fixed';
    currentPopup.style.top = '15%';

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && currentPopup.style.display !== 'none') {
            console.log('Hiding invite popup menu on escape')
            hideInviteUsers(event)
        }
    });
};

const hideInviteUsers = (event) => {
    if (event) {
        event.stopPropagation();
    }
    const currentPopup = document.getElementById("invite-users-container");
    currentPopup.style.display = 'none';
    removeModalOverlay();
};

const showProjectSettings = (event) => {
    if (event) {
        event.stopPropagation();
    }

    const modalOverlay = document.createElement('div');
    modalOverlay.classList.add('modal-overlay');
    modalOverlay.style.zIndex = 99999;
    document.body.appendChild(modalOverlay);

    const currentPopup = document.getElementById("project-settings-container");
    currentPopup.style.display = 'block';
    currentPopup.style.zIndex = 100000;
    currentPopup.style.position = 'fixed';
    currentPopup.style.top = '15%';

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && currentPopup.style.display !== 'none') {
            console.log('Hiding invite popup menu on escape')
            hideProjectSettings(event)
        }
    });
};

const hideProjectSettings = (event) => {
    if (event) {
        event.stopPropagation();
    }
    const currentPopup = document.getElementById("project-settings-container");
    currentPopup.style.display = 'none';
    removeModalOverlay();
};

const showCreateTask = (event) => {
    if (event) {
        event.stopPropagation();
    }

    const modalOverlay = document.createElement('div');
    modalOverlay.classList.add('modal-overlay');
    modalOverlay.style.zIndex = 99999;
    document.body.appendChild(modalOverlay);

    const currentPopup = document.getElementById("create-task-container");
    currentPopup.style.display = 'block';
    currentPopup.style.zIndex = 100000;
    currentPopup.style.position = 'fixed';
    currentPopup.style.top = '15%';

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && currentPopup.style.display !== 'none') {
            console.log('Create task popup menu on escape')
            hideCreateTask(event)
        }
    });
};

const hideCreateTask = (event) => {
    if (event) {
        event.stopPropagation();
    }
    const currentPopup = document.getElementById("create-task-container");
    currentPopup.style.display = 'none';
    removeModalOverlay();
};
