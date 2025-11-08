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

    <!-- Search Bar -->
    <div class="mb-6">
        <input type="text" id="searchInput" placeholder="Search by job title or department..."
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary text-sm"
            oninput="filterPostings()">
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
                data-status="{{ $job->status }}" data-sub-status="{{ $job->sub_status ?? 'active' }}" data-id="{{ $job->id }}">

                <div class="flex flex-col lg:flex-row lg:justify-between lg:items-start mb-4">
                    <div class="flex-1">
                        <!-- Status Badges -->
                        <div class="flex flex-wrap items-center mb-2">
                            @if($job->status === 'pending')
                                <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs mr-3 mb-1">Pending</span>
                            @elseif($job->status === 'approved')
                                <span class="@if($job->sub_status === 'paused') bg-blue-100 text-blue-800 @else bg-green-100 text-green-800 @endif px-2 py-1 rounded-full text-xs mr-3 mb-1">
                                    Approved (@if($job->sub_status === 'paused') Paused @else Active @endif)
                                </span>
                            @elseif($job->status === 'rejected')
                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs mr-3 mb-1">Rejected</span>
                            @else
                                <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs mr-3 mb-1">Completed</span>
                            @endif

                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs mr-3 mb-1">
                                {{ ucfirst(str_replace('time', '-time', $job->job_type)) }}
                            </span>
                            <span class="text-sm text-gray-500 mb-1">
                                Submitted {{ $job->created_at->format('M d, Y') }}
                            </span>
                        </div>

                        <!-- Job Title -->
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $job->title }}</h3>

                        <!-- Job Details -->
                        <p class="text-gray-600 mb-3">
                            {{ $job->department ?? 'N/A' }} • {{ $job->location ?? 'Remote' }} • {{ ucfirst(str_replace('-', ' ', $job->work_setup)) }}
                        </p>

                        <!-- Salary -->
                        <p class="text-lg font-semibold text-green-600 mb-3">
                            @if($job->is_unpaid)
                                Unpaid
                            @else
                                ₱{{ number_format($job->salary_min ?? 0) }} - ₱{{ number_format($job->salary_max ?? 0) }} @if($job->salary_period) / {{ $job->salary_period }} @endif
                            @endif
                        </p>

                        <!-- Status Message -->
                        @if($job->status === 'pending')
                            <p class="text-sm text-yellow-700 bg-yellow-50 p-3 rounded">
                                This posting is currently being reviewed by our administrators. You'll receive an email notification once it's approved.
                            </p>
                        @elseif($job->status === 'rejected')
                            <p class="text-sm text-red-700 bg-red-50 p-3 rounded">
                                <strong>Rejection Reason:</strong> {{ $job->rejection_reason ?? 'No reason provided' }}. Please review and edit to resubmit.
                            </p>
                        @elseif($job->status === 'approved')
                            <!-- Application Stats -->
                            <div class="flex flex-wrap items-center space-x-6 text-sm text-gray-500">
                                <div class="flex items-center mb-1">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    {{ $job->applications()->count() }} applications
                                </div>
                                <div class="flex items-center mb-1">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 919.288 0M15 7a3 3 0 11-6 0 3 3 0 616 0zm6 3a2 2 0 11-4 0 2 2 0 614 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    {{ $job->positions_available }} positions available
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-4 lg:mt-0 lg:ml-4 flex flex-col space-y-2 w-full sm:w-auto">
                        <a href="{{ route('partner.job-postings.show', $job->id) }}"
                            class="px-4 py-2 bg-primary text-white text-sm rounded-md hover:bg-blue-700 transition-colors duration-200 text-center">
                            View Details
                        </a>

                        @if($job->status === 'approved')
                            <a href="{{ route('partner.job-postings.applications', $job->id) }}"
                                class="px-4 py-2 bg-secondary text-white text-sm rounded-md hover:bg-blue-500 transition-colors duration-200 text-center">
                                View Applications
                            </a>

                            <a href="{{ route('partner.job-postings.edit', $job->id) }}"
                                class="px-4 py-2 bg-accent text-white text-sm rounded-md hover:bg-yellow-600 transition-colors duration-200 text-center">
                                Edit Posting
                            </a>

                            @if($job->sub_status === 'paused')
                                <form action="{{ route('partner.job-postings.resume', $job->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white text-sm rounded-md hover:bg-green-700 transition-colors duration-200">
                                        Resume Posting
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('partner.job-postings.pause', $job->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full px-4 py-2 bg-gray-600 text-white text-sm rounded-md hover:bg-gray-700 transition-colors duration-200">
                                        Pause Posting
                                    </button>
                                </form>
                            @endif

                            <form action="{{ route('partner.job-postings.close', $job->id) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to close this job posting?');">
                                @csrf
                                <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white text-sm rounded-md hover:bg-red-700 transition-colors duration-200">
                                    Close Posting
                                </button>
                            </form>
                        @elseif($job->status === 'pending' || $job->status === 'rejected')
                            <a href="{{ route('partner.job-postings.edit', $job->id) }}"
                                class="px-4 py-2 bg-accent text-white text-sm rounded-md hover:bg-yellow-600 transition-colors duration-200 text-center">
                                Edit Posting
                            </a>

                            @if($job->status === 'pending')
                                <form action="{{ route('partner.job-postings.destroy', $job->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to withdraw this job posting?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white text-sm rounded-md hover:bg-red-700 transition-colors duration-200">
                                        Withdraw
                                    </button>
                                </form>
                            @endif
                        @else
                            <a href="{{ route('partner.job-postings.applications', $job->id) }}"
                                class="px-4 py-2 bg-secondary text-white text-sm rounded-md hover:bg-blue-500 transition-colors duration-200 text-center">
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
                    class="inline-block px-6 py-2 bg-primary text-white rounded-md hover:bg-blue-700 transition-colors duration-200">
                    Create First Posting
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
