let currentPage = 1;
let currentDeleteId = null;

// Initialize
document.addEventListener("DOMContentLoaded", function () {
    loadDepartments();
    loadStats();
    loadUsers();
    setupEventListeners();
});

// Setup event listeners
function setupEventListeners() {
    document
        .getElementById("addUserForm")
        .addEventListener("submit", handleAddUser);
    document
        .getElementById("editUserForm")
        .addEventListener("submit", handleEditUser);
    document
        .getElementById("searchInput")
        .addEventListener("keyup", function () {
            clearTimeout(window.searchTimeout);
            window.searchTimeout = setTimeout(() => filterUsers(), 500);
        });
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

                data.data.forEach((dept) => {
                    addSelect.innerHTML += `<option value="${dept.id}">${dept.title}</option>`;
                    editSelect.innerHTML += `<option value="${dept.id}">${dept.title}</option>`;
                });
            }
        });
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
                document.getElementById("statTotal").textContent =
                    data.data.total;
                document.getElementById("statAdmin").textContent =
                    data.data.admin;
                document.getElementById("statStaff").textContent =
                    data.data.staff;
                document.getElementById("statPartner").textContent =
                    data.data.partner;
                document.getElementById("statStudent").textContent =
                    data.data.student;
                document.getElementById("statAlumni").textContent =
                    data.data.alumni;
            }
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
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            showToast("Failed to load users", "error");
        });
}

// Display users
function displayUsers(users) {
    const tbody = document.getElementById("userTableBody");

    if (users.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                    No users found
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
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">${nameDisplay}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    ${user.email}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${getRoleColor(
                        user.role
                    )} ">
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
                    })" class="text-primary hover:text-blue-700 font-medium">
                        Edit
                    </button>
                    <button onclick="openDeleteConfirmModal(${user.id}, '${
                user.first_name
            } ${
                user.last_name
            }')" class="text-red-600 hidden hover:text-red-700 font-medium">
                        Delete
                    </button>
                </td>
            </tr>
        `;
        })
        .join("");
}

// Modal functions
function openAddUserModal() {
    clearFormErrors("add");
    document.getElementById("addUserForm").reset();
    document.getElementById("addUserModal").classList.remove("hidden");
}

function closeAddUserModal() {
    document.getElementById("addUserModal").classList.add("hidden");
}

function openEditUserModal(id) {
    fetch(`/admin/users/${id}`, {
        headers: {
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
        },
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                const user = data.data;
                document.getElementById("editId").value = user.id;
                document.getElementById("editFirstName").value =
                    user.first_name;
                document.getElementById("editLastName").value = user.last_name;
                document.getElementById("editMiddleName").value =
                    user.middle_name || "";
                document.getElementById("editNameSuffix").value =
                    user.name_suffix || "";
                document.getElementById("editEmail").value = user.email;
                document.getElementById("editRole").value = user.role;
                document.getElementById("editIsActive").value = user.is_active
                    ? 1
                    : 0;

                if (user.department_id) {
                    document.getElementById("editDepartmentId").value =
                        user.department_id;
                }

                toggleEditDepartmentField();
                clearFormErrors("edit");
                document
                    .getElementById("editUserModal")
                    .classList.remove("hidden");
            }
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
    submitBtn.disabled = true;

    const formData = new FormData(form);

    fetch("/admin/users", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN":
                document.querySelector('meta[name="csrf-token"]')?.content ||
                "",
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
        });
}

function handleEditUser(e) {
    e.preventDefault();

    const form = document.getElementById("editUserForm");
    const submitBtn = document.getElementById("editSubmitBtn");
    const id = document.getElementById("editId").value;
    submitBtn.disabled = true;

    const formData = new FormData(form);

    fetch(`/admin/users/${id}`, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN":
                document.querySelector('meta[name="csrf-token"]')?.content ||
                "",
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
        },
        body: formData,
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                showToast("User updated successfully", "success");
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
        });
}

// Delete functions
function openDeleteConfirmModal(id, name) {
    currentDeleteId = id;
    document.getElementById(
        "deleteMessage"
    ).textContent = `Are you sure you want to delete "${name}"? This action cannot be undone.`;
    document.getElementById("deleteConfirmModal").classList.remove("hidden");
}

function closeDeleteConfirmModal() {
    document.getElementById("deleteConfirmModal").classList.add("hidden");
    currentDeleteId = null;
}

function confirmDelete() {
    if (!currentDeleteId) return;

    const deleteBtn = document.querySelector(
        '#deleteConfirmModal button[onclick="confirmDelete()"]'
    );
    deleteBtn.disabled = true;

    fetch(`/admin/users/${currentDeleteId}`, {
        method: "DELETE",
        headers: {
            "X-CSRF-TOKEN":
                document.querySelector('meta[name="csrf-token"]')?.content ||
                "",
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
        },
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                showToast("User deleted successfully", "success");
                closeDeleteConfirmModal();
                loadStats();
                loadUsers(currentPage);
            } else {
                showToast(data.message || "Failed to delete user", "error");
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            showToast("Failed to delete user", "error");
        })
        .finally(() => {
            deleteBtn.disabled = false;
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
            })" class="px-3 py-1 text-sm bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
                Previous
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
                <button class="px-3 py-1 text-sm bg-primary text-white rounded-md">
                    ${i}
                </button>
            `;
        } else {
            paginationHTML += `
                <button onclick="loadUsers(${i})" class="px-3 py-1 text-sm bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
                    ${i}
                </button>
            `;
        }
    }

    if (currentPage < lastPage) {
        paginationHTML += `
            <button onclick="loadUsers(${
                currentPage + 1
            })" class="px-3 py-1 text-sm bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
                Next
            </button>
        `;
    }

    document.getElementById("paginationContainer").innerHTML = paginationHTML;
}

