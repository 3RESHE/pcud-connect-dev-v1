/**
 * ALUMNI PROFILE MANAGEMENT JAVASCRIPT
 * File: public/js/alumni-profile.js
 */

// ===== PHOTO PREVIEW =====
function previewPhoto(event) {
    const file = event.target.files[0];
    if (!file) return;

    if (file.size > 2 * 1024 * 1024) {
        showError("profile_photoError", "File must not exceed 2MB");
        event.target.value = "";
        return;
    }

    const reader = new FileReader();
    reader.onload = function (e) {
        const preview = document.querySelector(
            '[class*="w-20"][class*="h-20"]'
        );
        if (preview && preview.querySelector("svg")) {
            preview.innerHTML = `<img src="${e.target.result}" alt="Preview" class="w-full h-full rounded-full object-cover">`;
        }
    };
    reader.readAsDataURL(file);
}

// ===== EXPERIENCE MANAGEMENT =====
function addExperience() {
    const list = document.getElementById("experiencesList");
    const item = document.createElement("div");
    item.className =
        "experience-item border border-gray-200 rounded-lg p-4 sm:p-6 new-item";
    item.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
            <div class="min-w-0">
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Position</label>
                <input type="text" name="experience_role[]" required class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm">
                <span class="experienceError text-red-500 text-xs mt-1 block hidden"></span>
            </div>
            <div class="min-w-0">
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Organization</label>
                <input type="text" name="experience_org[]" required class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm">
                <span class="experienceError text-red-500 text-xs mt-1 block hidden"></span>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6 mt-4">
            <div class="min-w-0">
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Start Date</label>
                <input type="date" name="experience_start[]" required class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm">
                <span class="experienceError text-red-500 text-xs mt-1 block hidden"></span>
            </div>
            <div class="min-w-0">
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">End Date</label>
                <input type="date" name="experience_end[]" class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm">
                <span class="experienceError text-red-500 text-xs mt-1 block hidden"></span>
            </div>
            <div class="min-w-0">
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Location</label>
                <input type="text" name="experience_location[]" class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm" placeholder="City, Country">
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mt-4">
            <div class="min-w-0">
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Type</label>
                <select name="experience_type[]" required class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm">
                    <option value="">Select Type</option>
                    <option value="full_time">Full-time</option>
                    <option value="part_time">Part-time</option>
                    <option value="internship">Internship</option>
                    <option value="freelance">Freelance</option>
                    <option value="volunteer">Volunteer</option>
                </select>
                <span class="experienceError text-red-500 text-xs mt-1 block hidden"></span>
            </div>
            <div class="min-w-0">
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                    <input type="checkbox" name="experience_current[]" class="mr-2">
                    Currently Working Here?
                </label>
            </div>
        </div>
        <div class="mt-4">
            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea name="experience_desc[]" rows="3" required class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm resize-none"></textarea>
            <span class="experienceError text-red-500 text-xs mt-1 block hidden"></span>
        </div>
        <div class="mt-4 flex justify-end gap-2">
            <button type="button" onclick="submitExperience(this)" class="px-3 py-1 bg-green-600 text-white hover:bg-green-700 text-xs sm:text-sm font-medium rounded">
                Save
            </button>
            <button type="button" onclick="this.closest('.experience-item').remove()" class="px-3 py-1 text-red-600 hover:text-red-800 text-xs sm:text-sm font-medium">
                Delete
            </button>
        </div>
    `;
    list.appendChild(item);
}

function submitExperience(btn) {
    const item = btn.closest(".experience-item");
    const isNew = item.classList.contains("new-item");
    const id = item.dataset.id;

    item.querySelectorAll(".experienceError").forEach((e) => {
        e.classList.add("hidden");
        e.textContent = "";
    });
    item.querySelectorAll("input, select, textarea").forEach((el) => {
        el.classList.remove("border-red-500");
    });

    const data = {
        experience_role: item.querySelector('input[name="experience_role[]"]')
            .value,
        experience_org: item.querySelector('input[name="experience_org[]"]')
            .value,
        experience_type: item.querySelector('select[name="experience_type[]"]')
            .value,
        experience_start: item.querySelector('input[name="experience_start[]"]')
            .value,
        experience_end: item.querySelector('input[name="experience_end[]"]')
            .value,
        experience_location: item.querySelector(
            'input[name="experience_location[]"]'
        ).value,
        experience_desc: item.querySelector(
            'textarea[name="experience_desc[]"]'
        ).value,
    };

    let url;
    let method;

    if (isNew || !id) {
        url = window.routes.experiencesStore;
        method = "POST";
    } else {
        url = `/alumni/experiences/${id}`;
        method = "PUT";
    }

    console.log("Submit Experience:", { url, method, data });

    fetch(url, {
        method: method,
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                .content,
            Accept: "application/json",
        },
        body: JSON.stringify(data),
    })
        .then((r) => r.json())
        .then((d) => {
            console.log("Response:", d);
            if (d.success) {
                showToast("✅ " + d.message, "success");
                if (isNew) item.classList.remove("new-item");
                item.dataset.id = d.experience?.id;
            } else {
                showToast("❌ " + (d.message || "Failed to save"), "error");
                if (d.errors) {
                    console.log("Experience Errors:", d.errors);
                    displayExperienceErrors(item, d.errors);
                }
            }
        })
        .catch((e) => {
            console.error("Fetch Error:", e);
            showToast("❌ Error: " + e.message, "error");
        });
}

function displayExperienceErrors(item, errors) {
    const fieldMap = {
        experience_role: 'input[name="experience_role[]"]',
        experience_org: 'input[name="experience_org[]"]',
        experience_type: 'select[name="experience_type[]"]',
        experience_start: 'input[name="experience_start[]"]',
        experience_end: 'input[name="experience_end[]"]',
        experience_desc: 'textarea[name="experience_desc[]"]',
    };

    Object.keys(errors).forEach((field) => {
        const fieldSelector = fieldMap[field];
        if (fieldSelector) {
            const input = item.querySelector(fieldSelector);
            if (input) {
                const errorEl =
                    input.parentElement.querySelector(".experienceError");
                if (errorEl) {
                    errorEl.textContent = errors[field][0];
                    errorEl.classList.remove("hidden");
                }
                input.classList.add("border-red-500");
                input.addEventListener(
                    "input",
                    function () {
                        this.classList.remove("border-red-500");
                        const err =
                            this.parentElement.querySelector(
                                ".experienceError"
                            );
                        if (err) err.classList.add("hidden");
                    },
                    { once: true }
                );
            }
        }
    });
}

function deleteExperience(btn, id) {
    if (!confirm("Delete this experience?")) return;

    fetch(`/alumni/experiences/${id}`, {
        method: "DELETE",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                .content,
        },
    })
        .then((r) => r.json())
        .then((d) => {
            if (d.success) {
                btn.closest(".experience-item").remove();
                showToast("✅ Experience deleted!", "success");
            } else showToast("❌ " + d.message, "error");
        });
}

// ===== PROJECT MANAGEMENT =====
function addProject() {
    const list = document.getElementById("projectsList");
    const item = document.createElement("div");
    item.className =
        "project-item border border-gray-200 rounded-lg p-4 sm:p-6 new-item";
    item.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
            <div class="min-w-0">
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Project Title</label>
                <input type="text" name="project_title[]" required class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm">
                <span class="projectError text-red-500 text-xs mt-1 block hidden"></span>
            </div>
            <div class="min-w-0">
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Project URL</label>
                <input type="url" name="project_url[]" class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm" placeholder="https://...">
                <span class="projectError text-red-500 text-xs mt-1 block hidden"></span>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mt-4">
            <div class="min-w-0">
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Start Date</label>
                <input type="date" name="project_start[]" required class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm">
                <span class="projectError text-red-500 text-xs mt-1 block hidden"></span>
            </div>
            <div class="min-w-0">
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">End Date</label>
                <input type="date" name="project_end[]" class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm">
                <span class="projectError text-red-500 text-xs mt-1 block hidden"></span>
            </div>
        </div>
        <div class="mt-4">
            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea name="project_desc[]" rows="3" required class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm resize-none"></textarea>
            <span class="projectError text-red-500 text-xs mt-1 block hidden"></span>
        </div>
        <div class="mt-4 flex justify-end gap-2">
            <button type="button" onclick="submitProject(this)" class="px-3 py-1 bg-green-600 text-white hover:bg-green-700 text-xs sm:text-sm font-medium rounded">
                Save
            </button>
            <button type="button" onclick="this.closest('.project-item').remove()" class="px-3 py-1 text-red-600 hover:text-red-800 text-xs sm:text-sm font-medium">
                Delete
            </button>
        </div>
    `;
    list.appendChild(item);
}

