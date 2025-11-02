// Sample job data
let jobs = [
    {
        id: 1,
        title: "Senior PHP Developer",
        company: "Tech Solutions Inc.",
        location: "Makati City, Philippines",
        salary_min: 80000,
        salary_max: 120000,
        job_type: "fulltime",
        status: "pending",
        description: "We are looking for an experienced PHP developer with 5+ years of experience in Laravel framework to lead our development team.",
        requirements: "5+ years PHP experience, Laravel expertise, Leadership skills, Problem-solving",
        skills: "PHP, Laravel, MySQL, Git, Docker",
        submitted_date: "2 hours ago",
    },
    {
        id: 2,
        title: "UI/UX Designer Intern",
        company: "Creative Agency Co.",
        location: "BGC, Taguig",
        salary_min: 0,
        salary_max: 0,
        job_type: "internship",
        status: "pending",
        description: "Join our creative team as an internship and work on real-world design projects for various clients.",
        requirements: "Proficiency in design tools, Creative mindset, Willingness to learn",
        skills: "Figma, Adobe XD, UI Design, Wireframing, Prototyping",
        submitted_date: "4 hours ago",
    },
    {
        id: 3,
        title: "Full Stack Developer",
        company: "Innovation Labs",
        location: "Quezon City",
        salary_min: 50000,
        salary_max: 80000,
        job_type: "fulltime",
        status: "approved",
        description: "We are seeking a talented full stack developer to build scalable web applications.",
        requirements: "3+ years experience, Full stack knowledge, Problem-solving",
        skills: "JavaScript, React, Node.js, PostgreSQL, AWS",
        submitted_date: "1 day ago",
    },
];

let currentFilter = 'all';

// Render job cards
function renderJobs() {
    const container = document.getElementById('jobCardsContainer');
    container.innerHTML = '';

    jobs.forEach(job => {
        if (currentFilter !== 'all' && job.status !== currentFilter) return;

        const jobCardHtml = `
            <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition border-l-4 ${
                job.status === 'pending' ? 'border-yellow-400' :
                job.status === 'approved' ? 'border-green-400' : 'border-red-400'
            }">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <h3 class="text-lg font-semibold text-gray-900">${job.title}</h3>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full ${
                                    job.status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                    job.status === 'approved' ? 'bg-green-100 text-green-800' :
                                    'bg-red-100 text-red-800'
                                }">
                                    ${job.status.charAt(0).toUpperCase() + job.status.slice(1)}
                                </span>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                    ${job.job_type.charAt(0).toUpperCase() + job.job_type.slice(1)}
                                </span>
                            </div>
                            <p class="text-gray-600"><strong>${job.company}</strong> • ${job.location}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">${job.submitted_date}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4 text-sm">
                        <div>
                            <p class="text-gray-600">Salary Range</p>
                            <p class="font-semibold text-gray-900">₱${job.salary_min.toLocaleString()} - ₱${job.salary_max.toLocaleString()}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Employment Type</p>
                            <p class="font-semibold text-gray-900">${job.job_type}</p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <p class="text-gray-700 text-sm line-clamp-2">${job.description}</p>
                    </div>

                    <div class="mb-4">
                        <p class="text-sm font-semibold text-gray-900 mb-2">Key Requirements</p>
                        <div class="flex flex-wrap gap-2">
                            ${job.requirements.split(',').map(req => `<span class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded">${req.trim()}</span>`).join('')}
                        </div>
                    </div>

                    <div class="mb-4">
                        <p class="text-sm font-semibold text-gray-900 mb-2">Required Skills</p>
                        <div class="flex flex-wrap gap-2">
                            ${job.skills.split(',').map(skill => `<span class="px-2 py-1 text-xs bg-blue-50 text-blue-700 rounded-full">${skill.trim()}</span>`).join('')}
                        </div>
                    </div>

                    <div class="flex gap-2 pt-4 border-t">
                        ${job.status === 'pending' ? `
                            <button onclick="previewJob(${job.id})" class="flex-1 px-3 py-2 bg-gray-100 text-gray-700 text-sm rounded hover:bg-gray-200">Preview</button>
                            <button onclick="openApproveModal(${job.id}, '${job.title}', '${job.company}')" class="flex-1 px-3 py-2 bg-green-600 text-white text-sm rounded hover:bg-green-700">Approve & Publish</button>
                            <button onclick="openRejectModal(${job.id})" class="flex-1 px-3 py-2 bg-red-600 text-white text-sm rounded hover:bg-red-700">Reject</button>
                        ` : job.status === 'approved' ? `
                            <button onclick="viewPublished(${job.id})" class="flex-1 px-3 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">View Published</button>
                            <button onclick="featureJob(${job.id})" class="flex-1 px-3 py-2 bg-yellow-600 text-white text-sm rounded hover:bg-yellow-700">Feature</button>
                            <button onclick="unpublishJob(${job.id})" class="px-3 py-2 bg-gray-600 text-white text-sm rounded hover:bg-gray-700">Unpublish</button>
                        ` : `
                            <button onclick="previewJob(${job.id})" class="flex-1 px-3 py-2 bg-gray-100 text-gray-700 text-sm rounded hover:bg-gray-200">Preview</button>
                            <button onclick="reactivateJob(${job.id})" class="flex-1 px-3 py-2 bg-green-600 text-white text-sm rounded hover:bg-green-700">Reactivate</button>
                        `}
                    </div>
                </div>
            </div>
        `;
        container.innerHTML += jobCardHtml;
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

    renderJobs();
}

function filterJobs() {
    // Blank - functionality later
    renderJobs();
}

// Modal functions
function openApproveModal(jobId, title, company) {
    document.getElementById('approveJobId').value = jobId;
    document.getElementById('approveJobTitle').textContent = title;
    document.getElementById('approveJobCompany').textContent = company;
    document.getElementById('approveModal').classList.remove('hidden');
}

function closeApproveModal() {
    document.getElementById('approveModal').classList.add('hidden');
    document.getElementById('approveJobForm').reset();
}

function openRejectModal(jobId) {
    document.getElementById('rejectJobId').value = jobId;
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.getElementById('rejectJobForm').reset();
}

// Action functions (blank - functionality later)
function previewJob(jobId) {
    console.log('Preview job:', jobId);
}

function viewPublished(jobId) {
    console.log('View published:', jobId);
}

function featureJob(jobId) {
    console.log('Feature job:', jobId);
}

function unpublishJob(jobId) {
    console.log('Unpublish job:', jobId);
}

function reactivateJob(jobId) {
    console.log('Reactivate job:', jobId);
}

// Form submissions (blank - functionality later)
document.getElementById('approveJobForm')?.addEventListener('submit', function (e) {
    e.preventDefault();
    console.log('Approve job form submitted');
});

document.getElementById('rejectJobForm')?.addEventListener('submit', function (e) {
    e.preventDefault();
    console.log('Reject job form submitted');
});

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    renderJobs();
});
