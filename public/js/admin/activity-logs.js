/**
 * View log details
 */
function viewLogDetails(logId) {
    fetch(`/admin/activity-logs/${logId}`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            displayLogDetails(data.data);
            document.getElementById('logDetailsModal').classList.remove('hidden');
        } else {
            alert('Failed to load log details');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred');
    });
}

/**
 * Display log details in modal
 */
function displayLogDetails(log) {
    const content = document.getElementById('logDetailsContent');

    let changedFieldsHTML = '';
    if (Object.keys(log.changed_fields).length > 0) {
        changedFieldsHTML = '<div class="mt-4"><h4 class="font-semibold text-gray-900 mb-2">Changed Fields:</h4>';
        Object.keys(log.changed_fields).forEach(field => {
            const change = log.changed_fields[field];
            changedFieldsHTML += `
                <div class="bg-gray-50 p-3 rounded mb-2">
                    <div class="font-medium text-gray-900">${field}</div>
                    <div class="text-sm text-gray-600">
                        <span class="text-red-600">Old: ${change.old ?? 'N/A'}</span>
                        <span class="mx-2">→</span>
                        <span class="text-green-600">New: ${change.new ?? 'N/A'}</span>
                    </div>
                </div>
            `;
        });
        changedFieldsHTML += '</div>';
    }

    content.innerHTML = `
        <div class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <h4 class="text-xs font-semibold text-gray-500 uppercase">User</h4>
                    <p class="text-sm font-medium text-gray-900">${log.user}</p>
                </div>
                <div>
                    <h4 class="text-xs font-semibold text-gray-500 uppercase">Action</h4>
                    <p class="text-sm font-medium text-gray-900">${log.action}</p>
                </div>
                <div>
                    <h4 class="text-xs font-semibold text-gray-500 uppercase">Date & Time</h4>
                    <p class="text-sm font-medium text-gray-900">${log.created_at}</p>
                </div>
                <div>
                    <h4 class="text-xs font-semibold text-gray-500 uppercase">IP Address</h4>
                    <p class="text-sm font-medium text-gray-900">${log.ip_address}</p>
                </div>
                <div>
                    <h4 class="text-xs font-semibold text-gray-500 uppercase">Browser</h4>
                    <p class="text-sm font-medium text-gray-900">${log.browser}</p>
                </div>
                <div>
                    <h4 class="text-xs font-semibold text-gray-500 uppercase">Subject Type</h4>
                    <p class="text-sm font-medium text-gray-900">${log.subject_type}</p>
                </div>
            </div>

            <div>
                <h4 class="text-xs font-semibold text-gray-500 uppercase">Subject</h4>
                <p class="text-sm font-medium text-gray-900">${log.subject}</p>
            </div>

            <div>
                <h4 class="text-xs font-semibold text-gray-500 uppercase">Description</h4>
                <p class="text-sm text-gray-700">${log.description || 'N/A'}</p>
            </div>

            ${changedFieldsHTML}
        </div>
    `;
}

/**
 * Close log details modal
 */
function closeLogDetailsModal() {
    document.getElementById('logDetailsModal').classList.add('hidden');
}

/**
 * Close modal when clicking outside
 */
document.addEventListener('click', function(event) {
    const modal = document.getElementById('logDetailsModal');
    if (event.target === modal) {
        closeLogDetailsModal();
    }
});
