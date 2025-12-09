let currentPage = 1;
let currentDeleteId = null;
let selectedUsers = new Set();
let bulkAction = null;
let isProcessing = false;

// Store original form HTML for restore
let originalEditFormHTML = null;

// Initialize
document.addEventListener("DOMContentLoaded", function () {
    // Store the original edit form HTML on page load
    const editUserModal = document.getElementById("editUserModal");
    if (editUserModal) {
        originalEditFormHTML = editUserModal.querySelector(".p-6").innerHTML;
    }
    
    loadDepartments();
    loadStats();
    loadUsers();
    setupEventListeners();
});

// Setup event listeners
function setupEventListeners() {
    const addUserForm = document.getElementById("addUserForm");
    const editUserForm = document.getElementById("editUserForm");
    const searchInput = document.getElementById("searchInput");
    
    if (addUserForm) {
        addUserForm.addEventListener("submit", handleAddUser);
    }
    if (editUserForm) {
        editUserForm.addEventListener("submit", handleEditUser);
    }
    if (searchInput) {
        searchInput.addEventListener("keyup", function () {
            clearTimeout(window.searchTimeout);
            window.searchTimeout = setTimeout(() => filterUsers(), 500);
        });
    }
}

// Load departments
function loadDepartments() {
    fetch("/admin/departments/all?page=1", {
        headers: {
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
        },
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success && data.data) {
                const addSelect = document.getElementById("addDepartmentId");
                const editSelect = document.getElementById("editDepartmentId");

                if (addSelect && editSelect) {
                    data.data.forEach((dept) => {
                        addSelect.innerHTML += `<option value="${dept.id}">${dept.title}</option>`;
                        editSelect.innerHTML += `<option value="${dept.id}">${dept.title}</option>`;
                    });
                }
            }
        })
        .catch((error) => console.error("Error loading departments:", error));
}

// Load statistics
function loadStats() {
    fetch("/admin/users/stats", {
        headers: {
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
        },
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                document.getElementById("statTotal").textContent = data.data.total;
                document.getElementById("statAdmin").textContent = data.data.admin;
                document.getElementById("statStaff").textContent = data.data.staff;
                document.getElementById("statPartner").textContent = data.data.partner;
                document.getElementById("statStudent").textContent = data.data.student;
                document.getElementById("statAlumni").textContent = data.data.alumni;
            }
        })
        .catch((error) => {
            console.error("Error loading stats:", error);
        });
}

// Load users
function loadUsers(page = 1) {
    const search = document.getElementById("searchInput").value;
    const role = document.getElementById("roleFilter").value;
    const status = document.getElementById("statusFilter").value;

    currentPage = page;

    let url = `/admin/users/all?page=${page}`;
    if (role && role !== "all") url += `&role=${role}`;
    if (status) url += `&status=${status}`;
    if (search) url += `&search=${search}`;

    showTableLoading();

    fetch(url, {
        headers: {
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
        },
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                displayUsers(data.data);
                updatePagination(data.pagination);
                clearSelection();
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            showToast("Failed to load users", "error");
        });
}

// Show table loading spinner
function showTableLoading() {
    const tbody = document.getElementById("userTableBody");
    tbody.innerHTML = `
        <tr>
            <td colspan="8" class="px-6 py-12 text-center">
                <div class="flex flex-col items-center justify-center">
                    <div class="relative w-12 h-12 mb-4">
                        <div class="animate-spin absolute inset-0">
                            <div class="h-full w-full border-4 border-blue-200 border-t-primary rounded-full"></div>
                        </div>
                    </div>
                    <p class="text-gray-600 font-medium">Loading users...</p>
                </div>
            </td>
        </tr>
    `;
}

