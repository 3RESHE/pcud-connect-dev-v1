// ===== GLOBAL VARIABLES =====
let currentPage = 1;
let currentDeleteId = null;

// ===== INITIALIZE =====
document.addEventListener("DOMContentLoaded", function () {
    loadDepartments();
    setupEventListeners();
});

// ===== EVENT LISTENERS =====
function setupEventListeners() {
    // Add Department Form
    document
        .getElementById("addDepartmentForm")
        .addEventListener("submit", handleAddDepartment);

    // Edit Department Form
    document
        .getElementById("editDepartmentForm")
        .addEventListener("submit", handleEditDepartment);

    // Auto uppercase code input
    document.getElementById("addCode").addEventListener("input", function () {
        this.value = this.value.toUpperCase();
    });
    document.getElementById("editCode").addEventListener("input", function () {
        this.value = this.value.toUpperCase();
    });
}

// ===== LOAD DEPARTMENTS =====
function loadDepartments(page = 1) {
    currentPage = page;

    fetch(`/admin/departments/all?page=${page}`, {
        method: "GET",
        headers: {
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
        },
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                displayDepartments(data.data);
                updatePagination(data.pagination);
            } else {
                showToast("Error loading departments", "error");
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            showToast("Failed to load departments", "error");
        });
}

// ===== DISPLAY DEPARTMENTS =====
function displayDepartments(departments) {
    const tbody = document.getElementById("departmentTableBody");

    if (departments.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <p>No departments found. Create your first department!</p>
                </td>
            </tr>
        `;
        return;
    }

    tbody.innerHTML = departments
        .map(
            (dept) => `
        <tr>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                ${escapeHtml(dept.title)}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                    ${escapeHtml(dept.code)}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                    ${dept.students_count || 0}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                ${new Date(dept.created_at).toLocaleDateString()}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                <button
                    onclick="openEditDepartmentModal(${dept.id})"
                    class="text-primary hover:text-blue-700 font-medium"
                >
                    Edit
                </button>
                <!-- ✅ NEW: Export Students Button -->
                <a href="/admin/departments/${dept.id}/export-students"
                   class="text-green-600 hover:text-green-700 font-medium inline-block"
                   title="Export students from this department">
                    Export
                </a>
                <button
                    onclick="openDeleteConfirmModal(${dept.id}, '${escapeHtml(
                dept.title
            )}')"
                    class="text-red-600 hover:text-red-700 font-medium"
                >
                    Delete
                </button>
            </td>
        </tr>
    `
        )
        .join("");
}

// ===== PAGINATION =====
function updatePagination(pagination) {
    const total = pagination.total;
    const perPage = pagination.per_page;
    const currentPage = pagination.current_page;
    const lastPage = pagination.total_pages;

    // Update showing text
    const showingStart = (currentPage - 1) * perPage + 1;
    const showingEnd = Math.min(currentPage * perPage, total);

    document.getElementById("showingStart").textContent = showingStart;
    document.getElementById("showingEnd").textContent = showingEnd;
    document.getElementById("showingTotal").textContent = total;

    // Generate pagination buttons
    let paginationHTML = "";

    if (currentPage > 1) {
        paginationHTML += `
            <button onclick="loadDepartments(${
                currentPage - 1
            })" class="px-3 py-1 text-sm bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
                Previous
            </button>
        `;
    }

    // Page numbers
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
                <button onclick="loadDepartments(${i})" class="px-3 py-1 text-sm bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
                    ${i}
                </button>
            `;
        }
    }

    if (currentPage < lastPage) {
        paginationHTML += `
            <button onclick="loadDepartments(${
                currentPage + 1
            })" class="px-3 py-1 text-sm bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
                Next
            </button>
        `;
    }

    document.getElementById("paginationContainer").innerHTML = paginationHTML;
}

// ===== MODALS =====
function openAddDepartmentModal() {
    clearFormErrors("add");
    document.getElementById("addDepartmentForm").reset();
    document.getElementById("addDepartmentModal").classList.remove("hidden");
}

function closeAddDepartmentModal() {
    document.getElementById("addDepartmentModal").classList.add("hidden");
}

function openEditDepartmentModal(id) {
    clearFormErrors("edit");

    fetch(`/admin/departments/${id}`, {
        method: "GET",
        headers: {
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
        },
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                document.getElementById("editId").value = data.data.id;
                document.getElementById("editTitle").value = data.data.title;
                document.getElementById("editCode").value = data.data.code;
                document
                    .getElementById("editDepartmentModal")
                    .classList.remove("hidden");
            } else {
                showToast("Failed to load department", "error");
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            showToast("Failed to load department", "error");
        });
}

