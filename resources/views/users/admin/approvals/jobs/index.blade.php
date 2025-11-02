@extends('layouts.admin')

@section('title', 'Job Post Approvals - PCU-DASMA Connect')
@section('page-title', 'Job Post Approvals')

@section('content')
<!-- Header -->
<div class="mb-8">
    <div class="bg-gradient-to-r from-primary to-secondary text-white rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-2">Job Post Approvals</h1>
        <p class="opacity-90">Review and approve job postings from partners</p>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
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
                <p class="text-2xl font-semibold text-gray-900">{{ $pending_count ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-sm">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Approved Today</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $approved_today ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-sm">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Active Postings</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $active_count ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-sm">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Rejected</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $rejected_count ?? 0 }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Search and Filter Bar -->
<div class="mb-6 flex flex-col sm:flex-row gap-4">
    <div class="flex-1">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <input
                type="text"
                id="searchInput"
                placeholder="Search by title, company, or skills..."
                oninput="filterJobs()"
                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-primary focus:border-primary"
            />
        </div>
    </div>
    <div class="flex space-x-2">
        <select id="jobTypeFilter" onchange="filterJobs()" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary">
            <option value="">All Job Types</option>
            <option value="fulltime">Full-time</option>
            <option value="parttime">Part-time</option>
            <option value="internship">Internship</option>
            <option value="contract">Contract</option>
        </select>
    </div>
</div>

<!-- Filter Tabs -->
<div class="mb-8">
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8 overflow-x-auto">
            <button onclick="filterByStatus('all')" class="filter-tab active border-b-2 border-primary text-primary py-2 px-1 text-sm font-medium whitespace-nowrap" data-status="all">
                All
            </button>
            <button onclick="filterByStatus('pending')" class="filter-tab border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-2 px-1 text-sm font-medium whitespace-nowrap" data-status="pending">
                Pending Review
            </button>
            <button onclick="filterByStatus('approved')" class="filter-tab border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-2 px-1 text-sm font-medium whitespace-nowrap" data-status="approved">
                Approved
            </button>
            <button onclick="filterByStatus('rejected')" class="filter-tab border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-2 px-1 text-sm font-medium whitespace-nowrap" data-status="rejected">
                Rejected
            </button>
        </nav>
    </div>
</div>

<!-- Job Cards -->
<div id="jobCardsContainer" class="space-y-4">
    <!-- Job cards will be rendered here -->
</div>

<!-- Modals -->
@include('users.admin.approvals.jobs._modals.reject-modal')
@include('users.admin.approvals.jobs._modals.approve-modal')

<script src="{{ asset('js/admin/jobs-approval.js') }}"></script>
@endsection
