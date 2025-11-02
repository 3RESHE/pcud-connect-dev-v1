// Sample news data
let newsArticles = [
    {
        id: 1,
        title: "Great Dasmarinas Bay Clean-up Drive: A Community Success Story",
        author: "Communications Staff",
        category: "Community Partnership",
        content: "The recent coastal clean-up partnership between PCU-DASMA and Clean Seas Alliance exceeded all expectations, with 450 volunteers collecting 3.2 tons of waste...",
        tags: "Community Partnership, Environmental, Success Story",
        status: "pending",
        submitted_date: "3 hours ago",
        is_featured: false,
    },
    {
        id: 2,
        title: "New Research Laboratory Opens in IT Department",
        author: "Maria Garcia",
        category: "University Update",
        content: "The IT Department proudly announces the opening of a new state-of-the-art research laboratory equipped with the latest technology for AI and ML research...",
        tags: "Research, Technology, IT Department",
        status: "pending",
        submitted_date: "2 hours ago",
        is_featured: false,
    },
    {
        id: 3,
        title: "Alumni Success Story: From Student to CEO",
        author: "Sarah Johnson",
        category: "Alumni",
        content: "Meet John Reyes, a PCU-DASMA alumnus who graduated in 2015 and is now leading a tech startup with 50+ employees...",
        tags: "Alumni, Success, Career",
        status: "pending",
        submitted_date: "1 hour ago",
        is_featured: false,
    },
    {
        id: 4,
        title: "PCU-DASMA Hosts International Conference on Sustainability",
        author: "Dr. Rosa Santos",
        category: "Events",
        content: "Our university successfully hosted the 5th International Conference on Sustainability with over 500 participants from 25 countries...",
        tags: "Events, Conference, Sustainability",
        status: "pending",
        submitted_date: "30 minutes ago",
        is_featured: false,
    },
    {
        id: 5,
        title: "Student Volunteer Program Reaches 1,000 Hours",
        author: "Manuel Cruz",
        category: "Community",
        content: "The student volunteer program has reached an incredible milestone of 1,000 community service hours this semester...",
        tags: "Community, Volunteer, Students",
        status: "pending",
        submitted_date: "15 minutes ago",
        is_featured: false,
    },
    {
        id: 6,
        title: "PCU-DASMA Connect Reaches 1,000+ Alumni Members",
        author: "Maria Garcia",
        category: "University Update",
        content: "We're excited to announce that PCU-DASMA Connect has successfully onboarded over 1,000 alumni members...",
        tags: "University Update, Alumni, Platform News",
        status: "published",
        submitted_date: "Oct 24, 2025",
        is_featured: true,
    },
    {
        id: 7,
        title: "Faculty Achievement: Dr. Lopez Wins International Award",
        author: "Academic Affairs",
        category: "Faculty",
        content: "Dr. Maria Lopez from the Chemistry Department has won the prestigious International Science Award for her research on sustainable materials...",
        tags: "Faculty, Achievement, Research",
        status: "published",
        submitted_date: "Oct 23, 2025",
        is_featured: false,
    },
    {
        id: 8,
        title: "Draft Article with Incomplete Information",
        author: "Staff Writer",
        category: "University Update",
        content: "This article was submitted with incomplete information and requires additional research and fact-checking...",
        tags: "Rejected, Incomplete",
        status: "rejected",
        submitted_date: "Oct 26, 2025",
        is_featured: false,
        rejection_reason: "• Incomplete content and missing sources\n• Requires additional research and fact-checking\n• Lacks proper citations and verification",
    },
];

let currentFilter = 'all';