// Display users
function displayUsers(users) {
    const tbody = document.getElementById("userTableBody");

    if (users.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <p class="text-gray-600 font-medium">No users found</p>
                </td>
            </tr>
        `;
        return;
    }

    tbody.innerHTML = users
        .map((user) => {
            let nameDisplay = `${user.first_name} ${user.last_name}`;
            if (
                user.role === "student" &&
                user.student_profile &&
                user.student_profile.student_id
            ) {
                nameDisplay += `<br><span class="text-xs text-gray-500">${user.student_profile.student_id}</span>`;
            }

            return `
            <tr class="user-row-${user.id} hover:bg-gray-50 transition">
                <td class="px-6 py-4 whitespace-nowrap">
                    <input type="checkbox" value="${user.id}" onchange="updateSelection()" class="user-checkbox w-4 h-4 rounded accent-primary cursor-pointer">
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">${nameDisplay}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    ${user.email}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${getRoleColor(
                        user.role
                    )}">
                        ${capitalizeFirst(user.role)}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    ${user.department ? user.department.title : "-"}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${
                        user.is_active
                            ? "bg-green-100 text-green-800"
                            : "bg-red-100 text-red-800"
                    }">
                        ${user.is_active ? "Active" : "Inactive"}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    ${new Date(user.created_at).toLocaleDateString()}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm space-x-2">
                    <button onclick="openEditUserModal(${
                        user.id
                    })" class="text-primary hover:text-blue-700 font-medium transition">
                        Edit
                    </button>
                </td>
            </tr>
        `;
        })
        .join("");
}

// ===== CHECKBOX & SELECTION MANAGEMENT =====

function updateSelection() {
    selectedUsers.clear();
    document.querySelectorAll(".user-checkbox:checked").forEach((checkbox) => {
        selectedUsers.add(parseInt(checkbox.value));
    });

    updateBulkActionBar();
}

function toggleSelectAll() {
    const selectAllCheckbox = document.getElementById("selectAllCheckbox");
    const allCheckboxes = document.querySelectorAll(".user-checkbox");

    allCheckboxes.forEach((checkbox) => {
        checkbox.checked = selectAllCheckbox.checked;
    });

    updateSelection();
}

function clearSelection() {
    selectedUsers.clear();
    document.getElementById("selectAllCheckbox").checked = false;
    document.querySelectorAll(".user-checkbox").forEach((checkbox) => {
        checkbox.checked = false;
    });
    updateBulkActionBar();
}

function updateBulkActionBar() {
    const count = selectedUsers.size;
    const bar = document.getElementById("bulkActionsBar");
    const countDisplay = document.getElementById("selectedCount");

    if (count > 0) {
        bar.classList.remove("hidden");
        countDisplay.textContent = `${count} user${count !== 1 ? "s" : ""} selected`;
    } else {
        bar.classList.add("hidden");
    }
}

// ===== BULK ACTIONS =====

function bulkActivate() {
    if (selectedUsers.size === 0) {
        showToast("Please select users first", "warning");
        return;
    }

    bulkAction = "activate";
    document.getElementById("bulkStatusTitle").textContent = "🟢 Activate Users?";
    document.getElementById("bulkStatusMessage").innerHTML = `You are about to activate <strong id="bulkStatusCount">${selectedUsers.size}</strong> user${selectedUsers.size !== 1 ? "s" : ""}. They will receive an email notification.`;
    document.getElementById("bulkStatusCount").textContent = selectedUsers.size;
    document.getElementById("bulkStatusModal").classList.remove("hidden");
}

function bulkDeactivate() {
    if (selectedUsers.size === 0) {
        showToast("Please select users first", "warning");
        return;
    }

    bulkAction = "deactivate";
    document.getElementById("bulkStatusTitle").textContent = "🔴 Deactivate Users?";
    document.getElementById("bulkStatusMessage").innerHTML = `You are about to deactivate <strong id="bulkStatusCount">${selectedUsers.size}</strong> user${selectedUsers.size !== 1 ? "s" : ""}. They will receive an email notification.`;
    document.getElementById("bulkStatusCount").textContent = selectedUsers.size;
    document.getElementById("bulkStatusModal").classList.remove("hidden");
}

function closeBulkStatusModal() {
    document.getElementById("bulkStatusModal").classList.add("hidden");
    bulkAction = null;
}

function confirmBulkStatusChange() {
    if (selectedUsers.size === 0 || isProcessing) {
        showToast("No users selected", "warning");
        closeBulkStatusModal();
        return;
    }

    isProcessing = true;
    const isActive = bulkAction === "activate" ? 1 : 0;
    const userIds = Array.from(selectedUsers);

    const btn = document.querySelector(
        '#bulkStatusModal button[onclick="confirmBulkStatusChange()"]'
    );
    const confirmModal = document.getElementById("bulkStatusModal");

    const modalContent = confirmModal.querySelector(".relative");
    const originalButtons = modalContent.querySelector(".flex.justify-center");

    originalButtons.innerHTML = `
        <div class="flex flex-col items-center justify-center py-6">
            <div class="relative w-10 h-10 mb-3">
                <div class="animate-spin absolute inset-0">
                    <div class="h-full w-full border-3 border-blue-200 border-t-primary rounded-full"></div>
                </div>
            </div>
            <p class="text-gray-600 text-sm font-medium">Processing...</p>
        </div>
    `;

    btn.disabled = true;

    fetch("/admin/users/bulk-update-status", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN":
                document.querySelector('meta[name="csrf-token"]')?.content || "",
            "Content-Type": "application/json",
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
        },
        body: JSON.stringify({
            user_ids: userIds,
            is_active: isActive,
        }),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                showToast(data.message, "success");
                closeBulkStatusModal();
                clearSelection();
                loadStats();
                loadUsers(currentPage);
            } else {
                showToast(data.message || "Failed to update users", "error");
                originalButtons.innerHTML = `
                    <button type="button" onclick="closeBulkStatusModal()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="button" onclick="confirmBulkStatusChange()" class="px-4 py-2 bg-primary text-white rounded-md hover:bg-blue-700">
                        Confirm
                    </button>
                `;
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            showToast("Failed to update users", "error");
            closeBulkStatusModal();
        })
        .finally(() => {
            btn.disabled = false;
            isProcessing = false;
        });
}

// ===== MODAL FUNCTIONS =====

function openAddUserModal() {
    clearFormErrors("add");
    document.getElementById("addUserForm").reset();
    document.getElementById("addUserModal").classList.remove("hidden");
}

function closeAddUserModal() {
    document.getElementById("addUserModal").classList.add("hidden");
}

/**
 * FIXED: openEditUserModal - Now properly handles form restoration
 */
function openEditUserModal(id) {
    const modalLoadingSpinner = `
        <div class="flex items-center justify-center py-8">
            <div class="relative w-8 h-8">
                <div class="animate-spin absolute inset-0">
                    <div class="h-full w-full border-3 border-blue-200 border-t-primary rounded-full"></div>
                </div>
            </div>
        </div>
    `;

    const editModal = document.getElementById("editUserModal");
    const formContainer = editModal.querySelector(".p-6");
    
    // Show loading state
    formContainer.innerHTML = modalLoadingSpinner;
    editModal.classList.remove("hidden");

    // Fetch user data
    fetch(`/admin/users/${id}`, {
        headers: {
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
        },
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                // RESTORE the original form HTML
                formContainer.innerHTML = originalEditFormHTML;
                
                // Now populate the restored form fields
                const user = data.data;
                document.getElementById("editId").value = user.id;
                document.getElementById("editFirstName").value = user.first_name;
                document.getElementById("editLastName").value = user.last_name;
                document.getElementById("editMiddleName").value = user.middle_name || "";
                document.getElementById("editNameSuffix").value = user.name_suffix || "";
                document.getElementById("editEmail").value = user.email;
                document.getElementById("editRole").value = user.role;
                document.getElementById("editIsActive").value = user.is_active ? 1 : 0;

                if (user.department_id) {
                    document.getElementById("editDepartmentId").value = user.department_id;
                }

                toggleEditDepartmentField();
                clearFormErrors("edit");
                
                // Re-attach event listener to the restored form
                const editForm = document.getElementById("editUserForm");
                if (editForm) {
                    editForm.removeEventListener("submit", handleEditUser);
                    editForm.addEventListener("submit", handleEditUser);
                }
            } else {
                showToast("Failed to load user", "error");
                closeEditUserModal();
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            showToast("Failed to load user", "error");
            closeEditUserModal();
        });
}

function closeEditUserModal() {
    document.getElementById("editUserModal").classList.add("hidden");
}

// Handle form submissions
function handleAddUser(e) {
    e.preventDefault();

    const form = document.getElementById("addUserForm");
    const submitBtn = document.getElementById("addSubmitBtn");

    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = `
        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Creating...
    `;

    const formData = new FormData(form);

    fetch("/admin/users", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN":
                document.querySelector('meta[name="csrf-token"]')?.content || "",
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
        },
        body: formData,
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                showToast(
                    "User created successfully. Credentials sent via email.",
                    "success"
                );
                closeAddUserModal();
                loadStats();
                loadUsers(1);
            } else if (data.errors) {
                displayFormErrors("add", data.errors);
                showToast("Validation failed", "error");
            } else {
                showToast(data.message || "Failed to create user", "error");
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            showToast("Failed to create user", "error");
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
}

function handleEditUser(e) {
    e.preventDefault();

    const form = document.getElementById("editUserForm");
    const submitBtn = document.getElementById("editSubmitBtn");
    const id = document.getElementById("editId").value;

    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = `
        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Saving...
    `;

    const formData = new FormData(form);

    fetch(`/admin/users/${id}`, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN":
                document.querySelector('meta[name="csrf-token"]')?.content || "",
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
        },
        body: formData,
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                showToast(data.message, "success");
                closeEditUserModal();
                loadUsers(currentPage);
            } else if (data.errors) {
                displayFormErrors("edit", data.errors);
                showToast("Validation failed", "error");
            } else {
                showToast(data.message || "Failed to update user", "error");
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            showToast("Failed to update user", "error");
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        });
}

// Filter and pagination
function filterUsers() {
    loadUsers(1);
}

function updatePagination(pagination) {
    const total = pagination.total;
    const perPage = pagination.per_page;
    const currentPage = pagination.current_page;
    const lastPage = pagination.total_pages;

    const showingStart = (currentPage - 1) * perPage + 1;
    const showingEnd = Math.min(currentPage * perPage, total);

    document.getElementById("showingStart").textContent = showingStart;
    document.getElementById("showingEnd").textContent = showingEnd;
    document.getElementById("showingTotal").textContent = total;

    let paginationHTML = "";

    if (currentPage > 1) {
        paginationHTML += `
            <button onclick="loadUsers(${
                currentPage - 1
            })" class="px-3 py-1 text-sm bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition">
                ← Previous
            </button>
        `;
    }

    for (
        let i = Math.max(1, currentPage - 2);
        i <= Math.min(lastPage, currentPage + 2);
        i++
    ) {
        if (i === currentPage) {
            paginationHTML += `
                <button class="px-3 py-1 text-sm bg-primary text-white rounded-md font-medium">
                    ${i}
                </button>
            `;
        } else {
            paginationHTML += `
                <button onclick="loadUsers(${i})" class="px-3 py-1 text-sm bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition">
                    ${i}
                </button>
            `;
        }
    }

    if (currentPage < lastPage) {
        paginationHTML += `
            <button onclick="loadUsers(${
                currentPage + 1
            })" class="px-3 py-1 text-sm bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 transition">
                Next →
            </button>
        `;
    }

    document.getElementById("paginationContainer").innerHTML = paginationHTML;
}

function toggleDepartmentField() {
    const role = document.getElementById("addRole").value;
    const deptDiv = document.getElementById("departmentFieldDiv");
    const deptInput = document.getElementById("addDepartmentId");
    const studentIdDiv = document.getElementById("studentIdFieldDiv");
    const studentIdInput = document.getElementById("addStudentId");

    if (role === "student") {
        deptDiv.classList.remove("hidden");
        deptInput.required = true;
        studentIdDiv.classList.remove("hidden");
        studentIdInput.required = true;
    } else {
        deptDiv.classList.add("hidden");
        deptInput.required = false;
        deptInput.value = "";
        studentIdDiv.classList.add("hidden");
        studentIdInput.required = false;
        studentIdInput.value = "";
    }
}

function toggleEditDepartmentField() {
    const role = document.getElementById("editRole").value;
    const div = document.getElementById("editDepartmentFieldDiv");
    const input = document.getElementById("editDepartmentId");

    if (role === "student") {
        div.classList.remove("hidden");
        input.required = true;
    } else {
        div.classList.add("hidden");
        input.required = false;
        input.value = "";
    }
}

function getRoleColor(role) {
    const colors = {
        admin: "bg-red-100 text-red-800",
        staff: "bg-green-100 text-green-800",
        partner: "bg-orange-100 text-orange-800",
        student: "bg-purple-100 text-purple-800",
        alumni: "bg-pink-100 text-pink-800",
    };
    return colors[role] || "bg-gray-100 text-gray-800";
}

function capitalizeFirst(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}

function clearFormErrors(type) {
    document.querySelectorAll(`#${type}UserForm .text-red-500`).forEach((el) => {
        el.classList.add("hidden");
        el.textContent = "";
    });

    document.querySelectorAll(`#${type}UserForm input, #${type}UserForm select`).forEach((el) => {
        el.classList.remove("border-red-500");
    });
}

