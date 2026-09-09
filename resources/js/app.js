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