function submitProject(btn) {
    const item = btn.closest(".project-item");
    const isNew = item.classList.contains("new-item");
    const id = item.dataset.id;

    item.querySelectorAll(".projectError").forEach((e) => {
        e.classList.add("hidden");
        e.textContent = "";
    });
    item.querySelectorAll("input, select, textarea").forEach((el) => {
        el.classList.remove("border-red-500");
    });

    const data = {
        project_title: item.querySelector('input[name="project_title[]"]')
            .value,
        project_url: item.querySelector('input[name="project_url[]"]').value,
        project_start: item.querySelector('input[name="project_start[]"]')
            .value,
        project_end: item.querySelector('input[name="project_end[]"]').value,
        project_desc: item.querySelector('textarea[name="project_desc[]"]')
            .value,
    };

    let url;
    let method;

    if (isNew || !id) {
        url = window.routes.projectsStore;
        method = "POST";
    } else {
        url = `/alumni/projects/${id}`;
        method = "PUT";
    }

    console.log("Submit Project:", { url, method, data });

    fetch(url, {
        method: method,
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                .content,
            Accept: "application/json",
        },
        body: JSON.stringify(data),
    })
        .then((r) => r.json())
        .then((d) => {
            console.log("Response:", d);
            if (d.success) {
                showToast("✅ " + d.message, "success");
                if (isNew) item.classList.remove("new-item");
                item.dataset.id = d.project?.id;
            } else {
                showToast("❌ " + (d.message || "Failed to save"), "error");
                if (d.errors) {
                    console.log("Project Errors:", d.errors);
                    displayProjectErrors(item, d.errors);
                }
            }
        })
        .catch((e) => {
            console.error("Fetch Error:", e);
            showToast("❌ Error: " + e.message, "error");
        });
}

