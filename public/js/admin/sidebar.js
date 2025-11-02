function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');

    sidebar.classList.toggle('-translate-x-full');
    overlay.classList.toggle('hidden');
}

function toggleSubmenu(submenuId) {
    const submenu = document.getElementById(submenuId);
    const arrow = submenu.previousElementSibling.querySelector('svg:last-child');

    submenu.classList.toggle('hidden');

    if (arrow) {
        arrow.classList.toggle('rotate-180');
    }
}

// Close sidebar when clicking on a link (mobile)
document.addEventListener('DOMContentLoaded', function() {
    const sidebarLinks = document.querySelectorAll('#sidebar a');
    sidebarLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth < 1024) {
                toggleSidebar();
            }
        });
    });

    // Open submenus if current route matches
    const currentRoute = window.location.pathname;

    if (currentRoute.includes('/admin/users')) {
        document.getElementById('usersSubmenu').classList.remove('hidden');
    }
    if (currentRoute.includes('/admin/approvals/jobs')) {
        document.getElementById('jobsSubmenu').classList.remove('hidden');
    }
    if (currentRoute.includes('/admin/approvals/events')) {
        document.getElementById('eventsSubmenu').classList.remove('hidden');
    }
    if (currentRoute.includes('/admin/approvals/news')) {
        document.getElementById('newsSubmenu').classList.remove('hidden');
    }
    if (currentRoute.includes('/admin/approvals/partnerships')) {
        document.getElementById('partnershipsSubmenu').classList.remove('hidden');
    }
});
