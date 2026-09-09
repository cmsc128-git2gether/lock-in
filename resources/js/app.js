import './bootstrap';

function toggle(event) {
    event.stopPropagation();
    const currMenu = event.currentTarget.nextElementSibling;
    document.querySelectorAll('.dropdown-choices').forEach(menu => {
        if (menu !== currMenu) {
            menu.classList.remove('show');
        }
    });
    currMenu.classList.toggle('show');
}

window.toggle = toggle;

document.addEventListener('click', () => {
    document.querySelectorAll('.dropdown-choices').forEach(menu => {
        menu.classList.remove('show');
    });
});

function handleDelete(button){
    const form = button.closest('.delete-form');
    const taskId = form.dataset.taskId;
    const taskTitle = form.dataset.taskTitle;
    const row = button.closest('tr');

    row.style.display = 'none';

    fetch(`/tasks/${taskId}/destroy`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
    }) .then(response => {
        if (!response.ok) throw new Error('Delete failed');
        showUndo(taskId, taskTitle, row);
    })
    .catch(() => {
        row.style.display = ''; // restore row if delete failed
        alert('Something went wrong deleting that task.');
    });

}

//undo delete feature
function showUndo(taskId, taskTitle, row){
    const container = document.getElementById('notif-container');

    const notif = document.createElement('div');
    notif.className='notif';
    notif.innerHTML = `
            <div class="notif-content">
                <span>Deleted "${taskTitle}"</span>
                <button type="button" class="notif-undo">Undo</button>
            </div>
            <div class="notif-progress"></div>
        `;

    container.appendChild(notif);

    const duration = 3000;
    const progressBar = notif.querySelector('.notif-progress');
    progressBar.style.animationDuration = `${duration}ms`;

    const timeout = setTimeout(() => {
        notif.remove();
        row.remove();
    }, duration);



    //if clicked, restore
    notif.querySelector('.notif-undo').addEventListener('click', () => {
        clearTimeout(timeout);
        restoreTask(taskId, row, notif);
    });
}


function restoreTask(taskId, row, notif) {
    fetch(`/tasks/${taskId}/restore`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
    })
    .then(response => {
        if (!response.ok) throw new Error('Restore failed');
        row.style.display = '';
        notif.remove();
    })
    .catch(() => {
        alert('Could not undo — please refresh.');
    });
}

//filter feature
const activeFilters = { priority: '', tag: '', status: '' };

function toggleColFilter(event) {
    event.stopPropagation();
    const panel = event.target.nextElementSibling;
    document.querySelectorAll('.th-filter-choices.show, .dropdown-choices.show').forEach(e => {
        if (e !== panel) e.classList.remove('show');
    });
    panel.classList.toggle('show');
}

function filterByColumn(column, value) {
    activeFilters[column] = value;
    applyFilters();
}

function applyFilters() {
    document.querySelectorAll('#table-tasks tbody tr').forEach(row => {
        const matchesPriority = !activeFilters.priority || row.dataset.priority === activeFilters.priority;
        const matchesTag = !activeFilters.tag || row.dataset.tag === activeFilters.tag;
        const matchesStatus = !activeFilters.status || row.dataset.status === activeFilters.status;

        row.style.display = (matchesPriority && matchesTag && matchesStatus) ? '' : 'none';
    });
}

document.addEventListener('click', () => {
    document.querySelectorAll('.th-filter-choices.show, .dropdown-choices.show').forEach(el => el.classList.remove('show'));
});

function togglePopup() {
    const overlay = document.getElementById('popupOverlay');
    overlay.classList.toggle('active');
}





window.toggleColFilter = toggleColFilter;
window.filterByColumn = filterByColumn;
window.handleDelete = handleDelete;
window.restoreTask = restoreTask;