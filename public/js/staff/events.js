let currentFilter = 'all';

// Filter events by tab
function filterEvents(status) {
    currentFilter = status;

    // Update active tab styling
    document.querySelectorAll('.event-filter').forEach((filter) => {
        filter.classList.remove('active', 'border-primary', 'text-primary');
        filter.classList.add('border-transparent', 'text-gray-500');
        filter.setAttribute('aria-selected', 'false');
    });

    event.target.classList.add('active', 'border-primary', 'text-primary');
    event.target.classList.remove('border-transparent', 'text-gray-500');
    event.target.setAttribute('aria-selected', 'true');

    applyFilters();
}

// Apply status filter from dropdown
function applyStatusFilter() {
    const status = document.getElementById('statusFilter').value;
    if (status) {
        currentFilter = status;

        // Update tab styling
        document.querySelectorAll('.event-filter').forEach((filter) => {
            const filterStatus = filter.getAttribute('onclick').match(/'([^']+)'/)[1];
            if (filterStatus === status) {
                filter.classList.add('active', 'border-primary', 'text-primary');
                filter.classList.remove('border-transparent', 'text-gray-500');
                filter.setAttribute('aria-selected', 'true');
            } else {
                filter.classList.remove('active', 'border-primary', 'text-primary');
                filter.classList.add('border-transparent', 'text-gray-500');
                filter.setAttribute('aria-selected', 'false');
            }
        });
    } else {
        currentFilter = 'all';
    }
    applyFilters();
}

// Search events
function searchEvents() {
    applyFilters();
}

// Apply combined filters
function applyFilters() {
    const searchTerm = document.getElementById('searchEvents').value.toLowerCase().trim();
    const cards = document.querySelectorAll('.event-card');

    cards.forEach((card) => {
        const cardStatus = card.getAttribute('data-status');
        const title = card.querySelector('h3')?.textContent.toLowerCase() || '';
        const description = card.querySelector('p')?.textContent.toLowerCase() || '';

        const matchesFilter = currentFilter === 'all' || cardStatus === currentFilter;
        const matchesSearch = searchTerm === '' || title.includes(searchTerm) || description.includes(searchTerm);

        if (matchesFilter && matchesSearch) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

// Withdraw submission (pending events)
function withdrawSubmission(eventId) {
    if (confirm('Withdraw this event from admin review? It will return to draft status.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/staff/events/${eventId}/withdraw`;
        form.innerHTML = '@csrf';
        document.body.appendChild(form);
        form.submit();
    }
}

// Publish event (approved events)
function publishEvent(eventId) {
    if (confirm('Publish this approved event? It will become visible to students.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/staff/events/${eventId}/publish`;
        form.innerHTML = '@csrf';
        document.body.appendChild(form);
        form.submit();
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    applyFilters();
});
