// Toggle end date field for multi-day events
function toggleEndDate() {
    const isMultiDay = document.getElementById('is_multi_day').checked;
    const endDateField = document.getElementById('end_date_field');
    const endDateInput = document.getElementById('end_date');

    if (isMultiDay) {
        endDateField.classList.remove('hidden');
        endDateInput.required = true;
    } else {
        endDateField.classList.add('hidden');
        endDateInput.required = false;
        endDateInput.value = '';
    }
}

// Toggle location fields based on event format
function toggleLocationFields() {
    const format = document.getElementById('event_format').value;
    const inPersonLocation = document.getElementById('in_person_location');
    const virtualLocation = document.getElementById('virtual_location');

    // Get all required inputs in each section
    const inPersonInputs = inPersonLocation.querySelectorAll('input[required], select[required], textarea[required]');
    const virtualInputs = virtualLocation.querySelectorAll('input[required], select[required]');

    if (format === 'in_person') {
        inPersonLocation.classList.remove('hidden');
        virtualLocation.classList.add('hidden');
        inPersonInputs.forEach(input => input.required = true);
        virtualInputs.forEach(input => input.required = false);
    } else if (format === 'virtual') {
        inPersonLocation.classList.add('hidden');
        virtualLocation.classList.remove('hidden');
        inPersonInputs.forEach(input => input.required = false);
        virtualInputs.forEach(input => {
            if (input.id === 'platform') input.required = true;
        });
    } else if (format === 'hybrid') {
        inPersonLocation.classList.remove('hidden');
        virtualLocation.classList.remove('hidden');
        inPersonInputs.forEach(input => input.required = true);
        virtualInputs.forEach(input => {
            if (input.id === 'platform') input.required = true;
        });
    } else {
        inPersonLocation.classList.add('hidden');
        virtualLocation.classList.add('hidden');
        inPersonInputs.forEach(input => input.required = false);
        virtualInputs.forEach(input => input.required = false);
    }
}

// Toggle custom platform field
function toggleCustomPlatform() {
    const platform = document.getElementById('platform').value;
    const customPlatformField = document.getElementById('custom_platform_field');
    const customPlatformInput = document.getElementById('custom_platform');

    if (platform === 'other') {
        customPlatformField.classList.remove('hidden');
        customPlatformInput.required = true;
    } else {
        customPlatformField.classList.add('hidden');
        customPlatformInput.required = false;
        customPlatformInput.value = '';
    }
}

// Toggle registration fields
function toggleRegistrationFields() {
    const registrationRequired = document.getElementById('registration_required').checked;
    const registrationFields = document.getElementById('registration_fields');

    if (registrationRequired) {
        registrationFields.classList.remove('hidden');
    } else {
        registrationFields.classList.add('hidden');
    }
}

