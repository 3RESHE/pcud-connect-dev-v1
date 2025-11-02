// Sample department data (will be replaced with actual data from backend)
let departments = [
    {
        id: 1,
        title: "College of Arts and Sciences",
        code: "CAS",
        created: "Jan 1, 2020",
    },
    {
        id: 2,
        title: "College of Business and Accountancy",
        code: "CBA",
        created: "Jan 1, 2020",
    },
    {
        id: 3,
        title: "College of Criminal Justice",
        code: "CCJ",
        created: "Jan 1, 2020",
    },
    {
        id: 4,
        title: "College of Engineering and Technology",
        code: "CET",
        created: "Jan 1, 2020",
    },
    {
        id: 5,
        title: "College of Education",
        code: "COED",
        created: "Jan 1, 2020",
    },
    {
        id: 6,
        title: "College of Informatics",
        code: "COI",
        created: "Jan 1, 2020",
    },
    {
        id: 7,
        title: "College of Hospitality and Tourism Management",
        code: "CHTM",
        created: "Jan 1, 2020",
    },
    {
        id: 8,
        title: "College of Nursing and Health Sciences",
        code: "CNHS",
        created: "Jan 1, 2020",
    },
    {
        id: 9,
        title: "College of Social Work",
        code: "CSW",
        created: "Jan 1, 2020",
    },
];

// Render department table
function renderDepartmentTable() {
    const tbody = document.getElementById("departmentTableBody");
    tbody.innerHTML = "";

    departments.forEach((dept) => {
        const row = document.createElement("tr");
        row.classList.add("hover:bg-gray-50");
        row.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${dept.title}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${dept.code}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${dept.created}</td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <button onclick="editDepartment(${dept.id})" class="text-yellow-600 hover:text-yellow-900 mr-4">
                    <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit
                </button>
                <button onclick="deleteDepartment(${dept.id})" class="text-red-600 hover:text-red-900">
                    <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Delete
                </button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

// Add Department Modal Functions
function openAddDepartmentModal() {
    document.getElementById("addDepartmentModal").classList.remove("hidden");
}

function closeAddDepartmentModal() {
    document.getElementById("addDepartmentModal").classList.add("hidden");
    document.getElementById("addDepartmentForm").reset();
}

// Edit Department Modal Functions
function openEditDepartmentModal(dept) {
    const form = document.getElementById("editDepartmentForm");
    form.querySelector("[name='id']").value = dept.id;
    form.querySelector("[name='title']").value = dept.title;
    form.querySelector("[name='code']").value = dept.code;
    document.getElementById("editDepartmentModal").classList.remove("hidden");
}

function closeEditDepartmentModal() {
    document.getElementById("editDepartmentModal").classList.add("hidden");
    document.getElementById("editDepartmentForm").reset();
}

// Department CRUD Operations (blank - functionality later)
function editDepartment(id) {
    const dept = departments.find((d) => d.id === id);
    if (dept) {
        openEditDepartmentModal(dept);
    }
}

function deleteDepartment(id) {
    // Blank implementation - functionality later
    if (confirm("Are you sure you want to delete this department?")) {
        // Will handle deletion via backend later
        console.log("Delete department:", id);
    }
}

// Form Submissions (blank - functionality later)
document.getElementById("addDepartmentForm")?.addEventListener("submit", function (e) {
    e.preventDefault();
    // Blank implementation - functionality later
    console.log("Add department form submitted");
});

document.getElementById("editDepartmentForm")?.addEventListener("submit", function (e) {
    e.preventDefault();
    // Blank implementation - functionality later
    console.log("Edit department form submitted");
});

// Initialize table on page load
document.addEventListener("DOMContentLoaded", function() {
    renderDepartmentTable();
});
