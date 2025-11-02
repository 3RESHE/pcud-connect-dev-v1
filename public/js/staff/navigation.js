// Mobile menu toggle function
function toggleMobileMenu() {
    const mobileMenu = document.getElementById("mobileMenu");
    const hamburgerIcon = document.getElementById("hamburgerIcon");
    const closeIcon = document.getElementById("closeIcon");
    mobileMenu.classList.toggle("hidden");
    hamburgerIcon.classList.toggle("hidden");
    closeIcon.classList.toggle("hidden");
    const button = document.querySelector('[onclick="toggleMobileMenu()"]');
    const isExpanded = !mobileMenu.classList.contains("hidden");
    button.setAttribute("aria-expanded", isExpanded);
}

// User menu toggle function
function toggleUserMenu() {
    const menu = document.getElementById("userMenu");
    menu.classList.toggle("hidden");
}

// Close menus when clicking outside
document.addEventListener("click", function (event) {
    const userMenu = document.getElementById("userMenu");
    const mobileMenu = document.getElementById("mobileMenu");
    const userButton = event.target.closest('button[onclick="toggleUserMenu()"]');
    const mobileButton = event.target.closest('button[onclick="toggleMobileMenu()"]');

    if (!userButton && !userMenu.contains(event.target)) {
        userMenu.classList.add("hidden");
    }
    if (
        !mobileButton &&
        !mobileMenu.contains(event.target) &&
        !mobileMenu.classList.contains("hidden")
    ) {
        toggleMobileMenu();
    }
});

// Handle window resize - close mobile menu on desktop
window.addEventListener("resize", function () {
    const mobileMenu = document.getElementById("mobileMenu");
    const hamburgerIcon = document.getElementById("hamburgerIcon");
    const closeIcon = document.getElementById("closeIcon");
    if (
        window.innerWidth >= 768 &&
        !mobileMenu.classList.contains("hidden")
    ) {
        mobileMenu.classList.add("hidden");
        hamburgerIcon.classList.remove("hidden");
        closeIcon.classList.add("hidden");
        const button = document.querySelector('[onclick="toggleMobileMenu()"]');
        button.setAttribute("aria-expanded", "false");
    }
});

// Handle escape key to close menus
document.addEventListener("keydown", function (event) {
    if (event.key === "Escape") {
        const userMenu = document.getElementById("userMenu");
        const mobileMenu = document.getElementById("mobileMenu");
        if (!userMenu.classList.contains("hidden")) {
            userMenu.classList.add("hidden");
        }
        if (!mobileMenu.classList.contains("hidden")) {
            toggleMobileMenu();
        }
    }
});