function displayProjectErrors(item, errors) {
    const fieldMap = {
        project_title: 'input[name="project_title[]"]',
        project_url: 'input[name="project_url[]"]',
        project_start: 'input[name="project_start[]"]',
        project_end: 'input[name="project_end[]"]',
        project_desc: 'textarea[name="project_desc[]"]',
    };

    Object.keys(errors).forEach((field) => {
        const fieldSelector = fieldMap[field];
        if (fieldSelector) {
            const input = item.querySelector(fieldSelector);
            if (input) {
                const errorEl =
                    input.parentElement.querySelector(".projectError");
                if (errorEl) {
                    errorEl.textContent = errors[field][0];
                    errorEl.classList.remove("hidden");
                }
                input.classList.add("border-red-500");
                input.addEventListener(
                    "input",
                    function () {
                        this.classList.remove("border-red-500");
                        const err =
                            this.parentElement.querySelector(".projectError");
                        if (err) err.classList.add("hidden");
                    },
                    { once: true }
                );
            }
        }
    });
}

function deleteProject(btn, id) {
    if (!confirm("Delete this project?")) return;

    fetch(`/alumni/projects/${id}`, {
        method: "DELETE",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                .content,
        },
    })
        .then((r) => r.json())
        .then((d) => {
            if (d.success) {
                btn.closest(".project-item").remove();
                showToast("✅ Project deleted!", "success");
            } else showToast("❌ " + d.message, "error");
        });
}