function closeEditDepartmentModal() {
    document.getElementById("editDepartmentModal").classList.add("hidden");
}

function openDeleteConfirmModal(id, title) {
    currentDeleteId = id;
    document.getElementById(
        "deleteMessage"
    ).textContent = `Are you sure you want to delete "${title}"? This action cannot be undone.`;
    document.getElementById("deleteConfirmModal").classList.remove("hidden");
}

function closeDeleteConfirmModal() {
    document.getElementById("deleteConfirmModal").classList.add("hidden");
    currentDeleteId = null;
}

// ===== FORM SUBMISSION =====
function handleAddDepartment(e) {
    e.preventDefault();

    const form = document.getElementById("addDepartmentForm");
    const submitBtn = document.getElementById("addSubmitBtn");
    submitBtn.disabled = true;

    // Get form values
    const title = document.getElementById("addTitle").value;
    const code = document.getElementById("addCode").value;

    const payload = {
        title: title,
        code: code,
    };

    fetch("/admin/departments", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN":
                document.querySelector('meta[name="csrf-token"]')?.content ||
                "",
            Accept: "application/json",
            "Content-Type": "application/json",
            "X-Requested-With": "XMLHttpRequest",
        },
        body: JSON.stringify(payload),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                showToast("Department created successfully", "success");
                closeAddDepartmentModal();
                loadDepartments(1);
            } else if (data.errors) {
                displayFormErrors("add", data.errors);
                showToast("Validation failed", "error");
            } else {
                showToast(
                    data.message || "Failed to create department",
                    "error"
                );
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            showToast("Failed to create department", "error");
        })
        .finally(() => {
            submitBtn.disabled = false;
        });
}

function handleEditDepartment(e) {
    e.preventDefault();

    const form = document.getElementById("editDepartmentForm");
    const submitBtn = document.getElementById("editSubmitBtn");
    const id = document.getElementById("editId").value;
    submitBtn.disabled = true;

    // Get form values
    const title = document.getElementById("editTitle").value;
    const code = document.getElementById("editCode").value;

    // Create FormData or JSON payload
    const payload = {
        title: title,
        code: code,
        _method: "PUT",
    };

    fetch(`/admin/departments/${id}`, {
        method: "POST", // Laravel accepts POST with _method: PUT
        headers: {
            "X-CSRF-TOKEN":
                document.querySelector('meta[name="csrf-token"]')?.content ||
                "",
            Accept: "application/json",
            "Content-Type": "application/json",
            "X-Requested-With": "XMLHttpRequest",
        },
        body: JSON.stringify(payload),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                showToast("Department updated successfully", "success");
                closeEditDepartmentModal();
                loadDepartments(currentPage);
            } else if (data.errors) {
                displayFormErrors("edit", data.errors);
                showToast("Validation failed", "error");
            } else {
                showToast(
                    data.message || "Failed to update department",
                    "error"
                );
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            showToast("Failed to update department", "error");
        })
        .finally(() => {
            submitBtn.disabled = false;
        });
}

function confirmDelete() {
    if (!currentDeleteId) return;

    const deleteBtn = document.querySelector(
        '#deleteConfirmModal button[onclick="confirmDelete()"]'
    );
    deleteBtn.disabled = true;

    fetch(`/admin/departments/${currentDeleteId}`, {
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
                showToast("Department deleted successfully", "success");
                closeDeleteConfirmModal();
                loadDepartments(currentPage);
            } else {
                showToast(
                    data.message || "Failed to delete department",
                    "error"
                );
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            showToast("Failed to delete department", "error");
        })
        .finally(() => {
            deleteBtn.disabled = false;
        });
}

// ===== HELPER FUNCTIONS =====
function displayFormErrors(type, errors) {
    clearFormErrors(type);

    Object.keys(errors).forEach((field) => {
        const errorElement = document.getElementById(
            `${type}${field.charAt(0).toUpperCase() + field.slice(1)}Error`
        );
        if (errorElement) {
            errorElement.textContent = errors[field][0];
            errorElement.classList.remove("hidden");
        }
    });
}

function clearFormErrors(type) {
    document
        .querySelectorAll(`#${type}DepartmentForm .text-red-500`)
        .forEach((el) => {
            el.classList.add("hidden");
            el.textContent = "";
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

function escapeHtml(text) {
    const map = {
        "&": "&amp;",
        "<": "&lt;",
        ">": "&gt;",
        '"': "&quot;",
        "'": "'",
    };
    return text.replace(/[&<>"']/g, (m) => map[m]);
}
