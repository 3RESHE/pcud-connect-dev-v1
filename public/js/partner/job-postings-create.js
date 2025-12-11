/**
 * PARTNER JOB POSTINGS CREATE - COMPLETE JAVASCRIPT
 * File: public/js/partner/job-postings-create.js
 */

// ================================================================
// SKILLS MANAGEMENT
// ================================================================

let skillsList = [];

function handleSkillsInput(event) {
    if (event.key === 'Enter') {
        event.preventDefault();
        const input = document.getElementById('skills_input');
        const skill = input.value.trim();

        if (skill && !skillsList.includes(skill)) {
            addSkill(skill);
            input.value = '';
        }
    }
}

function addSkill(skill) {
    skillsList.push(skill);
    updateSkillsTags();
    updateHiddenSkillsInput();
}

function removeSkill(skill) {
    skillsList = skillsList.filter(s => s !== skill);
    updateSkillsTags();
    updateHiddenSkillsInput();
}

function updateSkillsTags() {
    const container = document.getElementById('skills_tags');
    container.innerHTML = '';

    skillsList.forEach(skill => {
        const tag = document.createElement('span');
        tag.className = 'inline-flex items-center gap-2 px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm';
        tag.innerHTML = `
            ${skill}
            <button type="button" onclick="removeSkill('${skill}')" class="text-blue-600 hover:text-blue-800 focus:outline-none">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        `;
        container.appendChild(tag);
    });
}

function updateHiddenSkillsInput() {
    document.getElementById('technical_skills').value = skillsList.join(',');
}

// ================================================================
// JOB TYPE SELECTION
// ================================================================

document.querySelectorAll('input[name="job_type"]').forEach(radio => {
    radio.addEventListener('change', function() {
        // Update radio indicators
        document.querySelectorAll('input[name="job_type"]').forEach(r => {
            const label = r.closest('label');
            if (r.checked) {
                label.classList.add('border-blue-500', 'bg-blue-50');
                label.classList.remove('border-gray-200', 'hover:bg-gray-50');
                label.querySelector('.job-type-radio').classList.remove('hidden');
            } else {
                label.classList.remove('border-blue-500', 'bg-blue-50');
                label.classList.add('border-gray-200', 'hover:bg-gray-50');
                label.querySelector('.job-type-radio').classList.add('hidden');
            }
        });

        toggleJobTypeFields();
    });
});

// ================================================================
// TOGGLE FIELDS BASED ON JOB TYPE
// ================================================================

function toggleJobTypeFields() {
    const jobType = document.querySelector('input[name="job_type"]:checked')?.value;
    const unpaidField = document.getElementById('unpaid_internship_field');
    const internshipDuration = document.getElementById('internship_duration');
    const compensationTitle = document.getElementById('compensation_title');
    const salaryMinLabel = document.getElementById('salary_min_label');
    const salaryMaxLabel = document.getElementById('salary_max_label');
    const compensationFields = document.getElementById('compensation_fields');

    if (jobType === 'internship') {
        unpaidField.classList.remove('hidden');
        internshipDuration.classList.remove('hidden');
        compensationTitle.textContent = 'Allowance/Compensation';
        salaryMinLabel.textContent = 'Minimum Allowance';
        salaryMaxLabel.textContent = 'Maximum Allowance';

        // Make salary optional for internships
        document.getElementById('salary_min').required = false;
        document.getElementById('salary_max').required = false;
    } else {
        unpaidField.classList.add('hidden');
        internshipDuration.classList.add('hidden');
        compensationTitle.textContent = 'Compensation';
        salaryMinLabel.textContent = 'Minimum Salary';
        salaryMaxLabel.textContent = 'Maximum Salary';

        // Make salary required for other positions
        document.getElementById('salary_min').required = true;
        document.getElementById('salary_max').required = true;

        // Clear unpaid checkbox
        document.getElementById('is_unpaid').checked = false;
        toggleAllowanceFields();
    }
}

// ================================================================
// TOGGLE ALLOWANCE FIELDS FOR UNPAID INTERNSHIPS
// ================================================================

function toggleAllowanceFields() {
    const isUnpaid = document.getElementById('is_unpaid').checked;
    const compensationFields = document.getElementById('compensation_fields');

    if (isUnpaid) {
        compensationFields.classList.add('opacity-50', 'pointer-events-none');
        compensationFields.querySelectorAll('input, select').forEach(el => {
            el.disabled = true;
            el.required = false;
        });
    } else {
        compensationFields.classList.remove('opacity-50', 'pointer-events-none');
        compensationFields.querySelectorAll('input, select').forEach(el => {
            el.disabled = false;
            el.required = true;
        });
    }
}

// ================================================================
// TOGGLE LOCATION FIELD
// ================================================================

function toggleLocationField() {
    const workSetup = document.getElementById('work_setup').value;
    const locationField = document.getElementById('location_field');
    const locationInput = document.getElementById('location');

    if (workSetup === 'remote') {
        locationField.classList.add('opacity-50', 'pointer-events-none');
        locationInput.disabled = true;
        locationInput.required = false;
        locationInput.value = 'Remote';
    } else {
        locationField.classList.remove('opacity-50', 'pointer-events-none');
        locationInput.disabled = false;
        locationInput.required = true;
        if (locationInput.value === 'Remote') {
            locationInput.value = '';
        }
    }
}