// ===== PROFILE FORM SUBMISSION =====
document
    .getElementById("profileForm")
    ?.addEventListener("submit", async function (e) {
        e.preventDefault();

        const submitBtn = document.getElementById("submitBtn");
        submitBtn.disabled = true;
        const originalText = submitBtn.textContent;
        submitBtn.textContent = "Saving...";

        clearAllErrors();

        const formData = new FormData(this);

        try {
            const response = await fetch(window.routes.profileUpdate, {
                method: "POST",
                headers: {
                    Accept: "application/json",
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                },
                body: formData,
            });

            console.log("Response Status:", response.status);

            const responseText = await response.text();
            console.log("Response Text:", responseText.substring(0, 500));

            let data;
            try {
                data = JSON.parse(responseText);
            } catch (e) {
                console.error("JSON Parse Error:", e);
                showErrorAlert("❌ Server returned invalid response");
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
                return;
            }

            console.log("Parsed Data:", data);

            if (response.ok && data.success) {
                showToast("✅ " + data.message, "success");
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 1500);
            } else {
                showErrorAlert(data.message || "Failed to update profile");
                if (data.errors) {
                    console.log("Validation Errors:", data.errors);
                    displayErrors(data.errors);
                }
            }
        } catch (error) {
            console.error("Fetch Error:", error);
            showErrorAlert("❌ Network error: " + error.message);
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        }
    });

// ===== ERROR HANDLING =====
function showErrorAlert(message) {
    const alertContainer = document.createElement("div");
    alertContainer.className =
        "mb-4 sm:mb-6 bg-red-50 border-l-4 border-red-400 p-3 sm:p-4 rounded-r";
    alertContainer.id = "errorAlert";
    alertContainer.innerHTML = `
        <div class="flex gap-3">
            <svg class="w-5 h-5 text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0-6a4 4 0 110 8 4 4 0 010-8zm0-2a6 6 0 11-1.5.15"></path>
            </svg>
            <div class="flex-1 min-w-0">
                <p class="text-xs sm:text-sm text-red-800 break-words"><strong>Error:</strong> ${message}</p>
                <button onclick="document.getElementById('errorAlert').remove()" class="text-red-600 hover:text-red-800 text-xs mt-2 underline">Dismiss</button>
            </div>
        </div>
    `;

    const form = document.getElementById("profileForm");
    if (form) {
        form.parentElement.insertBefore(alertContainer, form);
    }

    alertContainer.scrollIntoView({
        behavior: "smooth",
        block: "start",
    });
}

function clearAllErrors() {
    const errorAlert = document.getElementById("errorAlert");
    if (errorAlert) {
        errorAlert.remove();
    }

    document.querySelectorAll('[id$="Error"]').forEach((el) => {
        el.classList.add("hidden");
        el.textContent = "";
    });
}

function displayErrors(errors) {
    Object.keys(errors).forEach((field) => {
        const errorEl = document.getElementById(field + "Error");
        if (errorEl) {
            errorEl.textContent = errors[field][0];
            errorEl.classList.remove("hidden");

            if (!document.getElementById("errorAlert")) {
                errorEl.parentElement.scrollIntoView({
                    behavior: "smooth",
                    block: "center",
                });
            }
        }
    });
}

function showError(elementId, message) {
    const element = document.getElementById(elementId);
    if (element) {
        element.textContent = message;
        element.classList.remove("hidden");
    }
}

function showToast(message, type = "info") {
    const container = document.getElementById("toastContainer");
    const bgColor =
        type === "success"
            ? "bg-green-500"
            : type === "error"
            ? "bg-red-500"
            : "bg-blue-500";

    const toast = document.createElement("div");
    toast.className = `${bgColor} text-white px-4 py-3 rounded-lg shadow-lg mb-2 flex items-center justify-between gap-2 text-sm`;
    toast.innerHTML = `
        <span class="break-words">${message}</span>
        <button onclick="this.parentElement.remove()" class="text-white hover:text-gray-200 flex-shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    `;

    if (container) {
        container.appendChild(toast);
        setTimeout(() => toast.remove(), 5000);
    }
}

// ===== RESUME UPLOAD =====
function handleResumeUpload(event) {
    const files = event.target.files;
    const list = document.getElementById("newResumesList");

    for (let file of files) {
        if (file.size > 5 * 1024 * 1024) {
            showToast(`❌ ${file.name} exceeds 5MB limit`, "error");
            continue;
        }

        const item = document.createElement("div");
        item.className =
            "flex items-center justify-between bg-blue-50 p-3 rounded-lg";
        item.innerHTML = `
            <div class="flex items-center gap-2 min-w-0 flex-1">
                <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M8 16.5a1 1 0 11-2 0 1 1 0 012 0zM15 7H4V5h11v2zM15 11H4V9h11v2z"></path>
                </svg>
                <span class="text-xs sm:text-sm text-blue-900 break-all">${file.name}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800 text-sm font-medium flex-shrink-0 ml-2">
                ✕
            </button>
        `;
        list.appendChild(item);
    }
}

