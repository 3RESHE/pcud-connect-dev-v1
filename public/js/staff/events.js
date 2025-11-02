let currentFilter = 'all';
let currentEventId = null;

// Filter events function
function filterEvents(status) {
    currentFilter = status;

    document.querySelectorAll('.event-filter').forEach((filter) => {
        filter.classList.remove('active', 'border-primary', 'text-primary');
        filter.classList.add('border-transparent', 'text-gray-500');
        filter.setAttribute('aria-selected', 'false');
    });

    event.target.classList.add('active', 'border-primary', 'text-primary');
    event.target.classList.remove('border-transparent', 'text-gray-500');
    event.target.setAttribute('aria-selected', 'true');

    applyFilter();
}

// Apply filter
function applyFilter() {
    const cards = document.querySelectorAll('.event-card');

    cards.forEach((card) => {
        const cardStatus = card.getAttribute('data-status');

        if (currentFilter === 'all' || cardStatus === currentFilter) {
            card.classList.remove('hidden');
        } else {
            card.classList.add('hidden');
        }
    });
}

// Publish event function
function publishEvent(eventId) {
    currentEventId = eventId;
    const modal = document.getElementById('publishModal');
    const confirmBtn = document.getElementById('confirmPublishBtn');
    const cancelBtn = document.getElementById('cancelPublishBtn');

    modal.classList.remove('hidden');

    confirmBtn.onclick = function() {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/staff/events/${eventId}/publish`;
        form.innerHTML = '@csrf';
        document.body.appendChild(form);
        form.submit();
    };

    cancelBtn.onclick = function() {
        modal.classList.add('hidden');
    };
}

// Close modal when clicking outside
document.addEventListener('click', function (event) {
    const modal = document.getElementById('publishModal');
    if (!modal.contains(event.target) && !event.target.closest('button[onclick*="publishEvent"]')) {
        if (!modal.classList.contains('hidden')) {
            modal.classList.add('hidden');
        }
    }
});

// Handle escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modal = document.getElementById('publishModal');
        if (!modal.classList.contains('hidden')) {
            modal.classList.add('hidden');
        }
    }
});
