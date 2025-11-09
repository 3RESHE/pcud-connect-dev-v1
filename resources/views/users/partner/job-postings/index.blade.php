@extends('layouts.partner')

@section('title', 'Job Postings & Opportunities - PCU-DASMA Connect')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-8">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-3xl font-bold text-gray-900">Job Postings & Opportunities</h1>
            <p class="text-gray-600">Manage your job postings and internship opportunities</p>
        </div>
        <a href="{{ route('partner.job-postings.create') }}"
            class="bg-primary text-white px-6 py-3 rounded-lg font-medium hover:bg-blue-700 flex items-center justify-center sm:justify-start transition-colors duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            New Job Posting
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Approved Stats -->
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Approved (Active/Paused)</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $approved_count }}</p>
                </div>
            </div>
        </div>

        <!-- Total Applications -->
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Applications</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $total_applications }}</p>
                </div>
            </div>
        </div>

        <!-- Pending Stats -->
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Pending Review</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $pending_count }}</p>
                </div>
            </div>
        </div>

        <!-- Rejected Stats -->
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Rejected</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $rejected_count }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- ✅ Enhanced Search & Filter Section -->
    <div class="bg-white shadow rounded-lg p-4 sm:p-6 mb-8">
        <div class="space-y-4">
            <!-- Main Search Bar -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <div class="relative">
                    <svg class="absolute left-3 top-3 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" id="searchInput" placeholder="Search by job title, department, location..."
                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-sm"
                        oninput="filterPostings()">
                </div>
            </div>

            <!-- Filters Row -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Job Type Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Job Type</label>
                    <select id="jobTypeFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary text-sm" onchange="filterPostings()">
                        <option value="">All Types</option>
                        <option value="fulltime">Full-time</option>
                        <option value="parttime">Part-time</option>
                        <option value="internship">Internship</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <!-- Experience Level Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Experience Level</label>
                    <select id="experienceLevelFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary text-sm" onchange="filterPostings()">
                        <option value="">All Levels</option>
                        <option value="entry">Entry Level</option>
                        <option value="mid">Mid Level</option>
                        <option value="senior">Senior Level</option>
                        <option value="lead">Lead/Manager</option>
                        <option value="student">Student/Fresh Graduate</option>
                    </select>
                </div>

                <!-- Work Setup Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Work Setup</label>
                    <select id="workSetupFilter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary text-sm" onchange="filterPostings()">
                        <option value="">All Setups</option>
                        <option value="onsite">On-site</option>
                        <option value="remote">Remote</option>
                        <option value="hybrid">Hybrid</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="mb-8">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8 overflow-x-auto" role="tablist">
                <button onclick="filterByStatus('all')"
                    class="posting-filter active border-b-2 border-primary text-primary py-2 px-1 text-sm font-medium whitespace-nowrap transition-colors duration-200"
                    role="tab" aria-selected="true">
                    All ({{ $total_jobs }})
                </button>
                <button onclick="filterByStatus('approved')"
                    class="posting-filter border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-2 px-1 text-sm font-medium whitespace-nowrap transition-colors duration-200"
                    role="tab" aria-selected="false">
                    Approved ({{ $approved_count }})
                </button>
                <button onclick="filterByStatus('pending')"
                    class="posting-filter border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-2 px-1 text-sm font-medium whitespace-nowrap transition-colors duration-200"
                    role="tab" aria-selected="false">
                    Pending ({{ $pending_count }})
                </button>
                <button onclick="filterByStatus('rejected')"
                    class="posting-filter border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-2 px-1 text-sm font-medium whitespace-nowrap transition-colors duration-200"
                    role="tab" aria-selected="false">
                    Rejected ({{ $rejected_count }})
                </button>
                <button onclick="filterByStatus('completed')"
                    class="posting-filter border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-2 px-1 text-sm font-medium whitespace-nowrap transition-colors duration-200"
                    role="tab" aria-selected="false">
                    Completed ({{ $completed_count }})
                </button>
            </nav>
        </div>
    </div>

    <!-- Job Postings List -->
    <div id="postingsContainer" class="space-y-6">
        @forelse($jobs as $job)
            <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 status-{{ $job->status }} @if($job->status === 'pending') border-yellow-500 @elseif($job->status === 'approved' && $job->sub_status === 'paused') border-blue-500 @elseif($job->status === 'approved') border-green-500 @elseif($job->status === 'rejected') border-red-500 @else border-gray-500 @endif"
                data-status="{{ $job->status }}"
                data-sub-status="{{ $job->sub_status ?? 'active' }}"
                data-job-type="{{ $job->job_type }}"
                data-experience-level="{{ $job->experience_level }}"
                data-work-setup="{{ $job->work_setup }}"
                data-title="{{ $job->title }}"
                data-department="{{ $job->department }}"
                data-location="{{ $job->location }}"
                data-id="{{ $job->id }}">

                <div class="flex flex-col lg:flex-row lg:justify-between lg:items-start mb-4">
                    <div class="flex-1">
                        <!-- Status Badges -->
                        <div class="flex flex-wrap items-center mb-2 gap-2">
                            @if($job->status === 'pending')
                                <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs font-medium">Pending</span>
                            @elseif($job->status === 'approved')
                                <span class="@if($job->sub_status === 'paused') bg-blue-100 text-blue-800 @else bg-green-100 text-green-800 @endif px-2 py-1 rounded-full text-xs font-medium">
                                    Approved (@if($job->sub_status === 'paused') Paused @else Active @endif)
                                </span>
                            @elseif($job->status === 'rejected')
                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-medium">Rejected</span>
                            @elseif($job->status === 'unpublished')
                                <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs font-medium">Unpublished</span>
                            @else
                                <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs font-medium">Completed</span>
                            @endif

                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-medium">
                                {{ $job->getJobTypeDisplay() }}
                            </span>
                            <span class="text-sm text-gray-500">
                                Submitted {{ $job->created_at->format('M d, Y') }}
                            </span>
                        </div>

                        <!-- Job Title -->
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $job->title }}</h3>

                        <!-- Job Details -->
                        <p class="text-gray-600 mb-3 text-sm">
                            <span class="font-medium">{{ $job->department ?? 'N/A' }}</span>
                            <span class="text-gray-400 mx-1">•</span>
                            <span>{{ $job->location ?? 'Remote' }}</span>
                            <span class="text-gray-400 mx-1">•</span>
                            <span>{{ $job->getWorkSetupDisplay() }}</span>
                            <span class="text-gray-400 mx-1">•</span>
                            <span class="text-xs bg-gray-100 px-2 py-1 rounded">{{ $job->getExperienceLevelDisplay() }}</span>
                        </p>

                        <!-- Salary -->
                        <p class="text-lg font-semibold text-green-600 mb-3">
                            @if($job->is_unpaid)
                                Unpaid
                            @else
                                ₱{{ number_format($job->salary_min ?? 0) }} - ₱{{ number_format($job->salary_max ?? 0) }}
                                @if($job->salary_period) <span class="text-sm text-gray-500">/ {{ $job->getSalaryPeriodDisplay() }}</span> @endif
                            @endif
                        </p>

                        <!-- Status Message -->
                        @if($job->status === 'pending')
                            <p class="text-sm text-yellow-700 bg-yellow-50 p-3 rounded inline-block">
                                📋 Awaiting admin review. You'll be notified once a decision is made.
                            </p>
                        @elseif($job->status === 'rejected')
                            <p class="text-sm text-red-700 bg-red-50 p-3 rounded">
                                <strong>❌ Rejection Reason:</strong> {{ $job->rejection_reason ?? 'No reason provided' }}. Edit and resubmit to try again.
                            </p>
                        @elseif($job->status === 'unpublished')
                            <p class="text-sm text-gray-700 bg-gray-50 p-3 rounded">
                                <strong>⚠️ Unpublished:</strong> {{ $job->unpublish_reason ?? 'No reason provided' }}
                            </p>
                        @elseif($job->status === 'approved')
                            <!-- Application Stats -->
                            <div class="flex flex-wrap items-center gap-6 text-sm text-gray-600">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    <strong>{{ $job->applications()->count() }}</strong> <span class="ml-1">applications</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 616 0zm6 3a2 2 0 11-4 0 2 2 0 614 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <strong>{{ $job->positions_available }}</strong> <span class="ml-1">position(s) available</span>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-4 lg:mt-0 lg:ml-4 flex flex-col space-y-2 w-full sm:w-auto">
                        <a href="{{ route('partner.job-postings.show', $job->id) }}"
                            class="px-4 py-2 bg-primary text-white text-sm rounded-md hover:bg-blue-700 transition-colors duration-200 text-center font-medium">
                            View Details
                        </a>

                        @if($job->status === 'approved')
                            <!-- ✅ NO EDIT BUTTON FOR APPROVED JOBS -->
                            <a href="{{ route('partner.job-postings.applications', $job->id) }}"
                                class="px-4 py-2 bg-blue-500 text-white text-sm rounded-md hover:bg-blue-600 transition-colors duration-200 text-center font-medium">
                                View Applications
                            </a>

                            @if($job->sub_status === 'paused')
                                <form action="{{ route('partner.job-postings.resume', $job->id) }}" method="POST" class="w-full">
                                    @csrf
                                    <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white text-sm rounded-md hover:bg-green-700 transition-colors duration-200 font-medium">
                                        Resume Posting
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('partner.job-postings.pause', $job->id) }}" method="POST" class="w-full">
                                    @csrf
                                    <button type="submit" class="w-full px-4 py-2 bg-yellow-600 text-white text-sm rounded-md hover:bg-yellow-700 transition-colors duration-200 font-medium">
                                        Pause Posting
                                    </button>
                                </form>
                            @endif

                            <form action="{{ route('partner.job-postings.close', $job->id) }}" method="POST" class="w-full" onsubmit="return confirm('Close this job posting? It will no longer accept applications.');'">
                                @csrf
                                <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white text-sm rounded-md hover:bg-red-700 transition-colors duration-200 font-medium">
                                    Close Posting
                                </button>
                            </form>

                        @elseif($job->status === 'pending' || $job->status === 'rejected')
                            <!-- ✅ EDIT BUTTON FOR PENDING/REJECTED JOBS -->
                            <a href="{{ route('partner.job-postings.edit', $job->id) }}"
                                class="px-4 py-2 bg-yellow-600 text-white text-sm rounded-md hover:bg-yellow-700 transition-colors duration-200 text-center font-medium">
                                Edit Posting
                            </a>

                            @if($job->status === 'pending')
                                <form action="{{ route('partner.job-postings.destroy', $job->id) }}" method="POST" class="w-full" onsubmit="return confirm('Withdraw this job posting? This action cannot be undone.');'">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white text-sm rounded-md hover:bg-red-700 transition-colors duration-200 font-medium">
                                        Withdraw
                                    </button>
                                </form>
                            @endif

                        @else
                            <!-- ✅ COMPLETED/UNPUBLISHED JOBS - VIEW APPLICATIONS ONLY -->
                            <a href="{{ route('partner.job-postings.applications', $job->id) }}"
                                class="px-4 py-2 bg-blue-500 text-white text-sm rounded-md hover:bg-blue-600 transition-colors duration-200 text-center font-medium">
                                View Applications
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No job postings found</h3>
                <p class="text-gray-600 mb-6">Start creating job postings to attract applicants</p>
                <a href="{{ route('partner.job-postings.create') }}"
                    class="inline-block px-6 py-2 bg-primary text-white rounded-md hover:bg-blue-700 transition-colors duration-200 font-medium">
                    Create Your First Posting
                </a>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($jobs->hasPages())
        <div class="mt-8">
            {{ $jobs->links('pagination::tailwind') }}
        </div>
    @endif
