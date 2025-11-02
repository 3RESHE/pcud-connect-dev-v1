// Sidebar toggle functionality
const sidebar = document.getElementById("sidebar");
const openSidebar = document.getElementById("openSidebar");
const closeSidebar = document.getElementById("closeSidebar");
const sidebarOverlay = document.getElementById("sidebarOverlay");

function showSidebar() {
    sidebar.classList.remove("-translate-x-full");
    sidebarOverlay.classList.remove("hidden");
}

function hideSidebar() {
    sidebar.classList.add("-translate-x-full");
    sidebarOverlay.classList.add("hidden");
}

openSidebar?.addEventListener("click", showSidebar);
closeSidebar?.addEventListener("click", hideSidebar);
sidebarOverlay?.addEventListener("click", hideSidebar);

// Dropdown functionality
function toggleDropdown(dropdownId) {
    const menu = document.getElementById(dropdownId + "-menu");
    const arrow = document.getElementById(dropdownId + "-arrow");

    if (menu.classList.contains("hidden")) {
        menu.classList.remove("hidden");
        arrow.classList.add("rotate-180");
    } else {
        menu.classList.add("hidden");
        arrow.classList.remove("rotate-180");
    }
}

function toggleUserMenu() {
    const menu = document.getElementById("userMenu");
    menu.classList.toggle("hidden");
}

function logout() {
    if (confirm("Are you sure you want to logout?")) {
        document.querySelector('form[action*="logout"]').submit();
    }
}

function approveItem(type, id) {
    if (confirm(`Are you sure you want to approve this ${type}?`)) {
        alert(`${type.charAt(0).toUpperCase() + type.slice(1)} #${id} approved successfully!`);
        location.reload();
    }
}

function rejectItem(type, id) {
    if (confirm(`Are you sure you want to reject this ${type}?`)) {
        alert(`${type.charAt(0).toUpperCase() + type.slice(1)} #${id} rejected.`);
        location.reload();
    }
}

// Close user menu when clicking outside
document.addEventListener("click", function (event) {
    const menu = document.getElementById("userMenu");
    const button = event.target.closest("button");

    if (!button || button.onclick !== toggleUserMenu) {
        menu.classList.add("hidden");
    }
});

// Close sidebar on window resize
window.addEventListener("resize", function () {
    if (window.innerWidth >= 1024) {
        hideSidebar();
    }
});
