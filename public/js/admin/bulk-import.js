/**
 * Bulk Import Users - Complete JavaScript Implementation
 * File: public/js/admin/bulk-import.js
 *
 * Features:
 * - Drag & drop file upload
 * - File validation (type, size)
 * - Department requirement for students
 * - Real-time form validation
 * - Loading states and progress feedback
 * - Toast notifications
 * - Error handling and logging
 */

// =====================================================
// INITIALIZATION
// =====================================================

document.addEventListener('DOMContentLoaded', function() {
    setupDragAndDrop();
    setupFormSubmission();
    setupDepartmentToggle();
    console.log('✅ Bulk Import form initialized');
});


// =====================================================
// DRAG AND DROP FUNCTIONALITY
// =====================================================

function setupDragAndDrop() {
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('csvFile');

    if (!dropZone || !fileInput) {
        console.warn('⚠️ Drop zone or file input not found');
        return;
    }

    // Prevent default drag behaviors
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    // Highlight drop zone when dragging over
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });

    function highlight(e) {
        dropZone.classList.add('border-primary', 'bg-blue-50');
    }

    function unhighlight(e) {
        dropZone.classList.remove('border-primary', 'bg-blue-50');
    }

    // Handle dropped files
    dropZone.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;

        if (files.length > 0) {
            fileInput.files = files;
            handleFileSelect();
        }
    }

    // Handle click to select file
    dropZone.addEventListener('click', () => fileInput.click());
    fileInput.addEventListener('change', handleFileSelect);
}


// =====================================================
// FILE SELECTION & VALIDATION
// =====================================================

function handleFileSelect() {
    const fileInput = document.getElementById('csvFile');
    const filePreview = document.getElementById('filePreview');
    const fileName = document.getElementById('fileName');
    const fileError = document.getElementById('fileError');

    clearError(fileError);

    if (fileInput.files.length === 0) {
        filePreview.classList.add('hidden');
        return;
    }

    const file = fileInput.files[0];

    // ===== Validation: File type =====
    const validTypes = [
        'text/csv',
        'application/csv',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
    ];

    const fileExtension = file.name.split('.').pop().toLowerCase();
    const validExtensions = ['csv', 'xls', 'xlsx'];

    // Check both MIME type and file extension (some systems have wrong MIME types)
    if (!validTypes.includes(file.type) && !validExtensions.includes(fileExtension)) {
        showError(fileError, '❌ Invalid file type. Please upload CSV or Excel (.csv, .xlsx, .xls) files only.');
        fileInput.value = '';
        filePreview.classList.add('hidden');
        console.warn('❌ Invalid file type:', file.type, 'Extension:', fileExtension);
        return;
    }

    // ===== Validation: File size (10MB max) =====
    const maxSize = 10 * 1024 * 1024; // 10MB
    if (file.size > maxSize) {
        showError(fileError, `❌ File size (${formatFileSize(file.size)}) exceeds 10MB limit. Please upload a smaller file.`);
        fileInput.value = '';
        filePreview.classList.add('hidden');
        console.warn('❌ File too large:', file.size);
        return;
    }

    // ===== Show file preview =====
    fileName.textContent = `✓ ${file.name} (${formatFileSize(file.size)})`;
    filePreview.classList.remove('hidden');
    console.log('✅ File validated:', file.name);
}


// =====================================================
// FORM SUBMISSION
// =====================================================

function setupFormSubmission() {
    const form = document.getElementById('bulkImportForm');
    if (!form) {
        console.warn('⚠️ Form not found');
        return;
    }

    form.addEventListener('submit', handleFormSubmit);
}

async function handleFormSubmit(e) {
    e.preventDefault();
    console.log('📤 Form submission started');

    const form = document.getElementById('bulkImportForm');
    const fileInput = document.getElementById('csvFile');
    const fileError = document.getElementById('fileError');
    const departmentError = document.getElementById('departmentError');
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const submitIcon = document.getElementById('submitIcon');
    const progressModal = document.getElementById('progressModal');

    // Clear previous errors
    clearError(fileError);
    clearError(departmentError);

    // ===== VALIDATION =====

    // 1. Check file selected
    if (fileInput.files.length === 0) {
        showError(fileError, '❌ Please select a file to upload.');
        console.warn('❌ No file selected');
        return;
    }

    // 2. Check upload type selected
    const uploadType = document.querySelector('input[name="upload_type"]:checked')?.value;
    if (!uploadType) {
        showError(fileError, '❌ Please select whether you are importing Students or Alumni.');
        console.warn('❌ Upload type not selected');
        return;
    }

    // 3. Check department for students
    if (uploadType === 'students') {
        const deptId = document.getElementById('departmentId').value;
        if (!deptId) {
            showError(departmentError, '❌ Please select a department for student imports.');
            console.warn('❌ Department not selected for students');
            return;
        }
    }

    // ===== SHOW LOADING STATE =====
    progressModal.classList.remove('hidden');
    submitBtn.disabled = true;
    submitText.textContent = 'Uploading & Processing...';
    submitIcon.classList.add('animate-spin');

    try {
        // Create FormData from form
        const formData = new FormData(form);
        const file = fileInput.files[0];

        console.log('📋 Submission details:', {
            uploadType: uploadType,
            fileName: file.name,
            fileSize: formatFileSize(file.size),
            department: document.getElementById('departmentId').value || 'N/A'
        });

        // Get the form action URL or use default
        const actionUrl = form.getAttribute('action') || '/admin/users/bulk-import';

        // Submit to server
        const response = await fetch(actionUrl, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        });

        // Check if response is JSON
        let data;
        try {
            data = await response.json();
        } catch (e) {
            console.error('❌ Response is not JSON:', response.status);
            showToast('error', `❌ Server error: ${response.status} ${response.statusText}`);
            progressModal.classList.add('hidden');
            submitBtn.disabled = false;
            submitText.textContent = 'Upload & Import';
            submitIcon.classList.remove('animate-spin');
            return;
        }

        console.log('📥 Server response:', data);

        // Handle response
        if (data.success) {
            showToast('success', `✅ ${data.message}`);
            console.log('✅ Import successful - Count:', data.count);

            // Reset form
            setTimeout(() => {
                form.reset();
                fileInput.value = '';
                document.getElementById('filePreview').classList.add('hidden');
                progressModal.classList.add('hidden');

                // Redirect after short delay
                setTimeout(() => {
                    console.log('🔄 Redirecting to users page');
                    window.location.href = '/admin/users';
                }, 1500);
            }, 1000);
        } else {
            // Error response
            showToast('error', `❌ ${data.message || 'Import failed'}`);
            console.error('❌ Import failed:', data.message);

            // Show validation errors if present
            if (data.errors) {
                const errorMessages = Object.entries(data.errors)
                    .map(([field, messages]) => {
                        const msgArray = Array.isArray(messages) ? messages : [messages];
                        return `${field}: ${msgArray.join(', ')}`;
                    })
                    .join('\n');
                showError(fileError, errorMessages);
                console.error('❌ Validation errors:', data.errors);
            }

            progressModal.classList.add('hidden');
        }
    } catch (error) {
        console.error('❌ Network or parsing error:', error);
        showToast('error', `❌ An error occurred: ${error.message}`);
        progressModal.classList.add('hidden');
    } finally {
        submitBtn.disabled = false;
        submitText.textContent = 'Upload & Import';
        submitIcon.classList.remove('animate-spin');
    }
}