// ===== CERTIFICATION UPLOAD =====
function handleCertificationUpload(event) {
    const files = event.target.files;
    const list = document.getElementById("newCertificationsList");

    for (let file of files) {
        if (file.size > 5 * 1024 * 1024) {
            showToast(`❌ ${file.name} exceeds 5MB limit`, "error");
            continue;
        }

        const item = document.createElement("div");
        item.className =
            "flex items-center justify-between bg-green-50 p-3 rounded-lg";
        item.innerHTML = `
            <div class="flex items-center gap-2 min-w-0 flex-1">
                <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v1h8v-1zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                </svg>
                <span class="text-xs sm:text-sm text-green-900 break-all">${file.name}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800 text-sm font-medium flex-shrink-0 ml-2">
                ✕
            </button>
        `;
        list.appendChild(item);
    }
}

// ===== DELETE EXISTING RESUME =====
function deleteExistingResume(filePath, btn) {
    if (!confirm("Delete this resume?")) return;

    fetch(window.routes.deleteResume, {
        method: "DELETE",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                .content,
            Accept: "application/json",
        },
        body: JSON.stringify({ file_path: filePath }),
    })
        .then((r) => r.json())
        .then((d) => {
            if (d.success) {
                btn.closest("div").remove();
                showToast("✅ Resume deleted!", "success");
            } else {
                showToast("❌ " + d.message, "error");
            }
        })
        .catch((e) => showToast("❌ Error: " + e.message, "error"));
}

// ===== DELETE EXISTING CERTIFICATION =====
function deleteExistingCertification(filePath, btn) {
    if (!confirm("Delete this certification?")) return;

    fetch(window.routes.deleteCertification, {
        method: "DELETE",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                .content,
            Accept: "application/json",
        },
        body: JSON.stringify({ file_path: filePath }),
    })
        .then((r) => r.json())
        .then((d) => {
            if (d.success) {
                btn.closest("div").remove();
                showToast("✅ Certification deleted!", "success");
            } else {
                showToast("❌ " + d.message, "error");
            }
        })
        .catch((e) => showToast("❌ Error: " + e.message, "error"));
}

// ===== DRAG & DROP FOR RESUMES =====
document.getElementById("resumeDropZone")?.addEventListener("dragover", (e) => {
    e.preventDefault();
    e.currentTarget.classList.add("border-blue-500", "bg-blue-50");
});

document
    .getElementById("resumeDropZone")
    ?.addEventListener("dragleave", (e) => {
        e.currentTarget.classList.remove("border-blue-500", "bg-blue-50");
    });

document.getElementById("resumeDropZone")?.addEventListener("drop", (e) => {
    e.preventDefault();
    e.currentTarget.classList.remove("border-blue-500", "bg-blue-50");
    document.getElementById("resumeInput").files = e.dataTransfer.files;
    handleResumeUpload(e);
});

document.getElementById("resumeDropZone")?.addEventListener("click", () => {
    document.getElementById("resumeInput").click();
});

// ===== DRAG & DROP FOR CERTIFICATIONS =====
document
    .getElementById("certificationDropZone")
    ?.addEventListener("dragover", (e) => {
        e.preventDefault();
        e.currentTarget.classList.add("border-green-500", "bg-green-50");
    });

document
    .getElementById("certificationDropZone")
    ?.addEventListener("dragleave", (e) => {
        e.currentTarget.classList.remove("border-green-500", "bg-green-50");
    });

document
    .getElementById("certificationDropZone")
    ?.addEventListener("drop", (e) => {
        e.preventDefault();
        e.currentTarget.classList.remove("border-green-500", "bg-green-50");
        document.getElementById("certificationInput").files =
            e.dataTransfer.files;
        handleCertificationUpload(e);
    });

document
    .getElementById("certificationDropZone")
    ?.addEventListener("click", () => {
        document.getElementById("certificationInput").click();
    });
