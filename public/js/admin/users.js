// Modal Management Functions
function openCreateUserModal() {
    document.getElementById("createUserModal").classList.remove("hidden");
}

function closeCreateUserModal() {
    document.getElementById("createUserModal").classList.add("hidden");
    document.getElementById("createUserForm").reset();
    document.getElementById("departmentField").classList.add("hidden");
    document.getElementById("emailNote").classList.add("hidden");
}

function toggleRoleFields() {
    const role = document.getElementById("userRole").value;
    const departmentField = document.getElementById("departmentField");
    const emailNote = document.getElementById("emailNote");

    departmentField.classList.add("hidden");
    emailNote.classList.add("hidden");

    if (role === "student") {
        departmentField.classList.remove("hidden");
    }
    if (role === "admin" || role === "staff" || role === "student") {
        emailNote.classList.remove("hidden");
    }
}

function openEditUserModal(user) {
    const form = document.getElementById("editUserForm");
    form.querySelector("[name='id']").value = user.id;
    form.querySelector("[name='role']").value = user.role;
    form.querySelector("[name='first_name']").value = user.first_name;
    form.querySelector("[name='last_name']").value = user.last_name;
    form.querySelector("[name='middle_name']").value = user.middle_name || "";
    form.querySelector("[name='name_suffix']").value = user.name_suffix || "";
    form.querySelector("[name='email']").value = user.email;
    form.querySelector("[name='department_id']").value = user.department_id || "";
    form.querySelector("[name='is_active']").value = user.is_active ? "1" : "0";
    toggleEditRoleFields();
    document.getElementById("editUserModal").classList.remove("hidden");
}

function closeEditUserModal() {
    document.getElementById("editUserModal").classList.add("hidden");
    document.getElementById("editUserForm").reset();
    document.getElementById("editDepartmentField").classList.add("hidden");
    document.getElementById("editEmailNote").classList.add("hidden");
}

function toggleEditRoleFields() {
    const role = document.getElementById("editUserRole").value;
    const departmentField = document.getElementById("editDepartmentField");
    const emailNote = document.getElementById("editEmailNote");

    departmentField.classList.add("hidden");
    emailNote.classList.add("hidden");

    if (role === "student") {
        departmentField.classList.remove("hidden");
    }
    if (role === "admin" || role === "staff" || role === "student") {
        emailNote.classList.remove("hidden");
    }
}

function openBulkUploadModal() {
    document.getElementById("bulkUploadModal").classList.remove("hidden");
}

function closeBulkUploadModal() {
    document.getElementById("bulkUploadModal").classList.add("hidden");
    document.getElementById("bulkUploadForm").reset();
    document.getElementById("studentDepartmentField").classList.add("hidden");
}

function toggleBulkUploadFields() {
    const uploadType = document.querySelector("#bulkUploadForm [name='upload_type']").value;
    const studentDepartmentField = document.getElementById("studentDepartmentField");
    studentDepartmentField.classList.toggle("hidden", uploadType !== "students");
}

function downloadTemplate() {
    const uploadType = document.querySelector("#bulkUploadForm [name='upload_type']").value;
    // Blank implementation - functionality later
}

function filterUsers(type) {
    // Blank implementation - functionality later
}

function searchUsers(term) {
    // Blank implementation - functionality later
}

function editUser(id) {
    // Blank implementation - functionality later
}

function deleteUser(id) {
    // Blank implementation - functionality later
}