// ================================================================
// PREVIEW FUNCTIONALITY
// ================================================================

function previewJob() {
    const formData = new FormData(document.getElementById('jobPostingForm'));

    // Populate preview - Job Type
    const jobTypeSelect = document.querySelector('input[name="job_type"]:checked')?.value || 'N/A';
    document.getElementById('previewJobType').textContent = jobTypeSelect.charAt(0).toUpperCase() + jobTypeSelect.slice(1);

    // Department - Get selected department option text
    const departmentSelect = document.getElementById('department_id');
    const departmentText = departmentSelect.options[departmentSelect.selectedIndex]?.text || 'N/A';
    document.getElementById('previewDepartment').textContent = departmentText;

    // Experience Level
    const expLevelMap = {
        'entry': 'Entry Level (0-2 years)',
        'mid': 'Mid Level (3-5 years)',
        'senior': 'Senior Level (6+ years)',
        'lead': 'Lead/Manager Level',
        'student': 'Student/Fresh Graduate'
    };
    document.getElementById('previewExperienceLevel').textContent = expLevelMap[formData.get('experience_level')] || 'N/A';

    // Work Setup
    const workSetup = formData.get('work_setup');
    document.getElementById('previewWorkSetup').textContent = workSetup.charAt(0).toUpperCase() + workSetup.slice(1);

    // Location
    document.getElementById('previewLocation').textContent = formData.get('location') || 'Remote';

    // Compensation
    const salaryMin = formData.get('salary_min');
    const salaryMax = formData.get('salary_max');
    const salaryPeriod = formData.get('salary_period');
    const isUnpaid = document.getElementById('is_unpaid').checked;

    let compensation = 'Not specified';
    if (isUnpaid) {
        compensation = 'Unpaid';
    } else if (salaryMin || salaryMax) {
        const minDisplay = salaryMin ? '₱' + parseInt(salaryMin).toLocaleString() : '₱0';
        const maxDisplay = salaryMax ? '₱' + parseInt(salaryMax).toLocaleString() : 'N/A';
        compensation = `${minDisplay} - ${maxDisplay} ${salaryPeriod || ''}`;
    }
    document.getElementById('previewCompensation').textContent = compensation;

    // Positions
    document.getElementById('previewPositions').textContent = formData.get('positions_available') || '1';

    // Deadline
    const deadline = formData.get('application_deadline');
    document.getElementById('previewDeadline').textContent = deadline ? new Date(deadline).toLocaleDateString() : 'N/A';

    // Title and Description
    const title = formData.get('title');
    const description = formData.get('description');
    document.getElementById('previewDescription').innerHTML = `
        <div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">${title || 'Job Title'}</h3>
            <p class="text-gray-700 whitespace-pre-wrap">${description || 'No description'}</p>
        </div>
    `;

    // Requirements
    const education = formData.get('education_requirements') || '';
    const experience = formData.get('experience_requirements') || '';

    let requirementsHtml = '<div><h3 class="text-lg font-semibold text-gray-900 mb-2">Requirements</h3>';
    if (education) {
        requirementsHtml += `<div class="mb-3"><p class="text-sm font-medium text-gray-900">Education:</p><p class="text-gray-700 whitespace-pre-wrap">${education}</p></div>`;
    }
    if (experience) {
        requirementsHtml += `<div class="mb-3"><p class="text-sm font-medium text-gray-900">Experience:</p><p class="text-gray-700 whitespace-pre-wrap">${experience}</p></div>`;
    }
    if (skillsList.length > 0) {
        requirementsHtml += `<div><p class="text-sm font-medium text-gray-900 mb-2">Required Skills:</p><div class="flex flex-wrap gap-2">${skillsList.map(s => `<span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">${s}</span>`).join('')}</div></div>`;
    }
    requirementsHtml += '</div>';
    document.getElementById('previewRequirements').innerHTML = requirementsHtml;

    // Benefits
    const benefits = formData.get('benefits') || '';
    if (benefits) {
        document.getElementById('previewBenefits').innerHTML = `
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Benefits</h3>
                <p class="text-gray-700 whitespace-pre-wrap">${benefits}</p>
            </div>
        `;
    } else {
        document.getElementById('previewBenefits').innerHTML = '';
    }

    // Application process
    const appProcess = formData.get('application_process') || '';
    if (appProcess) {
        document.getElementById('previewApplicationProcess').innerHTML = `
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">How to Apply</h3>
                <p class="text-gray-700 whitespace-pre-wrap">${appProcess}</p>
            </div>
        `;
    } else {
        document.getElementById('previewApplicationProcess').innerHTML = '';
    }

    // Show modal
    document.getElementById('previewModal').classList.remove('hidden');
}

function closePreviewModal() {
    document.getElementById('previewModal').classList.add('hidden');
}

function submitForm() {
    document.getElementById('jobPostingForm').dispatchEvent(new Event('submit'));
}