function displayFormErrors(type, errors) {
    clearFormErrors(type);

    Object.keys(errors).forEach((field) => {
        const errorElement = document.getElementById(
            `${type}${field.replace(/_/g, "").charAt(0).toUpperCase() + field.slice(1)}Error`
        );
        if (errorElement) {
            errorElement.textContent = errors[field][0];
            errorElement.classList.remove("hidden");
        }
    });
}

function showToast(message, type = "info") {
    const toastContainer = document.getElementById("toastContainer");

    const toastClass = {
        success: "bg-green-500",
        error: "bg-red-500",
        warning: "bg-yellow-500",
        info: "bg-blue-500",
    }[type] || "bg-blue-500";

    const icons = {
        success: '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>',
        error: '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>',
        warning: '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>',
        info: '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>',
    };

    const toast = document.createElement("div");
    toast.className = `${toastClass} text-white px-6 py-4 rounded-lg shadow-lg mb-3 flex items-center justify-between animate-pulse`;
    toast.innerHTML = `
        <div class="flex items-center space-x-3">
            ${icons[type] || icons.info}
            <span class="font-medium">${message}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="ml-4 text-white hover:text-gray-200 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    `;

    toastContainer.appendChild(toast);

    setTimeout(() => {
        toast.classList.remove("animate-pulse");
        toast.style.opacity = "0";
        toast.style.transition = "opacity 0.3s ease";
        setTimeout(() => toast.remove(), 300);
    }, 5000);
}