// =====================================================
// DEPARTMENT TOGGLE
// =====================================================

function setupDepartmentToggle() {
    const radioButtons = document.querySelectorAll('input[name="upload_type"]');

    if (radioButtons.length === 0) {
        console.warn('⚠️ Upload type radio buttons not found');
        return;
    }

    radioButtons.forEach(radio => {
        radio.addEventListener('change', toggleDepartmentField);
    });

    // Set initial state
    toggleDepartmentField();
}

function toggleDepartmentField() {
    const uploadType = document.querySelector('input[name="upload_type"]:checked')?.value;
    const departmentFieldDiv = document.getElementById('departmentFieldDiv');
    const departmentId = document.getElementById('departmentId');

    if (!departmentFieldDiv || !departmentId) {
        console.warn('⚠️ Department field elements not found');
        return;
    }

    if (uploadType === 'students') {
        departmentFieldDiv.classList.remove('hidden');
        departmentId.required = true;
        console.log('👥 Students selected - Department field shown');
    } else {
        departmentFieldDiv.classList.add('hidden');
        departmentId.required = false;
        departmentId.value = '';
        clearError(document.getElementById('departmentError'));
        console.log('🎓 Alumni selected - Department field hidden');
    }
}


// =====================================================
// TOAST NOTIFICATIONS
// =====================================================

function showToast(type, message) {
    const container = document.getElementById('toastContainer');
    if (!container) {
        console.warn('⚠️ Toast container not found');
        return;
    }

    const toastId = 'toast-' + Date.now();
    const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
    const textColor = 'text-white';

    const toast = document.createElement('div');
    toast.id = toastId;
    toast.className = `${bgColor} ${textColor} p-4 rounded-lg shadow-lg flex items-start space-x-3`;
    toast.style.animation = 'slideInRight 0.3s ease-in-out';

    toast.innerHTML = `
        <div class="flex-1">
            ${escapeHtml(message)}
        </div>
        <button onclick="document.getElementById('${toastId}').remove()" class="text-white hover:opacity-75 flex-shrink-0">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        </button>
    `;

    container.appendChild(toast);

    // Auto-remove after 5 seconds
    setTimeout(() => {
        if (toast.parentNode) {
            toast.remove();
        }
    }, 5000);

    console.log(`📢 Toast (${type}):`, message);
}


// =====================================================
// ERROR HANDLING
// =====================================================

function showError(element, message) {
    if (!element) {
        console.warn('⚠️ Error element not provided');
        return;
    }
    element.textContent = message;
    element.classList.remove('hidden');
    console.warn('⚠️ Error shown:', message);
}

function clearError(element) {
    if (!element) return;
    element.textContent = '';
    element.classList.add('hidden');
}


// =====================================================
// UTILITY FUNCTIONS
// =====================================================

/**
 * Format file size in human readable format
 */
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}

/**
 * Clear file input and preview
 */
function clearFile() {
    const fileInput = document.getElementById('csvFile');
    const filePreview = document.getElementById('filePreview');
    const fileError = document.getElementById('fileError');

    if (fileInput) fileInput.value = '';
    if (filePreview) filePreview.classList.add('hidden');
    if (fileError) clearError(fileError);

    console.log('🗑️ File cleared');
}

/**
 * Escape HTML special characters to prevent XSS
 */
function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}

/**
 * Log utility for debugging
 */
function logDebug(label, data) {
    if (window.DEBUG_MODE || localStorage.getItem('DEBUG_BULK_IMPORT')) {
        console.log(`🔍 [${label}]`, data);
    }
}


// =====================================================
// CSS ANIMATIONS (if not in Tailwind)
// =====================================================

// Add fade-in animation if not present
if (!document.getElementById('bulk-import-animations')) {
    const style = document.createElement('style');
    style.id = 'bulk-import-animations';
    style.textContent = `
        @keyframes slideInRight {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        .animate-fade-in {
            animation: slideInRight 0.3s ease-in-out;
        }
    `;
    document.head.appendChild(style);
}

console.log('✅ Bulk Import JavaScript loaded successfully');
