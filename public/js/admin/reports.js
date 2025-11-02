// Sample report data
let reportData = {
    userGrowth: [
        { month: 'Jan', users: 450 },
        { month: 'Feb', users: 520 },
        { month: 'Mar', users: 680 },
        { month: 'Apr', users: 780 },
        { month: 'May', users: 920 },
        { month: 'Jun', users: 1050 },
        { month: 'Jul', users: 1120 },
        { month: 'Aug', users: 1180 },
        { month: 'Sep', users: 1220 },
        { month: 'Oct', users: 1245 },
        { month: 'Nov', users: 1250 },
        { month: 'Dec', users: 1250 },
    ],

    approvalStatus: {
        'Pending': 15,
        'Approved': 145,
        'Rejected': 8,
        'Published': 92,
    },

    contentByType: {
        'Job Postings': 156,
        'Events': 28,
        'News Articles': 89,
        'Partnerships': 24,
    },

    activityByDay: [
        { day: 'Monday', morning: 24, afternoon: 45, evening: 32 },
        { day: 'Tuesday', morning: 28, afternoon: 52, evening: 38 },
        { day: 'Wednesday', morning: 32, afternoon: 48, evening: 35 },
        { day: 'Thursday', morning: 26, afternoon: 49, evening: 40 },
        { day: 'Friday', morning: 30, afternoon: 55, evening: 42 },
        { day: 'Saturday', morning: 18, afternoon: 32, evening: 28 },
        { day: 'Sunday', morning: 15, afternoon: 25, evening: 20 },
    ]
};

let currentDateRange = 'week';

// Change date range
function changeDateRange() {
    currentDateRange = document.getElementById('dateRange').value;
    console.log('Date range changed to:', currentDateRange);
    // Reload charts and data here
}

// Export report
function exportReport() {
    console.log('Exporting report for:', currentDateRange);
    // Open export modal or trigger download
    alert('Report export functionality will be implemented with backend');
}

// Draw simple ASCII-style charts (replace with Chart.js in production)
function drawCharts() {
    console.log('Drawing charts...');
    // Chart.js or similar library would be used here
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    drawCharts();
});