// Render news cards
function renderNews() {
    const container = document.getElementById('newsCardsContainer');
    container.innerHTML = '';

    newsArticles.forEach(article => {
        if (currentFilter !== 'all' && article.status !== currentFilter) return;

        const newsCardHtml = `
            <div class="news-card bg-white rounded-lg shadow-sm p-6 border-l-4 ${
                article.status === 'pending' ? 'border-yellow-500' :
                article.status === 'published' ? 'border-blue-500' :
                article.status === 'rejected' ? 'border-red-500' :
                'border-green-500'
            }">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex-1">
                        <div class="flex items-center mb-3">
                            <span class="px-2 py-1 rounded-full text-xs mr-3 font-medium ${
                                article.status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                article.status === 'published' ? 'bg-blue-100 text-blue-800' :
                                article.status === 'rejected' ? 'bg-red-100 text-red-800' :
                                'bg-green-100 text-green-800'
                            }">
                                ${article.status.charAt(0).toUpperCase() + article.status.slice(1)}
                            </span>
                            ${article.is_featured ? '<span class="px-2 py-1 rounded-full text-xs mr-3 font-medium bg-purple-100 text-purple-800">Featured</span>' : ''}
                            <span class="text-sm text-gray-500">${article.submitted_date}</span>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">${article.title}</h3>
                        <div class="flex items-center text-sm text-gray-600 mb-4 space-x-6">
                            <div class="flex items-center">
                                <strong>By:</strong>&nbsp;${article.author}
                            </div>
                            <div class="flex items-center">
                                <strong>Category:</strong>&nbsp;${article.category}
                            </div>
                        </div>
                        <p class="text-gray-600 mb-4 line-clamp-2">${article.content}</p>
                        <div class="flex flex-wrap gap-2">
                            ${article.tags.split(',').map(tag => `<span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded">${tag.trim()}</span>`).join('')}
                        </div>
                    </div>
                    <div class="ml-6 flex flex-col space-y-2 min-w-0 flex-shrink-0">
                        ${article.status === 'pending' ? `
                            <button onclick="openApproveNewsModal(${article.id}, '${article.title}', '${article.author}', '${article.category}')" class="px-4 py-2 bg-green-600 text-white text-sm rounded-md hover:bg-green-700 text-center">
                                Approve & Publish
                            </button>
                            <button onclick="openRejectNewsModal(${article.id}, '${article.title}')" class="px-4 py-2 bg-red-600 text-white text-sm rounded-md hover:bg-red-700">
                                Reject
                            </button>
                        ` : article.status === 'published' ? `
                            <button onclick="viewArticle(${article.id})" class="px-4 py-2 bg-gray-600 text-white text-sm rounded-md hover:bg-gray-700 text-center">
                                View Article
                            </button>
                            <button onclick="featureArticle(${article.id}, ${!article.is_featured})" class="px-4 py-2 ${article.is_featured ? 'bg-gray-600 hover:bg-gray-700' : 'bg-indigo-600 hover:bg-indigo-700'} text-white text-sm rounded-md">
                                ${article.is_featured ? 'Unfeature' : 'Feature'}
                            </button>
                            <button onclick="unpublishArticle(${article.id})" class="px-4 py-2 border border-red-300 text-red-700 text-sm rounded-md hover:bg-red-50">
                                Unpublish
                            </button>
                        ` : `
                            <button onclick="viewArticle(${article.id})" class="px-4 py-2 bg-gray-600 text-white text-sm rounded-md hover:bg-gray-700 text-center">
                                View Article
                            </button>
                        `}
                    </div>
                </div>
                ${article.status === 'rejected' && article.rejection_reason ? `
                    <div class="bg-red-50 p-4 rounded-lg border border-red-200 mt-4">
                        <h4 class="font-medium text-red-800 mb-2">Rejection Reasons:</h4>
                        <p class="text-sm text-red-700 whitespace-pre-line">${article.rejection_reason}</p>
                    </div>
                ` : ''}
            </div>
        `;
        container.innerHTML += newsCardHtml;
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

    renderNews();
}

function filterNews() {
    // Blank - functionality later
    renderNews();
}

// Modal functions
function openApproveNewsModal(articleId, title, author, category) {
    document.getElementById('approveArticleId').value = articleId;
    document.getElementById('approveArticleTitle').textContent = title;
    document.getElementById('approveArticleAuthor').textContent = author;
    document.getElementById('approveArticleCategory').textContent = category;
    document.getElementById('approveNewsModal').classList.remove('hidden');
}

function closeApproveNewsModal() {
    document.getElementById('approveNewsModal').classList.add('hidden');
    document.getElementById('approveNewsForm').reset();
}

function openRejectNewsModal(articleId, title) {
    document.getElementById('rejectArticleId').value = articleId;
    document.getElementById('rejectArticleTitle').textContent = title;
    document.getElementById('rejectNewsModal').classList.remove('hidden');
}

function closeRejectNewsModal() {
    document.getElementById('rejectNewsModal').classList.add('hidden');
    document.getElementById('rejectNewsForm').reset();
}

// Action functions (blank - functionality later)
function viewArticle(articleId) {
    console.log('View article:', articleId);
}

function featureArticle(articleId, shouldFeature) {
    console.log('Feature article:', articleId, shouldFeature);
}

function unpublishArticle(articleId) {
    console.log('Unpublish article:', articleId);
}

// Form submissions (blank - functionality later)
document.getElementById('approveNewsForm')?.addEventListener('submit', function (e) {
    e.preventDefault();
    console.log('Approve news form submitted');
});

document.getElementById('rejectNewsForm')?.addEventListener('submit', function (e) {
    e.preventDefault();
    console.log('Reject news form submitted');
});

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    renderNews();
});
