let currentFilter = 'all';

// Filter news by status
function filterNews(status) {
    currentFilter = status;

    // Update active tab
    document.querySelectorAll('.news-filter').forEach((filter) => {
        filter.classList.remove('active', 'border-primary', 'text-primary');
        filter.classList.add('border-transparent', 'text-gray-500');
        filter.setAttribute('aria-selected', 'false');
    });

    event.target.classList.add('active', 'border-primary', 'text-primary');
    event.target.classList.remove('border-transparent', 'text-gray-500');
    event.target.setAttribute('aria-selected', 'true');

    applyFilters();
}

// Apply filters (status + search)
function applyFilters() {
    const term = document.getElementById('newsSearch').value.toLowerCase();
    const articles = document.querySelectorAll('[data-status]');

    articles.forEach((article) => {
        const status = article.getAttribute('data-status');
        const title = article.querySelector('h3')?.textContent.toLowerCase() || '';
        const content = article.querySelector('p')?.textContent.toLowerCase() || '';
        const categories = Array.from(article.querySelectorAll('.flex.flex-wrap span'))
            .map(span => span.textContent.toLowerCase());

        const statusMatch = currentFilter === 'all' || status === currentFilter;
        const textMatch = term === '' ||
                         title.includes(term) ||
                         content.includes(term) ||
                         categories.some(cat => cat.includes(term));

        if (statusMatch && textMatch) {
            article.style.display = 'block';
        } else {
            article.style.display = 'none';
        }
    });
}

// Withdraw news from review
function withdrawNews(newsId) {
    if (confirm('Withdraw this article from admin review? It will return to draft status.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/staff/news/${newsId}/withdraw`;
        form.innerHTML = '@csrf';
        document.body.appendChild(form);
        form.submit();
    }
}

// Submit news for review
function submitNews(newsId) {
    if (confirm('Submit this article for admin review?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/staff/news/${newsId}/submit`;
        form.innerHTML = '@csrf';
        document.body.appendChild(form);
        form.submit();
    }
}

// Publish approved news
function publishNews(newsId) {
    if (confirm('Publish this approved article now?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/staff/news/${newsId}/publish`;
        form.innerHTML = '@csrf';
        document.body.appendChild(form);
        form.submit();
    }
}

// Initialize page
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('newsSearch');
    if (searchInput) {
        searchInput.addEventListener('input', applyFilters);
    }
    applyFilters(); // Initial apply
});
