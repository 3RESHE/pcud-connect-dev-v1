// Sample partnership data
let partnerships = [
    {
        id: 1,
        title: "Weekend Feeding Program for Street Children",
        category: "Feeding Program",
        description: "On-site meals for children and vulnerable groups in Dasmariñas",
        status: "pending",
        submitted_date: "October 26, 2025",
        event_date: "November 1, 2025 at 10:00 AM",
        participation: "Open participation",
    },
    {
        id: 2,
        title: "School Clean-up and Maintenance Day",
        category: "Brigada Eskwela",
        description: "Community event for school repair and beautification",
        status: "discussion",
        submitted_date: "October 26, 2025",
        event_date: "November 5, 2025 at 8:00 AM",
        participation: "Open participation",
    },
    {
        id: 3,
        title: "Monthly Community Clean-up Drive",
        category: "Community Clean-up",
        description: "Ongoing environmental protection activities in local areas",
        status: "approved",
        submitted_date: "October 27, 2025",
        participation: "Open to all community members",
    },
    {
        id: 4,
        title: "Park Beautification Tree Planting Initiative",
        category: "Tree Planting",
        description: "Environmental care through tree planting in local parks",
        status: "rejected",
        submitted_date: "October 27, 2025",
        participation: "Open to community volunteers",
        rejection_reason: "Scheduling conflict with existing environmental initiatives. We recommend coordinating with the current tree-planting program for better resource utilization.",
    },
    {
        id: 5,
        title: "Weekend Feeding Program for Street Children",
        category: "Feeding Program",
        description: "Community activity providing meals to street children",
        status: "completed",
        submitted_date: "October 27, 2025",
        completion_date: "October 27, 2025",
        outcomes: "Successfully completed with positive community feedback",
    },
];

let currentFilter = 'all';

// Render partnership cards
function renderPartnerships() {
    const container = document.getElementById('partnershipCardsContainer');
    container.innerHTML = '';

    partnerships.forEach(partnership => {
        if (currentFilter !== 'all' && partnership.status !== currentFilter) return;

        const partnershipCardHtml = `
            <div class="partnership-card bg-white rounded-lg shadow-sm p-6 border-l-4 ${
                partnership.status === 'pending' ? 'border-yellow-500' :
                partnership.status === 'discussion' ? 'border-blue-500' :
                partnership.status === 'approved' ? 'border-green-500' :
                partnership.status === 'rejected' ? 'border-red-500' :
                'border-blue-400'
            }">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex-1">
                        <div class="flex items-center mb-2">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold mr-3 ${
                                partnership.status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                partnership.status === 'discussion' ? 'bg-blue-100 text-blue-800' :
                                partnership.status === 'approved' ? 'bg-green-100 text-green-800' :
                                partnership.status === 'rejected' ? 'bg-red-100 text-red-800' :
                                'bg-blue-100 text-blue-800'
                            }">
                                ${partnership.status.charAt(0).toUpperCase() + partnership.status.slice(1)}
                            </span>
                            <span class="px-2 py-1 rounded-full text-xs font-semibold mr-3 bg-purple-100 text-purple-800">
                                ${partnership.category}
                            </span>
                            <span class="text-sm text-gray-500">${partnership.submitted_date}</span>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">${partnership.title}</h3>
                        <p class="text-gray-600 mb-3">${partnership.description}</p>
                        <div class="bg-blue-50 p-4 rounded-lg mb-4">
                            <h4 class="font-medium text-blue-900 mb-2">Details:</h4>
                            <p class="text-sm text-blue-700">
                                ${partnership.status === 'pending' ? 'This partnership proposal is awaiting review. Please check the details for more information.' :
                                partnership.status === 'discussion' ? 'This proposal is under discussion. Review the details for more information.' :
                                partnership.status === 'approved' ? 'This partnership has been approved. Monitor progress and coordinate as needed.' :
                                partnership.status === 'rejected' ? 'This partnership proposal was rejected. Review the feedback provided to the partner.' :
                                'This partnership has been successfully completed. Review the outcomes.'}
                            </p>
                        </div>
                        <div class="flex items-center space-x-6 text-sm text-gray-500">
                            ${partnership.event_date ? `
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 9l2 2 4-4M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    ${partnership.event_date}
                                </div>
                            ` : ''}
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                ${partnership.participation}
                            </div>
                        </div>
                    </div>
                    <div class="ml-6 flex flex-col space-y-2 min-w-0 flex-shrink-0">
                        ${partnership.status === 'pending' ? `
                            <button onclick="openApprovePartnershipModal(${partnership.id}, '${partnership.title}', '${partnership.category}')" class="px-4 py-2 bg-green-600 text-white text-sm rounded-md hover:bg-green-700 text-center">
                                Approve
                            </button>
                            <button onclick="openRejectPartnershipModal(${partnership.id}, '${partnership.title}')" class="px-4 py-2 bg-red-600 text-white text-sm rounded-md hover:bg-red-700">
                                Reject
                            </button>
                        ` : partnership.status === 'discussion' ? `
                            <button onclick="openApprovePartnershipModal(${partnership.id}, '${partnership.title}', '${partnership.category}')" class="px-4 py-2 bg-green-600 text-white text-sm rounded-md hover:bg-green-700 text-center">
                                Approve
                            </button>
                            <button onclick="openRejectPartnershipModal(${partnership.id}, '${partnership.title}')" class="px-4 py-2 bg-red-600 text-white text-sm rounded-md hover:bg-red-700">
                                Reject
                            </button>
                        ` : partnership.status === 'approved' ? `
                            <button onclick="viewPartnershipDetails(${partnership.id})" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 text-center">
                                View Details
                            </button>
                            <button onclick="endPartnership(${partnership.id})" class="px-4 py-2 border border-gray-300 text-gray-700 text-sm rounded-md hover:bg-gray-50">
                                End Partnership
                            </button>
                        ` : `
                            <button onclick="viewPartnershipDetails(${partnership.id})" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 text-center">
                                View Details
                            </button>
                        `}
                    </div>
                </div>
                ${partnership.status === 'rejected' && partnership.rejection_reason ? `
                    <div class="bg-red-50 p-4 rounded-lg border border-red-200 mt-4">
                        <h4 class="font-medium text-red-800 mb-2">Rejection Feedback:</h4>
                        <p class="text-sm text-red-700">${partnership.rejection_reason}</p>
                    </div>
                ` : ''}
            </div>
        `;
        container.innerHTML += partnershipCardHtml;
    });
}

