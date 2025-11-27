@extends('layouts.partner')

@section('title', 'Partner Dashboard - PCU-DASMA Connect')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Welcome Section -->
    <div class="mb-8">
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white rounded-lg p-8">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold mb-2">Welcome, {{ $partner->partnerProfile?->company_name ?? 'Company' }}! 👋</h1>
                    <p class="text-blue-100">{{ $partner->partnerProfile?->company_name ?? 'Company' }} - Partner Dashboard</p>
                </div>
                <a href="{{ route('partner.profile.show') }}" class="text-sm bg-white/20 hover:bg-white/30 px-4 py-2 rounded-lg transition">
                    Company Profile
                </a>
            </div>
        </div>
    </div>

    <!-- SECTION 1: QUICK ACTIONS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <a href="{{ route('partner.job-postings.create') }}" class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition border-l-4 border-blue-600">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Post New Job</p>
                    <p class="font-semibold text-gray-900">Create Opportunity</p>
                </div>
            </div>
        </a>

        <a href="{{ route('partner.partnerships.create') }}" class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition border-l-4 border-green-600">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600">New Partnership</p>
                    <p class="font-semibold text-gray-900">Submit Proposal</p>
                </div>
            </div>
        </a>

        <a href="{{ route('partner.job-postings.index') }}" class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition border-l-4 border-purple-600">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-600">View All</p>
                    <p class="font-semibold text-gray-900">My Job Postings</p>
                </div>
            </div>
        </a>
    </div>

    <!-- ✅ SECTION 2: EXPORT REPORTS SECTION -->
    <div class="mb-8 bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                <svg class="w-6 h-6 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4z"></path>
                    <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h6v6H4a1 1 0 01-1-1v-4zm8-1v6h4a1 1 0 001-1v-4a1 1 0 00-1-1h-4z" clip-rule="evenodd"></path>
                </svg>
                📊 Export Reports
            </h2>
            <span class="text-xs text-gray-500">Download your data as Excel files</span>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3">
            <!-- Comprehensive Dashboard -->
            <a href="{{ route('partner.dashboard.export', ['type' => 'comprehensive']) }}"
               class="inline-flex flex-col items-center justify-center px-4 py-3 bg-gradient-to-br from-blue-50 to-blue-100 text-blue-700 rounded-lg hover:from-blue-100 hover:to-blue-200 transition border border-blue-200 transform hover:scale-105">
                <svg class="w-5 h-5 mb-1" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4z"></path>
                    <path fill-rule="evenodd" d="M3 10a1 1 0 011-1h6v6H4a1 1 0 01-1-1v-4zm8-1v6h4a1 1 0 001-1v-4a1 1 0 00-1-1h-4z" clip-rule="evenodd"></path>
                </svg>
                <span class="text-xs font-semibold text-center">Dashboard</span>
                <span class="text-xs text-blue-600">Summary</span>
            </a>

            <!-- Job Status -->
            <a href="{{ route('partner.dashboard.export-jobs') }}"
               class="inline-flex flex-col items-center justify-center px-4 py-3 bg-gradient-to-br from-green-50 to-green-100 text-green-700 rounded-lg hover:from-green-100 hover:to-green-200 transition border border-green-200 transform hover:scale-105">
                <svg class="w-5 h-5 mb-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5 4a2 2 0 012-2h6a2 2 0 012 2v12a1 1 0 110 2h-6a1 1 0 110-2h5V4H7a1 1 0 000 2v10a1 1 0 110 2V4z" clip-rule="evenodd"></path>
                </svg>
                <span class="text-xs font-semibold text-center">Job Status</span>
                <span class="text-xs text-green-600">All Jobs</span>
            </a>

            <!-- Applications by Status Dropdown -->
            <div class="relative group inline-flex flex-col items-center justify-center px-4 py-3 bg-gradient-to-br from-yellow-50 to-yellow-100 text-yellow-700 rounded-lg hover:from-yellow-100 hover:to-yellow-200 transition border border-yellow-200">
                <button type="button" onclick="toggleAppsMenu()" class="w-full flex flex-col items-center">
                    <svg class="w-5 h-5 mb-1" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 1 1 0 000-2A4 4 0 000 5v10a4 4 0 004 4h12a4 4 0 004-4V5a4 4 0 00-8 0v10H4V5z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-xs font-semibold text-center">Applications</span>
                    <span class="text-xs text-yellow-600">By Status</span>
                </button>
                <div id="appsMenu" class="hidden absolute top-full mt-2 w-48 bg-white border border-yellow-200 rounded-lg shadow-lg z-10">
                    <a href="{{ route('partner.dashboard.export-applications') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-yellow-50 first:rounded-t-lg">All Applications</a>
                    <a href="{{ route('partner.dashboard.export-applications', ['status' => 'pending']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-yellow-50">Pending Review</a>
                    <a href="{{ route('partner.dashboard.export-applications', ['status' => 'contacted']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-yellow-50">Contacted</a>
                    <a href="{{ route('partner.dashboard.export-applications', ['status' => 'approved']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-yellow-50">Approved</a>
                    <a href="{{ route('partner.dashboard.export-applications', ['status' => 'rejected']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-yellow-50 last:rounded-b-lg">Rejected</a>
                </div>
            </div>

            <!-- Partnerships by Status Dropdown -->
            <div class="relative group inline-flex flex-col items-center justify-center px-4 py-3 bg-gradient-to-br from-purple-50 to-purple-100 text-purple-700 rounded-lg hover:from-purple-100 hover:to-purple-200 transition border border-purple-200">
                <button type="button" onclick="togglePartMenu()" class="w-full flex flex-col items-center">
                    <svg class="w-5 h-5 mb-1" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13 7H7v6h6V7z"></path>
                    </svg>
                    <span class="text-xs font-semibold text-center">Partnerships</span>
                    <span class="text-xs text-purple-600">By Status</span>
                </button>
                <div id="partMenu" class="hidden absolute top-full mt-2 w-48 bg-white border border-purple-200 rounded-lg shadow-lg z-10">
                    <a href="{{ route('partner.dashboard.export-partnerships') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 first:rounded-t-lg">All Partnerships</a>
                    <a href="{{ route('partner.dashboard.export-partnerships', ['status' => 'submitted']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50">Submitted</a>
                    <a href="{{ route('partner.dashboard.export-partnerships', ['status' => 'under_review']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50">Under Review</a>
                    <a href="{{ route('partner.dashboard.export-partnerships', ['status' => 'approved']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50">Approved</a>
                    <a href="{{ route('partner.dashboard.export-partnerships', ['status' => 'completed']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-purple-50 last:rounded-b-lg">Completed</a>
                </div>
            </div>

            <!-- Jobs by Status Dropdown -->
            <div class="relative group inline-flex flex-col items-center justify-center px-4 py-3 bg-gradient-to-br from-red-50 to-red-100 text-red-700 rounded-lg hover:from-red-100 hover:to-red-200 transition border border-red-200">
                <button type="button" onclick="toggleJobsMenu()" class="w-full flex flex-col items-center">
                    <svg class="w-5 h-5 mb-1" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path>
                    </svg>
                    <span class="text-xs font-semibold text-center">Job Posts</span>
                    <span class="text-xs text-red-600">By Status</span>
                </button>
                <div id="jobsMenu" class="hidden absolute top-full mt-2 w-48 bg-white border border-red-200 rounded-lg shadow-lg z-10">
                    <a href="{{ route('partner.dashboard.export-jobs') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-red-50 first:rounded-t-lg">All Jobs</a>
                    <a href="{{ route('partner.dashboard.export-jobs', ['status' => 'pending']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-red-50">Pending Approval</a>
                    <a href="{{ route('partner.dashboard.export-jobs', ['status' => 'approved']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-red-50">Approved</a>
                    <a href="{{ route('partner.dashboard.export-jobs', ['status' => 'rejected']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-red-50">Rejected</a>
                    <a href="{{ route('partner.dashboard.export-jobs', ['status' => 'completed']) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-red-50 last:rounded-b-lg">Completed</a>
                </div>
            </div>
        </div>

        <p class="text-xs text-gray-500 mt-4">💡 Click any button above to download reports. All exports include timestamps and detailed data.</p>
    </div>

    <!-- SECTION 3: KEY STATISTICS CARDS -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
        <!-- Active Jobs -->
        <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Active Jobs</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $active_jobs }}</p>
                </div>
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13 7H7v6h6V7z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-3">
                {{ $jobs_pending_approval }} pending approval
            </p>
        </div>

        <!-- Total Applications -->
        <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Applications</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $total_applications }}</p>
                </div>
                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 1 1 0 000-2A4 4 0 000 5v10a4 4 0 004 4h12a4 4 0 004-4V5a4 4 0 00-8 0v10H4V5z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-3">
                {{ $pending_applications }} pending review
            </p>
        </div>

        <!-- Approved Applications -->
        <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Approved</p>
                    <p class="text-3xl font-bold text-green-600 mt-1">{{ $approved_applications }}</p>
                </div>
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-3">
                {{ $approval_rate }}% approval rate
            </p>
        </div>

        <!-- Contacted Applications -->
        <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Contacted</p>
                    <p class="text-3xl font-bold text-blue-600 mt-1">{{ $contacted_applications ?? 0 }}</p>
                </div>
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-3">
                Awaiting decision
            </p>
        </div>

        <!-- Rejected Applications -->
        <div class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Rejected</p>
                    <p class="text-3xl font-bold text-red-600 mt-1">{{ $rejected_applications }}</p>
                </div>
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
            <p class="text-xs text-gray-500 mt-3">
                Need follow-up
            </p>
        </div>
    </div>

    <!-- SECTION 4: JOB & PARTNERSHIP STATUS BREAKDOWN -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Job Status Breakdown -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Job Posting Status</h2>
            <div class="space-y-3">
                <div class="flex items-center justify-between pb-2 border-b">
                    <span class="text-sm text-gray-600">Pending Approval</span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                        {{ $job_status_stats['pending'] ?? 0 }}
                    </span>
                </div>
                <div class="flex items-center justify-between pb-2 border-b">
                    <span class="text-sm text-gray-600">Approved</span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        {{ $job_status_stats['approved'] ?? 0 }}
                    </span>
                </div>
                <div class="flex items-center justify-between pb-2 border-b">
                    <span class="text-sm text-gray-600">Rejected</span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                        {{ $job_status_stats['rejected'] ?? 0 }}
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Completed</span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                        {{ $job_status_stats['completed'] ?? 0 }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Partnership Status Breakdown -->
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Partnership Status</h2>
            <div class="space-y-3">
                <div class="flex items-center justify-between pb-2 border-b">
                    <span class="text-sm text-gray-600">Submitted</span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        {{ $partnership_status_stats['submitted'] ?? 0 }}
                    </span>
                </div>
                <div class="flex items-center justify-between pb-2 border-b">
                    <span class="text-sm text-gray-600">Under Review</span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                        {{ $partnership_status_stats['under_review'] ?? 0 }}
                    </span>
                </div>
                <div class="flex items-center justify-between pb-2 border-b">
                    <span class="text-sm text-gray-600">Approved</span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        {{ $partnership_status_stats['approved'] ?? 0 }}
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Completed</span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                        {{ $partnership_status_stats['completed'] ?? 0 }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- SECTION 5: ALERTS & NOTIFICATIONS -->
    @if ($alerts['jobs_pending_approval'] > 0 || $alerts['partnerships_pending_approval'] > 0 || $alerts['applications_pending_review'] > 0 || $alerts['applications_nearing_deadline'] > 0)
        <div class="mb-8">
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-yellow-400 mt-0.5 flex-shrink-0 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <div class="flex-1">
                        <h3 class="text-sm font-medium text-yellow-800">Action Required</h3>
                        <div class="mt-2 text-sm text-yellow-700 space-y-1">
                            @if ($alerts['jobs_pending_approval'] > 0)
                                <p>📋 <strong>{{ $alerts['jobs_pending_approval'] }}</strong> job posting(s) pending admin approval</p>
                            @endif
                            @if ($alerts['jobs_rejected'] > 0)
                                <p>⚠️ <strong>{{ $alerts['jobs_rejected'] }}</strong> job posting(s) rejected - requires revision</p>
                            @endif
                            @if ($alerts['partnerships_pending_approval'] > 0)
                                <p>🤝 <strong>{{ $alerts['partnerships_pending_approval'] }}</strong> partnership proposal(s) under review</p>
                            @endif
                            @if ($alerts['applications_pending_review'] > 0)
                                <p>👥 <strong>{{ $alerts['applications_pending_review'] }}</strong> application(s) awaiting your review</p>
                            @endif
                            @if ($alerts['applications_nearing_deadline'] > 0)
                                <p>⏰ <strong>{{ $alerts['applications_nearing_deadline'] }}</strong> job deadline(s) in next 7 days</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- SECTION 6: JOB PERFORMANCE ANALYTICS -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Top Performing Jobs -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Top Job Postings</h2>
                <a href="{{ route('partner.job-postings.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">View all →</a>
            </div>

            <div class="space-y-4">
                @forelse ($job_performance as $job)
                    <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h3 class="font-medium text-gray-900">{{ $job['title'] }}</h3>
                                <p class="text-xs text-gray-500 mt-1">{{ $job['views_count'] }} views</p>
                            </div>
                            <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded">
                                {{ $job['approval_rate'] }}% approval
                            </span>
                        </div>

                        <div class="grid grid-cols-4 gap-2 mb-3 text-sm">
                            <div class="text-center">
                                <p class="text-gray-600 text-xs">Applications</p>
                                <p class="text-lg font-bold text-gray-900">{{ $job['total_applications'] }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-gray-600 text-xs">Approved</p>
                                <p class="text-lg font-bold text-green-600">{{ $job['approved'] }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-gray-600 text-xs">Pending</p>
                                <p class="text-lg font-bold text-yellow-600">{{ $job['pending'] }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-gray-600 text-xs">Rejected</p>
                                <p class="text-lg font-bold text-red-600">{{ $job['rejected'] }}</p>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full transition-all" style="width: {{ $job['approval_rate'] }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-8">No approved job postings yet</p>
                @endforelse
            </div>
        </div>

        <!-- Recent Approvals -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Recent Approvals</h2>
                <a href="{{ route('partner.job-postings.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">View all →</a>
            </div>

            <div class="space-y-3">
                @forelse ($recent_approvals as $approval)
                    <div class="flex items-start gap-3 pb-3 border-b last:border-b-0">
                        <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $approval->applicant->name }}</p>
                            <p class="text-xs text-gray-600 truncate">for {{ $approval->jobPosting->title }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $approval->reviewed_at?->diffForHumans() ?? 'Recently' }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-8">No recent approvals</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- SECTION 7: RECENT ACTIVITY & PARTNERSHIPS -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Applications -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Recent Applications</h2>
                <a href="{{ route('partner.job-postings.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">View all →</a>
            </div>

            <div class="space-y-3">
                @forelse ($recent_applications as $app)
                    <div class="flex items-start gap-3 pb-3 border-b last:border-b-0">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0 text-xs font-bold text-blue-600">
                            {{ substr($app->applicant->name, 0, 1) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ $app->applicant->name }}</p>
                            <p class="text-xs text-gray-600 truncate">applied for {{ $app->jobPosting->title }}</p>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-xs px-2 py-0.5 rounded-full
                                    @if($app->status === 'approved') bg-green-100 text-green-800
                                    @elseif($app->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($app->status === 'contacted') bg-blue-100 text-blue-800
                                    @elseif($app->status === 'rejected') bg-red-100 text-red-800
                                    @else bg-purple-100 text-purple-800 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $app->status)) }}
                                </span>
                                <a href="{{ route('partner.applications.show', $app) }}" class="text-xs text-blue-600 hover:text-blue-800">
                                    View →
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-8">No recent applications</p>
                @endforelse
            </div>
        </div>

        <!-- Partnerships Overview -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Active Partnerships</h2>
                <a href="{{ route('partner.partnerships.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">View all →</a>
            </div>

            <div class="space-y-3">
                @forelse ($recent_partnerships as $partnership)
                    <div class="border border-gray-200 rounded-lg p-4 hover:border-green-300 transition">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-medium text-gray-900 pr-2">{{ $partnership->activity_title }}</h3>
                            <span class="text-xs px-2 py-1 rounded-full flex-shrink-0
                                @if($partnership->status === 'approved') bg-green-100 text-green-800
                                @elseif($partnership->status === 'submitted') bg-yellow-100 text-yellow-800
                                @elseif($partnership->status === 'under_review') bg-blue-100 text-blue-800
                                @elseif($partnership->status === 'completed') bg-purple-100 text-purple-800
                                @elseif($partnership->status === 'rejected') bg-red-100 text-red-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst(str_replace('_', ' ', $partnership->status)) }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600">{{ $partnership->organization_name }}</p>
                        <p class="text-xs text-gray-500 mt-2">📅 {{ $partnership->activity_date->format('M d, Y') }}</p>
                    </div>
                @empty
                    <p class="text-gray-500 text-center py-8">No partnerships yet</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Dropdown Menu Scripts -->
<script>
    function toggleAppsMenu() {
        const menu = document.getElementById('appsMenu');
        menu.classList.toggle('hidden');
    }

    function togglePartMenu() {
        const menu = document.getElementById('partMenu');
        menu.classList.toggle('hidden');
    }

    function toggleJobsMenu() {
        const menu = document.getElementById('jobsMenu');
        menu.classList.toggle('hidden');
    }

    // Close menus when clicking outside
    document.addEventListener('click', function(event) {
        const appsMenu = document.getElementById('appsMenu');
        const partMenu = document.getElementById('partMenu');
        const jobsMenu = document.getElementById('jobsMenu');

        if (!event.target.closest('.relative')) {
            appsMenu.classList.add('hidden');
            partMenu.classList.add('hidden');
            jobsMenu.classList.add('hidden');
        }
    });
</script>

@endsection
