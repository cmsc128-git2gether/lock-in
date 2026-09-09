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
    const taskTitle = form. dataset.taskTitle;
    const row = button.closest('tr');

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
    notif.innerHTML = `<span>Deleted "${taskTitle}"</span>
        <button type="button" class="notif-undo">Undo</button>
    `;

    container.appendChild(notif);

    //remove once undo time window passes
    const timeout = setTimeout(() => {
        notif.remove();
        row.remove();
    }, 5000);

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


const priorityWeight = {
    High: 3,
    Medium: 2,
    Low: 1,
    Unlabeled: 0,
};

//sort feature
function sortTable(key) {
    const table = document.getElementById('table-tasks');
    if (!table) return;
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr:not(.group-header-row)'));
 
    const dir = table.dataset.sortKey === key && table.dataset.sortDir === 'asc' ? 'desc' : 'asc';
    table.dataset.sortKey = key;
    table.dataset.sortDir = dir;
 
    rows.sort((a, b) => {
        let valA, valB;
 
        switch (key) {
            case 'created_at':
                valA = a.dataset.created ? new Date(a.dataset.created).getTime() : 0;
                valB = b.dataset.created ? new Date(b.dataset.created).getTime() : 0;
                break;
            case 'due_at':
                valA = a.dataset.due ? new Date(a.dataset.due).getTime() : Number.MAX_SAFE_INTEGER;
                valB = b.dataset.due ? new Date(b.dataset.due).getTime() : Number.MAX_SAFE_INTEGER;
                break;
            case 'priority':
                valA = priorityWeight[a.dataset.priority] ?? -1;
                valB = priorityWeight[b.dataset.priority] ?? -1;
                break;
            case 'tag_id':
                valA = a.dataset.tagId || 0;
                valB = b.dataset.tagId || 0;
                break;
            default:
                return 0;
        }
 
        if (valA < valB) return dir === 'asc' ? -1 : 1;
        if (valA > valB) return dir === 'asc' ? 1 : -1;
        return 0;
    });
 
    rows.forEach(row => tbody.appendChild(row));
 
    const select = document.getElementById('sortSelect');
    if (select) select.value = key;

}
 
// groupings
function applyGrouping() {
    const tbody = document.querySelector('#table-tasks tbody');
    if (!tbody) return;
 
    document.querySelectorAll('.group-header-row').forEach(el => el.remove());
 
    const rows = Array.from(tbody.querySelectorAll('tr'));
    let lastTag = null;
 
    rows.forEach(row => {
        const tag = row.dataset.tag || 'Untagged';
        if (tag !== lastTag) {
            const headerRow = document.createElement('tr');
            headerRow.className = 'group-header-row';
            const td = document.createElement('td');
            td.colSpan = 5;
            td.textContent = tag;
            td.style.fontWeight = 'bold';
            td.style.background = '#f3f4f6';
            headerRow.appendChild(td);
            tbody.insertBefore(headerRow, row);
            lastTag = tag;
        }
    });
}
 
document.getElementById('groupByTag')?.addEventListener('change', function () {
    if (this.checked) {
        sortTable('tag_id');
    } else {
        document.querySelectorAll('.group-header-row').forEach(el => el.remove());
    }
});

window.toggleColFilter = toggleColFilter;
window.filterByColumn = filterByColumn;
window.handleDelete = handleDelete;
window.restoreTask = restoreTask;
window.sortTable = sortTable;