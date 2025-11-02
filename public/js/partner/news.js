let currentPage = 1;
let currentSort = 'recent';
let currentCategory = '';
let currentSearch = '';

// Filter news based on search and category
function filterNews() {
    currentSearch = document.getElementById('searchInput').value.toLowerCase().trim();
    currentCategory = document.getElementById('categoryFilter').value;
    currentPage = 1;
    applyFilters();
}

// Apply filters to displayed cards
function applyFilters() {
    const cards = document.querySelectorAll('.news-card');
    let visibleCount = 0;

    cards.forEach((card) => {
        const title = card.querySelector('h3').textContent.toLowerCase();
        const content = card.querySelector('p').textContent.toLowerCase();
        const category = card.getAttribute('data-category');

        const matchesSearch = currentSearch === '' ||
                            title.includes(currentSearch) ||
                            content.includes(currentSearch);
        const matchesCategory = currentCategory === '' || category === currentCategory;

        if (matchesSearch && matchesCategory) {
            card.classList.remove('hidden');
            visibleCount++;
        } else {
            card.classList.add('hidden');
        }
    });

    // Show empty state if no results
    if (visibleCount === 0) {
        const newsGrid = document.getElementById('newsGrid');
        if (!document.querySelector('.no-results-message')) {
            const emptyMessage = document.createElement('div');
            emptyMessage.className = 'col-span-full bg-white rounded-lg shadow-sm p-12 text-center no-results-message';
            emptyMessage.innerHTML = `
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No Articles Found</h3>
                <p class="text-gray-600">No news articles match your search and filter criteria.</p>
            `;
            newsGrid.appendChild(emptyMessage);
        }
    } else {
        const noResults = document.querySelector('.no-results-message');
        if (noResults) {
            noResults.remove();
        }
    }
}

// Sort news by different criteria
function sortNews() {
    currentSort = document.getElementById('sortFilter').value;
    const cards = Array.from(document.querySelectorAll('.news-card:not(.hidden)'));
    const newsGrid = document.getElementById('newsGrid');

    cards.sort((a, b) => {
        const dateA = parseInt(a.getAttribute('data-date'));
        const dateB = parseInt(b.getAttribute('data-date'));
        const viewsA = parseInt(a.getAttribute('data-views'));
        const viewsB = parseInt(b.getAttribute('data-views'));

        if (currentSort === 'recent') {
            return dateB - dateA;
        } else if (currentSort === 'oldest') {
            return dateA - dateB;
        } else if (currentSort === 'views') {
            return viewsB - viewsA;
        }
    });

    // Re-append sorted cards
    cards.forEach((card) => {
        newsGrid.appendChild(card);
    });
}

// View news article details
function viewNews(articleId) {
    window.location.href = `/partner/news/${articleId}`;
}

// Load more news articles
function loadMoreNews() {
    currentPage++;
    fetch(`/partner/news/load-more?page=${currentPage}&category=${currentCategory}&search=${currentSearch}`)
        .then(response => response.json())
        .then(data => {
            const newsGrid = document.getElementById('newsGrid');

            // Remove "no results" message if it exists
            const noResults = document.querySelector('.no-results-message');
            if (noResults) {
                noResults.remove();
            }

            // Add new articles
            data.articles.forEach(article => {
                const card = createNewsCard(article);
                newsGrid.appendChild(card);
            });

            // Hide load more button if no more pages
            if (!data.has_more_pages) {
                const loadMoreBtn = document.querySelector('[onclick="loadMoreNews()"]');
                if (loadMoreBtn) {
                    loadMoreBtn.style.display = 'none';
                }
            }
        })
        .catch(error => console.error('Error loading more news:', error));
}

// Create news card HTML
function createNewsCard(article) {
    const div = document.createElement('div');
    div.className = 'group cursor-pointer bg-white rounded-lg shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden news-card';
    div.setAttribute('onclick', `viewNews('${article.id}')`);
    div.setAttribute('data-category', article.category);
    div.setAttribute('data-date', Math.floor(new Date(article.published_at).getTime() / 1000));
    div.setAttribute('data-views', article.views_count);

    const imageHtml = article.featured_image
        ? `<img class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300" src="/storage/${article.featured_image}" alt="${article.title}" />`
        : `<div class="w-full h-48 bg-gradient-to-br from-blue-600 to-blue-400 flex items-center justify-center">
            <svg class="w-12 h-12 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
            </svg>
          </div>`;

    const categoryColor = getCategoryColor(article.category);
    const publishDate = new Date(article.published_at).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });

    div.innerHTML = `
        ${imageHtml}
        <div class="p-6">
            <div class="flex items-center text-sm text-gray-500 mb-2">
                <span class="${categoryColor} px-2 py-1 rounded-full text-xs mr-3">
                    ${getCategoryDisplay(article.category)}
                </span>
                <span>${publishDate}</span>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2 group-hover:text-primary transition-colors">
                ${article.title}
            </h3>
            <p class="text-gray-600 text-sm mb-4">
                ${getExcerpt(article.content, 150)}
            </p>
            <div class="flex items-center justify-between text-xs text-gray-500">
                <span class="flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    ${formatViews(article.views_count)}
                </span>
                <span class="text-primary font-medium group-hover:underline">Read More</span>
            </div>
        </div>
    `;

    return div;
}

// Get category display name
function getCategoryDisplay(category) {
    const categories = {
        'university_updates': 'University Update',
        'alumni_success': 'Alumni Success',
        'campus_events': 'Campus Events',
        'partnership_highlights': 'Partnership Success',
        'general': 'General News'
    };
    return categories[category] || 'News';
}

// Get category badge color
function getCategoryColor(category) {
    const colors = {
        'university_updates': 'bg-blue-100 text-blue-800',
        'alumni_success': 'bg-yellow-100 text-yellow-800',
        'campus_events': 'bg-red-100 text-red-800',
        'partnership_highlights': 'bg-emerald-100 text-emerald-800',
        'general': 'bg-gray-100 text-gray-800'
    };
    return colors[category] || 'bg-gray-100 text-gray-800';
}

// Get text excerpt
function getExcerpt(text, length = 150) {
    const plainText = text.replace(/<[^>]*>/g, '');
    return plainText.length > length ? plainText.substring(0, length) + '...' : plainText;
}

// Format views count
function formatViews(views) {
    if (views < 1000) {
        return views.toString();
    }
    const thousands = Math.floor(views / 1000);
    const remainder = views % 1000;
    if (remainder === 0) {
        return `${thousands}K`;
    }
    const hundredths = Math.round(remainder / 100);
    return `${thousands}.${hundredths}K`;
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Any initialization code here
});