function toggleDepartmentField() {
    const role = document.getElementById('addRole').value;
    const deptDiv = document.getElementById('departmentFieldDiv');
    const deptInput = document.getElementById('addDepartmentId');
    const studentIdDiv = document.getElementById('studentIdFieldDiv');
    const studentIdInput = document.getElementById('addStudentId');

    if (role === 'student') {
        deptDiv.classList.remove('hidden');
        deptInput.required = true;
        studentIdDiv.classList.remove('hidden');
        studentIdInput.required = true;
    } else {
        deptDiv.classList.add('hidden');
        deptInput.required = false;
        deptInput.value = '';
        studentIdDiv.classList.add('hidden');
        studentIdInput.required = false;
        studentIdInput.value = '';
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
    document.querySelectorAll(`#${type}UserForm .text-red-500`).forEach(el => {
        el.classList.add('hidden');
        el.textContent = '';
    });

    // Also clear any input/select error states
    document.querySelectorAll(`#${type}UserForm input, #${type}UserForm select`).forEach(el => {
        el.classList.remove('border-red-500');
    });
}

function displayFormErrors(type, errors) {
    clearFormErrors(type);

    Object.keys(errors).forEach((field) => {
        const errorElement = document.getElementById(
            `${type}${
                field.replace("_", "").charAt(0).toUpperCase() + field.slice(1)
            }Error`
        );
        if (errorElement) {
            errorElement.textContent = errors[field][0];
            errorElement.classList.remove("hidden");
        }
    });
}

function showToast(message, type = "info") {
    const toastContainer = document.getElementById("toastContainer");

    const toastClass =
        {
            success: "bg-green-500",
            error: "bg-red-500",
            info: "bg-blue-500",
        }[type] || "bg-blue-500";

    const toast = document.createElement("div");
    toast.className = `${toastClass} text-white px-6 py-3 rounded-lg shadow-lg mb-2 flex items-center justify-between`;
    toast.innerHTML = `
        <span>${message}</span>
        <button onclick="this.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    `;

    toastContainer.appendChild(toast);

    setTimeout(() => {
        toast.remove();
    }, 5000);
}
