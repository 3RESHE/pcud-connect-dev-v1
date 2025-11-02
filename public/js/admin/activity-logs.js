// Sample activity log data
let activityLogs = [
    {
        id: 1,
        user_id: 1,
        action: "created",
        description: "Created new user account - Maria Santos",
        subject_type: "User",
        subject_id: 56,
        created_at: "2025-10-31 14:30:00",
        ip_address: "192.168.1.100",
        user_agent: "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36",
        user_name: "Admin User",
        subject_title: "Maria Santos"
    },
    {
        id: 2,
        user_id: 3,
        action: "updated",
        description: "Updated job posting - Senior Developer",
        subject_type: "JobPosting",
        subject_id: 1,
        created_at: "2025-10-31 14:15:23",
        ip_address: "192.168.1.102",
        user_agent: "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36",
        user_name: "Staff Member",
        subject_title: "Senior PHP Developer"
    },
    {
        id: 3,
        user_id: 2,
        action: "approved",
        description: "Approved event - AI Seminar",
        subject_type: "Event",
        subject_id: 3,
        created_at: "2025-10-31 13:45:12",
        ip_address: "192.168.1.101",
        user_agent: "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36",
        user_name: "Admin User",
        subject_title: "AI in Education Seminar"
    },
    {
        id: 4,
        user_id: 4,
        action: "rejected",
        description: "Rejected job application - Sarah Johnson",
        subject_type: "JobApplication",
        subject_id: 12,
        created_at: "2025-10-31 13:20:45",
        ip_address: "192.168.1.103",
        user_agent: "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36",
        user_name: "Partner Admin",
        subject_title: "Junior Developer Application"
    },
    {
        id: 5,
        user_id: 1,
        action: "published",
        description: "Published news article - Faculty Awards",
        subject_type: "NewsArticle",
        subject_id: 7,
        created_at: "2025-10-31 12:55:33",
        ip_address: "192.168.1.100",
        user_agent: "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36",
        user_name: "Admin User",
        subject_title: "Faculty Achievement Award Winners"
    },
    {
        id: 6,
        user_id: 3,
        action: "checked_in",
        description: "Checked in attendee - Mark Lopez",
        subject_type: "EventRegistration",
        subject_id: 45,
        created_at: "2025-10-31 11:30:18",
        ip_address: "192.168.1.102",
        user_agent: "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36",
        user_name: "Event Staff",
        subject_title: "AI in Education Seminar"
    },
    {
        id: 7,
        user_id: 2,
        action: "updated",
        description: "Updated student profile - Juan Dela Cruz",
        subject_type: "User",
        subject_id: 34,
        created_at: "2025-10-31 10:15:09",
        ip_address: "192.168.1.101",
        user_agent: "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36",
        user_name: "Staff Member",
        subject_title: "Juan Dela Cruz"
    },
    {
        id: 8,
        user_id: 1,
        action: "completed",
        description: "Marked partnership as completed - Community Clean-up",
        subject_type: "Partnership",
        subject_id: 2,
        created_at: "2025-10-31 09:45:22",
        ip_address: "192.168.1.100",
        user_agent: "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36",
        user_name: "Admin User",
        subject_title: "Monthly Community Clean-up Drive"
    },
];

let currentActionFilter = '';
let currentUserFilter = '';
let currentDateFilter = '';

