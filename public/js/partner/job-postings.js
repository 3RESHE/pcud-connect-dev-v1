let currentFilter = 'all';
let currentJobId = null;

// Filter postings function
function filterPostings(status) {
    currentFilter = status;
    document.querySelectorAll('.job-filter').forEach((filter) => {
        filter.classList.remove('active', 'border-primary', 'text-primary');
        filter.classList.add('border-transparent', 'text-gray-500');
        filter.setAttribute('aria-selected', 'false');
    });
    event.target.classList.add('active', 'border-primary', 'text-primary');
    event.target.classList.remove('border-transparent', 'text-gray-500');
    event.target.setAttribute('aria-selected', 'true');
    applyFilterAndSearch();
}

// Search function
function searchPostings() {
    applyFilterAndSearch();
}

// Combined filter and search
function applyFilterAndSearch() {
    const searchInput = document.getElementById('searchInput').value.toLowerCase().trim();
    const postings = document.querySelectorAll('#postingsContainer > div');

    postings.forEach((posting) => {
        const postingStatus = posting.getAttribute('data-status');
        const title = posting.querySelector('h3')?.textContent.toLowerCase() || '';
        const dept = posting.querySelector('p.text-gray-600')?.textContent.toLowerCase() || '';

        const matchesFilter = currentFilter === 'all' || postingStatus === currentFilter;
        const matchesSearch = searchInput === '' || title.includes(searchInput) || dept.includes(searchInput);

        if (matchesFilter && matchesSearch) {
            posting.classList.remove('hidden');
        } else {
            posting.classList.add('hidden');
        }
    });
}

// Pause posting function
function pausePosting(postingId) {
    if (confirm('Are you sure you want to pause this job posting?')) {
        // Submit form to backend
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/partner/job-postings/${postingId}/pause`;
        form.innerHTML = '@csrf';
        document.body.appendChild(form);
        form.submit();
    }
}

// Resume posting function
function resumePosting(postingId) {
    if (confirm('Are you sure you want to resume this job posting?')) {
        // Submit form to backend
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/partner/job-postings/${postingId}/resume`;
        form.innerHTML = '@csrf';
        document.body.appendChild(form);
        form.submit();
    }
}

// Close posting function
function closePosting(postingId) {
    currentJobId = postingId;
    const modal = document.getElementById('closeModal');
    const confirmBtn = document.getElementById('confirmCloseBtn');
    const cancelBtn = document.getElementById('cancelCloseBtn');
    modal.classList.remove('hidden');

    confirmBtn.onclick = function() {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/partner/job-postings/${postingId}/close`;
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
    const modal = document.getElementById('closeModal');
    if (!modal.contains(event.target) && !event.target.closest('button[onclick*="closePosting"]')) {
        if (!modal.classList.contains('hidden')) {
            modal.classList.add('hidden');
        }
    }
});

// Handle escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modal = document.getElementById('closeModal');
        if (!modal.classList.contains('hidden')) {
            modal.classList.add('hidden');
        }
    }
});
