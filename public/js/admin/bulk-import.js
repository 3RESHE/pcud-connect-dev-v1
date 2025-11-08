/**
 * Bulk Import Users JavaScript
 */

// Setup
document.addEventListener('DOMContentLoaded', function() {
    setupDragAndDrop();
    setupFormSubmission();
    toggleDepartmentField();
});

/**
 * Setup Drag and Drop
 */
function setupDragAndDrop() {
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('csvFile');

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => {
            dropZone.classList.add('border-primary', 'bg-blue-50');
        });
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, () => {
            dropZone.classList.remove('border-primary', 'bg-blue-50');
        });
    });

    dropZone.addEventListener('drop', (e) => {
        const dt = e.dataTransfer;
        const files = dt.files;
        fileInput.files = files;
        handleFileSelect();
    });

    dropZone.addEventListener('click', () => fileInput.click());
    fileInput.addEventListener('change', handleFileSelect);
}

/**
 * Handle File Selection
 */
function handleFileSelect() {
    const fileInput = document.getElementById('csvFile');
    const filePreview = document.getElementById('filePreview');
    const fileName = document.getElementById('fileName');
    const fileError = document.getElementById('fileError');

    if (fileInput.files.length > 0) {
        const file = fileInput.files[0];
        const maxSize = 10 * 1024 * 1024; // 10MB

        // Validate file
        if (!['text/csv', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'].includes(file.type)) {
            showError(fileError, 'Invalid file format. Please use CSV, XLS, or XLSX.');
            fileInput.value = '';
            filePreview.classList.add('hidden');
            return;
        }

        if (file.size > maxSize) {
            showError(fileError, 'File is too large. Maximum size is 10MB.');
            fileInput.value = '';
            filePreview.classList.add('hidden');
            return;
        }

        clearError(fileError);
        fileName.textContent = file.name;
        filePreview.classList.remove('hidden');
    }
}

/**
 * Clear File
 */
function clearFile() {
    document.getElementById('csvFile').value = '';
    document.getElementById('filePreview').classList.add('hidden');
}

/**
 * Toggle Department Field
 */
function toggleDepartmentField() {
    const uploadType = document.querySelector('input[name="upload_type"]:checked');
    const deptFieldDiv = document.getElementById('departmentFieldDiv');
    const deptInput = document.getElementById('departmentId');

    if (uploadType && uploadType.value === 'students') {
        deptFieldDiv.classList.remove('hidden');
        deptInput.required = true;
    } else {
        deptFieldDiv.classList.add('hidden');
        deptInput.required = false;
        deptInput.value = '';
    }
}

/**
 * Setup Form Submission
 */
function setupFormSubmission() {
    document.getElementById('bulkImportForm').addEventListener('submit', handleFormSubmit);
}

/**
 * Handle Form Submit
 */
function handleFormSubmit(e) {
    e.preventDefault();

    const form = document.getElementById('bulkImportForm');
    const uploadType = document.querySelector('input[name="upload_type"]:checked').value;
    const deptId = document.getElementById('departmentId').value;
    const fileInput = document.getElementById('csvFile');
    const submitBtn = document.getElementById('submitBtn');

    // Validate
    if (!fileInput.files.length) {
        showToast('Please select a file to upload', 'error');
        return;
    }

    if (uploadType === 'students' && !deptId) {
        showError(document.getElementById('departmentError'), 'Please select a department');
        return;
    }

    // Prepare form data
    const formData = new FormData();
    formData.append('upload_type', uploadType);
    formData.append('csv_file', fileInput.files[0]);
    if (deptId) formData.append('department_id', deptId);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

    // Show progress
    document.getElementById('progressModal').classList.remove('hidden');
    submitBtn.disabled = true;

    // Submit
    fetch('/admin/users/bulk-import', {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('progressModal').classList.add('hidden');

        if (data.success) {
            showToast(`✅ ${data.message}`, 'success');
            setTimeout(() => {
                window.location.href = '/admin/users';
            }, 2000);
        } else {
            showToast(data.message || 'Import failed', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('progressModal').classList.add('hidden');
        showToast('Failed to import users', 'error');
    })
    .finally(() => {
        submitBtn.disabled = false;
    });
}

/**
 * Show Toast Notification
 */
function showToast(message, type = 'info') {
    const toastContainer = document.getElementById('toastContainer');

    const toastClass = {
        'success': 'bg-green-500',
        'error': 'bg-red-500',
        'info': 'bg-blue-500'
    }[type] || 'bg-blue-500';

    const toast = document.createElement('div');
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

/**
 * Show Error
 */
function showError(element, message) {
    element.textContent = message;
    element.classList.remove('hidden');
}

/**
 * Clear Error
 */
function clearError(element) {
    element.textContent = '';
    element.classList.add('hidden');
}