// ================================================================
// FORM SUBMISSION
// ================================================================

document.getElementById('jobPostingForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    closePreviewModal();

    const submitBtn = document.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.disabled = true;
    submitBtn.textContent = 'Submitting...';

    // Clear previous errors
    clearAllErrors();

    const formData = new FormData(this);

    try {
        const response = await fetch(this.action, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            },
            body: formData
        });

        const data = await response.json();

        console.log('Response Status:', response.status);
        console.log('Response Data:', data);

        if (response.ok && data.success) {
            showToast('✅ ' + data.message, 'success');
            setTimeout(() => {
                window.location.href = data.redirect;
            }, 1500);
        } else {
            // Show detailed error messages
            if (data.errors) {
                console.log('Validation Errors:', data.errors);
                showValidationErrors(data.errors);
                showToast('❌ Please fix the validation errors below', 'error');
            } else {
                showToast('❌ ' + (data.message || 'Failed to create job posting'), 'error');
            }
        }
    } catch (error) {
        console.error('Fetch Error:', error);
        showToast('❌ Network error: ' + error.message, 'error');
    } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
    }
});

// ================================================================
// ERROR HANDLING & DISPLAY
// ================================================================

function showValidationErrors(errors) {
    const errorContainer = document.createElement('div');
    errorContainer.className = 'bg-red-50 border border-red-200 rounded-lg p-4 mb-6';
    errorContainer.id = 'validation-errors';

    let errorHTML = '<div class="flex gap-3"><div class="flex-shrink-0"><svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div><div class="flex-1"><h3 class="text-sm font-semibold text-red-800 mb-2">Validation Errors:</h3><ul class="text-sm text-red-700 space-y-1">';

    // Collect all errors and highlight fields
    const errorFields = [];

    for (const [field, messages] of Object.entries(errors)) {
        errorFields.push(field);

        // Display error message
        const fieldName = formatFieldName(field);
        errorHTML += `<li class="flex items-start"><span class="mr-2">•</span><span><strong>${fieldName}:</strong> ${messages[0]}</span></li>`;

        // Highlight the input field
        const input = document.querySelector(`[name="${field}"]`);
        if (input) {
            input.classList.add('border-red-500', 'bg-red-50');
            input.addEventListener('focus', function() {
                this.classList.remove('border-red-500', 'bg-red-50');
            }, { once: true });
        }
    }

    errorHTML += '</ul></div></div>';
    errorContainer.innerHTML = errorHTML;

    // Insert at top of form
    const form = document.getElementById('jobPostingForm');
    form.parentElement.insertBefore(errorContainer, form);

    // Scroll to first error
    if (errorFields.length > 0) {
        const firstErrorField = document.querySelector(`[name="${errorFields[0]}"]`);
        if (firstErrorField) {
            firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }
}

// Format field names for display
function formatFieldName(fieldName) {
    return fieldName
        .replace(/_/g, ' ')
        .replace(/([A-Z])/g, ' $1')
        .trim()
        .split(' ')
        .map(word => word.charAt(0).toUpperCase() + word.slice(1))
        .join(' ');
}

// Clear validation errors
function clearAllErrors() {
    const errorContainer = document.getElementById('validation-errors');
    if (errorContainer) {
        errorContainer.remove();
    }

    // Remove error highlighting from inputs
    document.querySelectorAll('.border-red-500').forEach(el => {
        el.classList.remove('border-red-500', 'bg-red-50');
    });
}

// ================================================================
// TOAST NOTIFICATIONS
// ================================================================

function showToast(message, type = 'info') {
    const container = document.getElementById('toastContainer');
    const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';

    const toast = document.createElement('div');
    toast.className = `${bgColor} text-white px-6 py-4 rounded-lg shadow-xl flex items-center justify-between gap-4 mb-2 animate-in fade-in slide-in-from-top-5`;
    toast.innerHTML = `
        <span class="flex-1">${message}</span>
        <button onclick="this.parentElement.remove()" class="text-white hover:text-gray-200 flex-shrink-0 focus:outline-none">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    `;

    container.appendChild(toast);
    setTimeout(() => toast.remove(), 5000);
}

// ================================================================
// PAGE INITIALIZATION
// ================================================================

document.addEventListener('DOMContentLoaded', function() {
    console.log('Job Posting Create page initialized');

    // Initialize field states
    toggleJobTypeFields();
    toggleLocationField();

    // Add event listeners for work setup changes
    const workSetupSelect = document.getElementById('work_setup');
    if (workSetupSelect) {
        workSetupSelect.addEventListener('change', toggleLocationField);
    }

    // Add event listener for internship checkbox
    const isUnpaidCheckbox = document.getElementById('is_unpaid');
    if (isUnpaidCheckbox) {
        isUnpaidCheckbox.addEventListener('change', toggleAllowanceFields);
    }

    console.log('Initialized successfully');
});

// ================================================================
// CLOSE PREVIEW MODAL ON OUTSIDE CLICK
// ================================================================

document.getElementById('previewModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closePreviewModal();
    }
});
