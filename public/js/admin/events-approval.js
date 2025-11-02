// Sample event data
let events = [
    {
        id: 1,
        title: "Advanced Web Development Bootcamp",
        staff: "Prof. Carlos Martinez",
        date: "December 15, 2025",
        time: "9:00 AM - 5:00 PM",
        duration: "6 weeks",
        location: "IT Laboratory 1 & 2",
        capacity: 50,
        status: "pending",
        description: "Comprehensive 6-week bootcamp covering modern web development technologies including React, Node.js, and cloud deployment.",
        tags: "Web Development, Bootcamp, Professional Development",
        submitted_date: "1 day ago",
        is_featured: false,
    },
    {
        id: 2,
        title: "Digital Marketing Mastery Workshop Series",
        staff: "Ms. Patricia Cruz",
        date: "November 10, 2025",
        time: "2:00 PM - 5:00 PM",
        duration: "4 weeks",
        location: "Business Building Room 305",
        capacity: 40,
        status: "approved",
        description: "4-week workshop series covering modern digital marketing strategies.",
        tags: "Marketing, Professional Development",
        submitted_date: "Oct 12, 2025",
        is_featured: false,
    },
    {
        id: 3,
        title: "AI in Education Seminar",
        staff: "Dr. Elena Garcia",
        date: "Today",
        time: "1:00 PM",
        duration: "4 hours",
        location: "Main Auditorium",
        capacity: 200,
        status: "published",
        description: "Seminar on integrating AI tools in educational practices. Currently in progress.",
        tags: "AI, Education, Live Event",
        submitted_date: "Oct 15, 2025",
        is_featured: false,
        is_live: true,
        checked_in: 53,
    },
    {
        id: 4,
        title: "Cybersecurity Awareness Workshop",
        staff: "Prof. Michael Tan",
        date: "November 20, 2025",
        time: "10:00 AM - 12:00 PM",
        duration: "2 hours",
        location: "IT Department Room 101",
        capacity: 80,
        status: "published",
        description: "Workshop on cybersecurity best practices and threat detection.",
        tags: "Cybersecurity, Workshop",
        submitted_date: "Oct 9, 2025",
        is_featured: false,
        registrations: 35,
    },
    {
        id: 5,
        title: "International Food Festival",
        staff: "Mr. Jose Delgado",
        date: "October 25, 2025",
        time: "12:00 PM - 6:00 PM",
        duration: "6 hours",
        location: "Campus Grounds",
        capacity: 500,
        status: "rejected",
        description: "Proposed multi-cultural food festival.",
        tags: "Food Festival, Cultural",
        submitted_date: "Oct 8, 2025",
        is_featured: false,
        rejection_reason: "• Scheduling conflict with Homecoming Week activities\n• Insufficient lead time for vendor coordination\n• Lack of detailed safety and sanitation protocols",
    },
    {
        id: 6,
        title: "Cybersecurity Awareness Workshop (Completed)",
        staff: "Prof. Michael Tan",
        date: "October 5, 2025",
        time: "10:00 AM - 12:00 PM",
        duration: "2 hours",
        location: "IT Department Room 101",
        capacity: 82,
        status: "completed",
        description: "Successfully completed workshop on cybersecurity. Great turnout with excellent feedback.",
        tags: "Cybersecurity, Workshop, Successful",
        submitted_date: "Sep 20, 2025",
        is_featured: false,
        attendance: 78,
        rating: 4.8,
    },
];

let currentFilter = 'all';

