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

    let propertiesHTML = '';
    if (log.properties && Object.keys(log.properties).length > 0) {
        propertiesHTML = '<div class="mt-4 border-t pt-4"><h4 class="font-semibold text-gray-900 mb-2">Additional Information:</h4>';
        Object.entries(log.properties).forEach(([key, value]) => {
            propertiesHTML += `
                <div class="flex justify-between py-1">
                    <span class="text-sm text-gray-600 capitalize">${key.replace(/_/g, ' ')}:</span>
                    <span class="text-sm text-gray-900 font-medium">${value || 'N/A'}</span>
                </div>
            `;
        });
        propertiesHTML += '</div>';
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

            <div class="border-t pt-4">
                <h4 class="text-xs font-semibold text-gray-500 uppercase mb-2">Subject</h4>
                <p class="text-sm font-medium text-gray-900">${log.subject}</p>
            </div>

            <div>
                <h4 class="text-xs font-semibold text-gray-500 uppercase mb-2">Description</h4>
                <p class="text-sm text-gray-700">${log.description || 'N/A'}</p>
            </div>

            ${propertiesHTML}
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