</div>

<script>
let currentFilter = 'all';

function filterByStatus(status) {
    currentFilter = status;
    updateFilters(event);
    filterPostings();
}

function updateFilters(event) {
    document.querySelectorAll('.posting-filter').forEach((filter) => {
        filter.classList.remove('active', 'border-primary', 'text-primary');
        filter.classList.add('border-transparent', 'text-gray-500');
        filter.setAttribute('aria-selected', 'false');
    });
    event.target.classList.add('border-primary', 'text-primary');
    event.target.classList.remove('border-transparent', 'text-gray-500');
    event.target.setAttribute('aria-selected', 'true');
}

function filterPostings() {
    // ✅ Get all filter values
    const searchInput = document.getElementById('searchInput').value.toLowerCase().trim();
    const jobTypeFilter = document.getElementById('jobTypeFilter').value.toLowerCase();
    const experienceLevelFilter = document.getElementById('experienceLevelFilter').value.toLowerCase();
    const workSetupFilter = document.getElementById('workSetupFilter').value.toLowerCase();

    const postings = document.querySelectorAll('#postingsContainer > div');

    let visibleCount = 0;

    postings.forEach((posting) => {
        const postingStatus = posting.getAttribute('data-status');
        const jobType = posting.getAttribute('data-job-type').toLowerCase();
        const experienceLevel = posting.getAttribute('data-experience-level').toLowerCase();
        const workSetup = posting.getAttribute('data-work-setup').toLowerCase();
        const title = posting.getAttribute('data-title').toLowerCase();
        const department = posting.getAttribute('data-department').toLowerCase();
        const location = posting.getAttribute('data-location').toLowerCase();

        // ✅ Status filter
        const matchesStatusFilter = currentFilter === 'all' || postingStatus === currentFilter;

        // ✅ Search filter (title, department, location)
        const matchesSearch = searchInput === '' ||
            title.includes(searchInput) ||
            department.includes(searchInput) ||
            location.includes(searchInput);

        // ✅ Job type filter
        const matchesJobType = jobTypeFilter === '' || jobType === jobTypeFilter;

        // ✅ Experience level filter
        const matchesExperienceLevel = experienceLevelFilter === '' || experienceLevel === experienceLevelFilter;

        // ✅ Work setup filter
        const matchesWorkSetup = workSetupFilter === '' || workSetup === workSetupFilter;

        // Apply all filters
        if (matchesStatusFilter && matchesSearch && matchesJobType && matchesExperienceLevel && matchesWorkSetup) {
            posting.classList.remove('hidden');
            visibleCount++;
        } else {
            posting.classList.add('hidden');
        }
    });

    // Show "no results" message if no postings match
    const container = document.getElementById('postingsContainer');
    let noResultsDiv = document.getElementById('noResults');

    if (visibleCount === 0 && postings.length > 0) {
        if (!noResultsDiv) {
            noResultsDiv = document.createElement('div');
            noResultsDiv.id = 'noResults';
            noResultsDiv.className = 'bg-white rounded-lg shadow-sm p-12 text-center';
            noResultsDiv.innerHTML = `
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">No job postings match your filters</h3>
                <p class="text-gray-600">Try adjusting your search criteria</p>
            `;
            container.appendChild(noResultsDiv);
        }
    } else if (noResultsDiv) {
        noResultsDiv.remove();
    }
}

// Close menus when clicking outside
document.addEventListener('click', function (event) {
    const userMenu = document.getElementById('userMenu');
    const mobileMenu = document.getElementById('mobileMenu');
    const userButton = event.target.closest('button[onclick="toggleUserMenu()"]');
    if (!userButton && userMenu && !userMenu.contains(event.target)) {
        userMenu.classList.add('hidden');
    }
});
</script>
@endsection