// Preview event before submission
function previewEvent() {
    const form = document.getElementById('eventForm');

    // Basic validation
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }

    // Gather form data
    const formData = new FormData(form);
    let previewHTML = '<div class="space-y-4">';

    // Event Title
    previewHTML += `
        <div>
            <h4 class="text-sm font-semibold text-gray-700 mb-1">Event Title</h4>
            <p class="text-gray-900">${formData.get('title')}</p>
        </div>
    `;

    // Event Description
    previewHTML += `
        <div>
            <h4 class="text-sm font-semibold text-gray-700 mb-1">Description</h4>
            <p class="text-gray-900">${formData.get('description')}</p>
        </div>
    `;

    // Event Format
    const formatSelect = document.getElementById('event_format');
    const formatText = formatSelect.options[formatSelect.selectedIndex].text;
    previewHTML += `
        <div>
            <h4 class="text-sm font-semibold text-gray-700 mb-1">Event Format</h4>
            <p class="text-gray-900">${formatText}</p>
        </div>
    `;

    // Date & Time
    const eventDate = formData.get('event_date');
    const endDate = formData.get('end_date');
    const startTime = formData.get('start_time');
    const endTime = formData.get('end_time');

    let dateTimeText = formatDate(eventDate);
    if (endDate) {
        dateTimeText += ` to ${formatDate(endDate)}`;
    }
    dateTimeText += ` • ${formatTime(startTime)} - ${formatTime(endTime)}`;

    previewHTML += `
        <div>
            <h4 class="text-sm font-semibold text-gray-700 mb-1">Date & Time</h4>
            <p class="text-gray-900">${dateTimeText}</p>
        </div>
    `;

    // Location
    const format = formData.get('event_format');
    if (format === 'in_person' || format === 'hybrid') {
        const venueName = formData.get('venue_name');
        const venueCapacity = formData.get('venue_capacity');
        previewHTML += `
            <div>
                <h4 class="text-sm font-semibold text-gray-700 mb-1">Venue</h4>
                <p class="text-gray-900">${venueName} (Capacity: ${venueCapacity})</p>
            </div>
        `;
    }

    if (format === 'virtual' || format === 'hybrid') {
        const platformSelect = document.getElementById('platform');
        let platformText = platformSelect.options[platformSelect.selectedIndex].text;
        if (platformSelect.value === 'other') {
            platformText = formData.get('custom_platform');
        }
        previewHTML += `
            <div>
                <h4 class="text-sm font-semibold text-gray-700 mb-1">Virtual Platform</h4>
                <p class="text-gray-900">${platformText}</p>
            </div>
        `;
    }

    // Target Audience (SIMPLIFIED)
    const targetAudience = formData.get('target_audience');
    let audienceText = '';
    if (targetAudience === 'all_students') audienceText = 'All Students';
    else if (targetAudience === 'alumni') audienceText = 'Alumni';
    else if (targetAudience === 'open_for_all') audienceText = 'Open for All (Students, Alumni & Public)';

    previewHTML += `
        <div>
            <h4 class="text-sm font-semibold text-gray-700 mb-1">Target Audience</h4>
            <p class="text-gray-900">${audienceText}</p>
        </div>
    `;

    // Contact Information
    previewHTML += `
        <div>
            <h4 class="text-sm font-semibold text-gray-700 mb-1">Contact Person</h4>
            <p class="text-gray-900">${formData.get('contact_person')}</p>
            <p class="text-gray-600 text-sm">${formData.get('contact_email')}</p>
        </div>
    `;

    previewHTML += '</div>';

    // Show modal
    document.getElementById('previewContent').innerHTML = previewHTML;
    document.getElementById('previewModal').classList.remove('hidden');
}

// Close preview modal
function closePreviewModal() {
    document.getElementById('previewModal').classList.add('hidden');
}

// Submit from preview
function submitFromPreview() {
    const form = document.getElementById('eventForm');
    const submitBtn = document.createElement('input');
    submitBtn.type = 'hidden';
    submitBtn.name = 'action';
    submitBtn.value = 'submit';
    form.appendChild(submitBtn);
    form.submit();
}

// Helper functions
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

function formatTime(timeString) {
    const [hours, minutes] = timeString.split(':');
    const hour = parseInt(hours);
    const ampm = hour >= 12 ? 'PM' : 'AM';
    const displayHour = hour % 12 || 12;
    return `${displayHour}:${minutes} ${ampm}`;
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Set initial states
    toggleLocationFields();
    toggleRegistrationFields();
    toggleEndDate();

    // Add event listeners
    const eventFormatSelect = document.getElementById('event_format');
    if (eventFormatSelect) {
        eventFormatSelect.addEventListener('change', toggleLocationFields);
    }

    const registrationCheckbox = document.getElementById('registration_required');
    if (registrationCheckbox) {
        registrationCheckbox.addEventListener('change', toggleRegistrationFields);
    }

    const multiDayCheckbox = document.getElementById('is_multi_day');
    if (multiDayCheckbox) {
        multiDayCheckbox.addEventListener('change', toggleEndDate);
    }

    const platformSelect = document.getElementById('platform');
    if (platformSelect) {
        platformSelect.addEventListener('change', toggleCustomPlatform);
    }

    // Form validation before preview
    const previewBtn = document.getElementById('preview_btn');
    if (previewBtn) {
        previewBtn.addEventListener('click', function(e) {
            e.preventDefault();
            previewEvent();
        });
    }
});
