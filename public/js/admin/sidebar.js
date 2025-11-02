// Sidebar toggle functionality
const sidebar = document.getElementById("sidebar");
const openSidebar = document.getElementById("openSidebar");
const closeSidebar = document.getElementById("closeSidebar");
const sidebarOverlay = document.getElementById("sidebarOverlay");

function showSidebar() {
    if (sidebar) {
        sidebar.classList.remove("-translate-x-full");
        if (sidebarOverlay) sidebarOverlay.classList.remove("hidden");
    }
}

function hideSidebar() {
    if (sidebar) {
        sidebar.classList.add("-translate-x-full");
        if (sidebarOverlay) sidebarOverlay.classList.add("hidden");
    }
}

openSidebar?.addEventListener("click", showSidebar);
closeSidebar?.addEventListener("click", hideSidebar);
sidebarOverlay?.addEventListener("click", hideSidebar);

// Dropdown functionality
function toggleDropdown(dropdownId) {
    const menu = document.getElementById(dropdownId + "-menu");
    const arrow = document.getElementById(dropdownId + "-arrow");

    if (!menu) return;

    if (menu.classList.contains("hidden")) {
        menu.classList.remove("hidden");
        if (arrow) arrow.classList.add("rotate-180");
    } else {
        menu.classList.add("hidden");
        if (arrow) arrow.classList.remove("rotate-180");
    }
}

// User menu toggle with event parameter
function toggleUserMenu(event) {
    event?.stopPropagation(); // Prevent closing immediately
    const menu = document.getElementById("userMenu");
    if (menu) {
        menu.classList.toggle("hidden");
    }
}

// Close user menu when clicking outside
document.addEventListener("click", function (event) {
    const menu = document.getElementById("userMenu");
    const button = document.querySelector("button[onclick*='toggleUserMenu']");

    // If clicking outside the menu and button, close it
    if (menu && !menu.contains(event.target) && !button?.contains(event.target)) {
        menu.classList.add("hidden");
    }
});

// Close sidebar on window resize
window.addEventListener("resize", function () {
    if (window.innerWidth >= 1024) {
        hideSidebar();
    }
});