// Filter functions
function filterByStatus(status) {
    currentFilter = status;

    // Update active tab
    document.querySelectorAll('.partnership-filter').forEach(tab => {
        tab.classList.remove('border-primary', 'text-primary');
        tab.classList.add('border-transparent', 'text-gray-500');
    });

    event.target.classList.add('border-primary', 'text-primary');
    event.target.classList.remove('border-transparent', 'text-gray-500');

    renderPartnerships();
}

// Modal functions
function openApprovePartnershipModal(partnershipId, title, category) {
    document.getElementById('approvePartnershipId').value = partnershipId;
    document.getElementById('approvePartnershipTitle').textContent = title;
    document.getElementById('approvePartnershipCategory').textContent = category;
    document.getElementById('approvePartnershipModal').classList.remove('hidden');
}

function closeApprovePartnershipModal() {
    document.getElementById('approvePartnershipModal').classList.add('hidden');
    document.getElementById('approvePartnershipForm').reset();
}

function openRejectPartnershipModal(partnershipId, title) {
    document.getElementById('rejectPartnershipId').value = partnershipId;
    document.getElementById('rejectPartnershipTitle').textContent = title;
    document.getElementById('rejectPartnershipModal').classList.remove('hidden');
}

function closeRejectPartnershipModal() {
    document.getElementById('rejectPartnershipModal').classList.add('hidden');
    document.getElementById('rejectPartnershipForm').reset();
}

// Action functions (blank - functionality later)
function viewPartnershipDetails(partnershipId) {
    console.log('View partnership details:', partnershipId);
}

function endPartnership(partnershipId) {
    console.log('End partnership:', partnershipId);
}

// Form submissions (blank - functionality later)
document.getElementById('approvePartnershipForm')?.addEventListener('submit', function (e) {
    e.preventDefault();
    console.log('Approve partnership form submitted');
});

document.getElementById('rejectPartnershipForm')?.addEventListener('submit', function (e) {
    e.preventDefault();
    console.log('Reject partnership form submitted');
});

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    renderPartnerships();
});