// Render event cards
function renderEvents() {
    const container = document.getElementById('eventsCardsContainer');
    container.innerHTML = '';

    events.forEach(event => {
        if (currentFilter !== 'all' && event.status !== currentFilter) return;

        const eventCardHtml = `
            <div class="event-card bg-white rounded-lg shadow-sm p-6 border-l-4 ${
                event.status === 'pending' ? 'border-yellow-500' :
                event.status === 'approved' ? 'border-green-400' :
                event.status === 'published' ? (event.is_live ? 'border-orange-500' : 'border-blue-400') :
                event.status === 'rejected' ? 'border-red-400' :
                'border-gray-500'
            }">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex-1">
                        <div class="flex items-center mb-3">
                            <span class="px-2 py-1 rounded-full text-xs mr-3 font-medium ${
                                event.status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                event.status === 'approved' ? 'bg-green-100 text-green-800' :
                                event.status === 'published' ? (event.is_live ? 'bg-orange-100 text-orange-800 animate-pulse' : 'bg-blue-100 text-blue-800') :
                                event.status === 'rejected' ? 'bg-red-100 text-red-800' :
                                'bg-gray-100 text-gray-800'
                            }">
                                ${event.is_live ? 'Happening Now' : event.status.charAt(0).toUpperCase() + event.status.slice(1)}
                            </span>
                            <span class="text-sm text-gray-500">${event.submitted_date}</span>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">${event.title}</h3>
                        <div class="flex items-center text-sm text-gray-600 mb-4 space-x-6">
                            <div class="flex items-center">
                                <strong>Staff:</strong>&nbsp;${event.staff}
                            </div>
                            <div class="flex items-center">
                                <strong>${event.date}</strong>&nbsp;• ${event.time}
                            </div>
                            <div class="flex items-center">
                                <strong>Location:</strong>&nbsp;${event.location}
                            </div>
                        </div>
                        <p class="text-gray-600 mb-4 line-clamp-2">${event.description}</p>
                        <div class="flex flex-wrap gap-2">
                            ${event.tags.split(',').map(tag => `<span class="px-2 py-1 bg-purple-100 text-purple-800 text-xs rounded">${tag.trim()}</span>`).join('')}
                        </div>
                    </div>
                    <div class="ml-6 flex flex-col space-y-2 min-w-0 flex-shrink-0">
                        ${event.status === 'pending' ? `
                            <button onclick="viewEventDetails(${event.id})" class="px-4 py-2 bg-gray-600 text-white text-sm rounded-md hover:bg-gray-700 text-center">View Details</button>
                            <button onclick="openApproveEventModal(${event.id}, '${event.title}', '${event.staff}', '${event.date} ${event.time}')" class="px-4 py-2 bg-green-600 text-white text-sm rounded-md hover:bg-green-700">Approve & Publish</button>
                            <button onclick="openRejectEventModal(${event.id}, '${event.title}')" class="px-4 py-2 bg-red-600 text-white text-sm rounded-md hover:bg-red-700">Reject</button>
                        ` : event.status === 'approved' ? `
                            <button onclick="viewEventDetails(${event.id})" class="px-4 py-2 bg-gray-600 text-white text-sm rounded-md hover:bg-gray-700 text-center">View Details</button>
                            <button onclick="featureEvent(${event.id})" class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700">Feature Event</button>
                        ` : event.status === 'published' ? `
                            <button onclick="viewEventDetails(${event.id})" class="px-4 py-2 bg-gray-600 text-white text-sm rounded-md hover:bg-gray-700 text-center">View Details</button>
                            <button onclick="featureEvent(${event.id})" class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700">Feature Event</button>
                            <button onclick="unpublishEvent(${event.id})" class="px-4 py-2 border border-red-300 text-red-700 text-sm rounded-md hover:bg-red-50">Unpublish</button>
                        ` : `
                            <button onclick="viewEventDetails(${event.id})" class="px-4 py-2 bg-gray-600 text-white text-sm rounded-md hover:bg-gray-700 text-center">View Details</button>
                        `}
                    </div>
                </div>
                ${event.status === 'rejected' && event.rejection_reason ? `
                    <div class="bg-red-50 p-4 rounded-lg border border-red-200 mt-4">
                        <h4 class="font-medium text-red-800 mb-2">Rejection Reasons:</h4>
                        <p class="text-sm text-red-700 whitespace-pre-line">${event.rejection_reason}</p>
                    </div>
                ` : event.status === 'completed' && event.attendance ? `
                    <div class="bg-gray-50 p-3 rounded-lg border border-gray-200 mt-4">
                        <div class="text-sm text-gray-700">
                            <strong>Event Completed:</strong> ${event.attendance}/${event.capacity} attended (${Math.round(event.attendance/event.capacity*100)}%) • ${event.rating}/5 rating
                        </div>
                    </div>
                ` : event.status === 'published' && event.is_live ? `
                    <div class="bg-orange-50 p-3 rounded-lg border border-orange-200 mt-4">
                        <div class="text-sm text-orange-700">
                            <strong>Live Event Status:</strong> ${event.checked_in} checked in • Event is currently in progress
                        </div>
                    </div>
                ` : ''}
            </div>
        `;
        container.innerHTML += eventCardHtml;
    });
}

// Filter functions
function filterByStatus(status) {
    currentFilter = status;

    // Update active tab
    document.querySelectorAll('.filter-tab').forEach(tab => {
        tab.classList.remove('border-primary', 'text-primary');
        tab.classList.add('border-transparent', 'text-gray-500');
    });

    event.target.classList.add('border-primary', 'text-primary');
    event.target.classList.remove('border-transparent', 'text-gray-500');

    renderEvents();
}

function filterEvents() {
    // Blank - functionality later
    renderEvents();
}

// Modal functions
function openApproveEventModal(eventId, title, staff, dateTime) {
    document.getElementById('approveEventId').value = eventId;
    document.getElementById('approveEventTitle').textContent = title;
    document.getElementById('approveEventStaff').textContent = staff;
    document.getElementById('approveEventDateTime').textContent = dateTime;
    document.getElementById('approveEventModal').classList.remove('hidden');
}

function closeApproveEventModal() {
    document.getElementById('approveEventModal').classList.add('hidden');
    document.getElementById('approveEventForm').reset();
}

function openRejectEventModal(eventId, title) {
    document.getElementById('rejectEventId').value = eventId;
    document.getElementById('rejectEventTitle').textContent = title;
    document.getElementById('rejectEventModal').classList.remove('hidden');
}

function closeRejectEventModal() {
    document.getElementById('rejectEventModal').classList.add('hidden');
    document.getElementById('rejectEventForm').reset();
}

// Action functions (blank - functionality later)
function viewEventDetails(eventId) {
    console.log('View event details:', eventId);
}

function featureEvent(eventId) {
    console.log('Feature event:', eventId);
}

function unpublishEvent(eventId) {
    console.log('Unpublish event:', eventId);
}

// Form submissions (blank - functionality later)
document.getElementById('approveEventForm')?.addEventListener('submit', function (e) {
    e.preventDefault();
    console.log('Approve event form submitted');
});

document.getElementById('rejectEventForm')?.addEventListener('submit', function (e) {
    e.preventDefault();
    console.log('Reject event form submitted');
});

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    renderEvents();
});