// Render activity logs table
function renderActivityLogs() {
    const tbody = document.getElementById('activityTableBody');
    if (!tbody) return;

    tbody.innerHTML = '';

    // Filter data
    let filteredLogs = activityLogs.filter(log => {
        let matches = true;

        // Action filter
        if (currentActionFilter && log.action !== currentActionFilter) {
            matches = false;
        }

        // User filter (simplified)
        if (currentUserFilter && !log.user_name.includes(currentUserFilter)) {
            matches = false;
        }

        // Date filter (simplified - based on text matching)
        if (currentDateFilter && !log.created_at.includes(currentDateFilter)) {
            matches = false;
        }

        return matches;
    });

    // Sort by date (newest first)
    filteredLogs.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));

    // Create rows
    filteredLogs.forEach(log => {
        const row = document.createElement('tr');
        row.classList.add('hover:bg-gray-50');
        row.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">${formatDateTime(log.created_at)}</div>
                <div class="text-xs text-gray-500">${getRelativeTime(log.created_at)}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">${log.user_name}</div>
                <div class="text-xs text-gray-500">${log.ip_address}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${
                    log.action === 'created' || log.action === 'approved' || log.action === 'published' || log.action === 'completed' ? 'bg-green-100 text-green-800' :
                    log.action === 'updated' ? 'bg-blue-100 text-blue-800' :
                    log.action === 'rejected' || log.action === 'deleted' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800'
                }">
                    ${log.action.charAt(0).toUpperCase() + log.action.slice(1)}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">
                    <span class="font-medium">${getSubjectTypeDisplay(log.subject_type)}</span>
                </div>
                <div class="text-sm text-gray-500">${log.subject_title}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-500">${log.ip_address}</div>
                <div class="text-xs text-gray-400">${getBrowserIcon(log.user_agent)} ${getBrowserName(log.user_agent)}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                <button onclick="viewLogDetails('${log.id}')" class="text-primary hover:text-blue-700">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    View Details
                </button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

// Filter functions
function filterLogs() {
    currentActionFilter = document.getElementById('actionFilter')?.value || '';
    currentUserFilter = document.getElementById('userFilter')?.value || '';
    currentDateFilter = document.getElementById('dateFilter')?.value || '';

    renderActivityLogs();
}

// Helper functions
function formatDateTime(dateTimeString) {
    const date = new Date(dateTimeString);
    return date.toLocaleString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
        hour: 'numeric',
        minute: '2-digit'
    });
}

function getRelativeTime(dateTimeString) {
    const now = new Date();
    const logDate = new Date(dateTimeString);
    const diff = now - logDate;

    if (diff < 60000) {
        return 'Just now';
    }

    const minutes = Math.floor(diff / 60000);
    const hours = Math.floor(diff / 3600000);
    const days = Math.floor(diff / 86400000);

    if (minutes < 60) {
        return `${minutes}m ago`;
    } else if (hours < 24) {
        return `${hours}h ago`;
    } else {
        return `${days}d ago`;
    }
}

function getSubjectTypeDisplay(type) {
    const displayNames = {
        'User': 'User',
        'JobPosting': 'Job Posting',
        'JobApplication': 'Job Application',
        'Event': 'Event',
        'EventRegistration': 'Event Registration',
        'NewsArticle': 'News Article',
        'Partnership': 'Partnership'
    };
    return displayNames[type] || type;
}

function getBrowserIcon(userAgent) {
    if (!userAgent) return '';

    if (userAgent.includes('Chrome')) {
        return '🦊'; // Chrome
    } else if (userAgent.includes('Firefox')) {
        return '🔥'; // Firefox
    } else if (userAgent.includes('Safari')) {
        return '🧭'; // Safari
    } else if (userAgent.includes('Edge')) {
        return '🔍'; // Edge
    }

    return '🌐';
}

function getBrowserName(userAgent) {
    if (!userAgent) return 'Unknown Browser';

    if (userAgent.includes('Chrome')) {
        return 'Chrome';
    } else if (userAgent.includes('Firefox')) {
        return 'Firefox';
    } else if (userAgent.includes('Safari')) {
        return 'Safari';
    } else if (userAgent.includes('Edge')) {
        return 'Edge';
    }

    return 'Other Browser';
}

// Action functions (blank - functionality later)
function viewLogDetails(logId) {
    console.log('View log details:', logId);
}

function exportLogs() {
    // Open export modal
    document.getElementById('exportModal').classList.remove('hidden');
}

// Close export modal
function closeExportModal() {
    document.getElementById('exportModal').classList.add('hidden');
}

// Form submission (blank - functionality later)
document.getElementById('exportForm')?.addEventListener('submit', function (e) {
    e.preventDefault();
    console.log('Export logs form submitted');
    closeExportModal();
});

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    renderActivityLogs();
});
