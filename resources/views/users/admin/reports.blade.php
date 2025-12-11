@extends('layouts.admin')

@section('title', 'Reports & Analytics - PCU-DASMA Connect')
@section('page-title', 'Reports & Analytics')

@section('content')
<!-- Include Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Header Section with Export Options -->
<div class="mb-8">
    <div class="flex justify-between items-center flex-wrap gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Reports & Analytics</h1>
            <p class="text-gray-600 mt-1">Comprehensive system analytics and reporting dashboard</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <!-- Date Range Selector -->
            <select id="dateRange" onchange="changeDateRange()" class="px-4 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                <option value="today">Today</option>
                <option value="week" selected>This Week</option>
                <option value="month">This Month</option>
                <option value="quarter">This Quarter</option>
                <option value="year">This Year</option>
                <option value="all">All Time</option>
            </select>

            <!-- Report Type Selector -->
            <select id="reportType" class="px-4 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary">
                <option value="comprehensive">Comprehensive Report</option>
                <option value="users">Users Only</option>
                <option value="jobs">Jobs Only</option>
                <option value="events">Events Only</option>
                <option value="news">News Only</option>
                <option value="partnerships">Partnerships Only</option>
            </select>

            <!-- Export Excel Button -->
            <button onclick="exportReportExcel()" class="px-4 py-2 bg-green-600 text-white text-sm rounded-md hover:bg-green-700 transition flex items-center whitespace-nowrap">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 18H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Export Excel
            </button>

            <!-- Refresh Button -->
            <button onclick="location.reload()" class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition flex items-center whitespace-nowrap">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Refresh
            </button>
        </div>
    </div>
</div>

<!-- Job Applications Export Section -->
<div class="mb-8 bg-white rounded-lg shadow-sm p-6">
    <div class="flex justify-between items-center mb-4">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Job Applications Export</h2>
            <p class="text-sm text-gray-600 mt-1">Export applicant records by status</p>
        </div>
    </div>
    <div class="flex flex-wrap gap-2">
        <button onclick="exportApplications('approved')" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition inline-flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 18H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Approved ({{ $approved_applications ?? 0 }})
        </button>
        <button onclick="exportApplications('rejected')" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition inline-flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 18H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Rejected ({{ $rejected_applications ?? 0 }})
        </button>
        <button onclick="exportApplications('contacted')" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-medium rounded-lg transition inline-flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 18H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Contacted ({{ $contacted_applications ?? 0 }})
        </button>
        <button onclick="exportApplications('pending')" class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white font-medium rounded-lg transition inline-flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 18H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Pending ({{ $pending_applications ?? 0 }})
        </button>
        <button onclick="exportApplications('all')" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition inline-flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 18H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            All Applications ({{ $total_applications ?? 0 }})
        </button>
    </div>
</div>

<!-- Success/Error Messages -->
@if(session('success'))
    <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg animate-fade-in">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ session('success') }}
        </div>
    </div>
@endif

@if(session('error'))
    <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg animate-fade-in">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            {{ session('error') }}
        </div>
    </div>
@endif

@if(session('warning'))
    <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 text-yellow-700 rounded-lg animate-fade-in">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 0v2m0-6v-2m0 0V7m0 6h2m-2 0h-2"></path>
            </svg>
            {{ session('warning') }}
        </div>
    </div>
@endif

<!-- KPI Cards Section -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
    <!-- Total Users Card -->
    <div class="bg-white p-6 rounded-lg shadow-sm border-t-4 border-blue-500 hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Users</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $total_users ?? 0 }}</p>
                <p class="text-xs text-gray-500 mt-2">
                    Active: <span class="font-semibold text-green-600">{{ ($admins_count ?? 0) + ($staff_count ?? 0) }}</span>
                </p>
            </div>
            <div class="bg-blue-100 p-4 rounded-lg">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Job Applications Card -->
    <div class="bg-white p-6 rounded-lg shadow-sm border-t-4 border-indigo-500 hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Job Applications</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $total_applications ?? 0 }}</p>
                <p class="text-xs text-gray-500 mt-2">
                    Approved: <span class="font-semibold text-green-600">{{ $approved_applications ?? 0 }}</span>
                </p>
            </div>
            <div class="bg-indigo-100 p-4 rounded-lg">
                <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Active Partnerships Card -->
    <div class="bg-white p-6 rounded-lg shadow-sm border-t-4 border-green-500 hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Active Partnerships</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $active_partnerships ?? 0 }}</p>
                <p class="text-xs text-gray-500 mt-2">
                    Completed: <span class="font-semibold text-green-600">{{ $completed_partnerships ?? 0 }}</span>
                </p>
            </div>
            <div class="bg-green-100 p-4 rounded-lg">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Job Postings Card -->
    <div class="bg-white p-6 rounded-lg shadow-sm border-t-4 border-orange-500 hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Job Postings</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $active_jobs ?? 0 }}</p>
                <p class="text-xs text-gray-500 mt-2">
                    Pending: <span class="font-semibold text-orange-600">{{ $pending_jobs ?? 0 }}</span>
                </p>
            </div>
            <div class="bg-orange-100 p-4 rounded-lg">
                <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Upcoming Events Card -->
    <div class="bg-white p-6 rounded-lg shadow-sm border-t-4 border-purple-500 hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Upcoming Events</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $upcoming_events ?? 0 }}</p>
                <p class="text-xs text-gray-500 mt-2">
                    Registrations: <span class="font-semibold text-purple-600">{{ $registered_total ?? 0 }}</span>
                </p>
            </div>
            <div class="bg-purple-100 p-4 rounded-lg">
                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 9l2 2 4-4M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Additional KPI Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Published Content -->
    <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-indigo-500 hover:shadow-md transition">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Published Content</h3>
            <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m7 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div class="space-y-3">
            <div class="flex justify-between">
                <span class="text-gray-600">Jobs</span>
                <span class="font-bold text-gray-900">{{ $published_jobs ?? 0 }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Events</span>
                <span class="font-bold text-gray-900">{{ $published_events ?? 0 }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">News</span>
                <span class="font-bold text-gray-900">{{ $published_news ?? 0 }}</span>
            </div>
        </div>
    </div>

    <!-- Pending Approvals -->
    <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-yellow-500 hover:shadow-md transition">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Pending Approvals</h3>
            <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <div class="space-y-3">
            <div class="flex justify-between">
                <span class="text-gray-600">Jobs</span>
                <span class="font-bold text-yellow-600">{{ $pending_jobs ?? 0 }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Events</span>
                <span class="font-bold text-yellow-600">{{ $pending_events ?? 0 }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">News</span>
                <span class="font-bold text-yellow-600">{{ $pending_news ?? 0 }}</span>
            </div>
            <div class="flex justify-between pt-2 border-t">
                <span class="text-gray-600">Partnerships</span>
                <span class="font-bold text-yellow-600">{{ $pending_partnerships ?? 0 }}</span>
            </div>
        </div>
    </div>

    <!-- Activity Summary -->
    <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-cyan-500 hover:shadow-md transition">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Today's Activity</h3>
            <svg class="w-6 h-6 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
        </div>
        <div class="space-y-3">
            <div class="flex justify-between">
                <span class="text-gray-600">Approvals</span>
                <span class="font-bold text-cyan-600">{{ $today_approvals ?? 0 }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Registrations</span>
                <span class="font-bold text-cyan-600">{{ $today_registrations ?? 0 }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">New Jobs</span>
                <span class="font-bold text-cyan-600">{{ $today_jobs_posted ?? 0 }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- User Growth Chart -->
    <div class="bg-white p-6 rounded-lg shadow-sm">
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-900">User Growth (Last 30 Days)</h3>
            <p class="text-sm text-gray-500">Cumulative total registered users over time</p>
        </div>
        <canvas id="userGrowthChart" class="w-full"></canvas>
    </div>

    <!-- Approval Status Chart -->
    <div class="bg-white p-6 rounded-lg shadow-sm">
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Approval Status</h3>
            <p class="text-sm text-gray-500">Content pending approval by type</p>
        </div>
        <canvas id="approvalChart" class="w-full"></canvas>
    </div>

    <!-- Activity Heatmap -->
    <div class="bg-white p-6 rounded-lg shadow-sm">
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-900">System Activity by Day</h3>
            <p class="text-sm text-gray-500">Activity logs recorded last 7 days</p>
        </div>
        <canvas id="heatmapChart" class="w-full"></canvas>
    </div>

    <!-- Content Distribution -->
    <div class="bg-white p-6 rounded-lg shadow-sm">
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Content Distribution</h3>
            <p class="text-sm text-gray-500">Breakdown by content type</p>
        </div>
        <canvas id="contentChart" class="w-full"></canvas>
    </div>
</div>

<!-- Detailed Statistics Tables -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- User Statistics by Role -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-blue-100">
            <h3 class="text-lg font-semibold text-gray-900">User Statistics by Role</h3>
        </div>
        <div class="divide-y divide-gray-200">
            <!-- Students -->
            <div class="px-6 py-4 flex justify-between items-center hover:bg-blue-50 transition">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                    <span class="text-sm font-medium text-gray-700">🎓 Students</span>
                </div>
                <div class="text-right">
                    <p class="text-lg font-semibold text-gray-900">{{ $students_count ?? 0 }}</p>
                    <p class="text-xs text-gray-500">{{ $total_users > 0 ? round(($students_count ?? 0) / $total_users * 100) : 0 }}% of total</p>
                </div>
            </div>
            <!-- Alumni -->
            <div class="px-6 py-4 flex justify-between items-center hover:bg-green-50 transition">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                    <span class="text-sm font-medium text-gray-700">🌟 Alumni</span>
                </div>
                <div class="text-right">
                    <p class="text-lg font-semibold text-gray-900">{{ $alumni_count ?? 0 }}</p>
                    <p class="text-xs text-gray-500">{{ $total_users > 0 ? round(($alumni_count ?? 0) / $total_users * 100) : 0 }}% of total</p>
                </div>
            </div>
            <!-- Partners -->
            <div class="px-6 py-4 flex justify-between items-center hover:bg-orange-50 transition">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-orange-500 rounded-full mr-3"></div>
                    <span class="text-sm font-medium text-gray-700">🤝 Partners</span>
                </div>
                <div class="text-right">
                    <p class="text-lg font-semibold text-gray-900">{{ $partners_count ?? 0 }}</p>
                    <p class="text-xs text-gray-500">{{ $total_users > 0 ? round(($partners_count ?? 0) / $total_users * 100) : 0 }}% of total</p>
                </div>
            </div>
            <!-- Staff -->
            <div class="px-6 py-4 flex justify-between items-center hover:bg-purple-50 transition">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-purple-500 rounded-full mr-3"></div>
                    <span class="text-sm font-medium text-gray-700">👩‍🏫 Staff</span>
                </div>
                <div class="text-right">
                    <p class="text-lg font-semibold text-gray-900">{{ $staff_count ?? 0 }}</p>
                    <p class="text-xs text-gray-500">{{ $total_users > 0 ? round(($staff_count ?? 0) / $total_users * 100) : 0 }}% of total</p>
                </div>
            </div>
            <!-- Admins -->
            <div class="px-6 py-4 flex justify-between items-center hover:bg-red-50 transition">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-red-500 rounded-full mr-3"></div>
                    <span class="text-sm font-medium text-gray-700">👨‍💼 Admins</span>
                </div>
                <div class="text-right">
                    <p class="text-lg font-semibold text-gray-900">{{ $admins_count ?? 0 }}</p>
                    <p class="text-xs text-gray-500">{{ $total_users > 0 ? round(($admins_count ?? 0) / $total_users * 100) : 0 }}% of total</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Statistics -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-green-50 to-green-100">
            <h3 class="text-lg font-semibold text-gray-900">Content Statistics</h3>
        </div>
        <div class="divide-y divide-gray-200">
            <!-- Job Postings -->
            <div class="px-6 py-4 flex justify-between items-center hover:bg-blue-50 transition">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                    </svg>
                    <span class="text-sm font-medium text-gray-700">Job Postings</span>
                </div>
                <div class="text-right">
                    <p class="text-lg font-semibold text-gray-900">{{ $total_jobs ?? 0 }}</p>
                    <p class="text-xs text-gray-500">{{ $published_jobs ?? 0 }} approved • {{ $featured_jobs ?? 0 }} featured</p>
                </div>
            </div>
            <!-- Events -->
            <div class="px-6 py-4 flex justify-between items-center hover:bg-green-50 transition">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 9l2 2 4-4M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span class="text-sm font-medium text-gray-700">Events</span>
                </div>
                <div class="text-right">
                    <p class="text-lg font-semibold text-gray-900">{{ $total_events ?? 0 }}</p>
                    <p class="text-xs text-gray-500">{{ $published_events ?? 0 }} published • {{ $upcoming_events ?? 0 }} upcoming</p>
                </div>
            </div>
            <!-- News Articles -->
            <div class="px-6 py-4 flex justify-between items-center hover:bg-orange-50 transition">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-orange-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                    </svg>
                    <span class="text-sm font-medium text-gray-700">News Articles</span>
                </div>
                <div class="text-right">
                    <p class="text-lg font-semibold text-gray-900">{{ $total_news ?? 0 }}</p>
                    <p class="text-xs text-gray-500">{{ $published_news ?? 0 }} published • {{ $featured_news ?? 0 }} featured</p>
                </div>
            </div>
            <!-- Partnerships -->
            <div class="px-6 py-4 flex justify-between items-center hover:bg-purple-50 transition">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span class="text-sm font-medium text-gray-700">Partnerships</span>
                </div>
                <div class="text-right">
                    <p class="text-lg font-semibold text-gray-900">{{ $total_partnerships ?? 0 }}</p>
                    <p class="text-xs text-gray-500">{{ $active_partnerships ?? 0 }} active • {{ $completed_partnerships ?? 0 }} completed</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity Snapshot -->
<div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-indigo-50 to-indigo-100">
        <h3 class="text-lg font-semibold text-gray-900">Today's Activity Snapshot</h3>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 divide-y md:divide-y-0 md:divide-x divide-gray-200">
        <!-- Approvals Today -->
        <div class="px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Items Approved</p>
                    <p class="text-3xl font-bold text-green-600">{{ $today_approvals ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- New Registrations Today -->
        <div class="px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">New Registrations</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $today_registrations ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- New Jobs Posted Today -->
        <div class="px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">New Jobs Posted</p>
                    <p class="text-3xl font-bold text-orange-600">{{ $today_jobs_posted ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Events Started Today -->
        <div class="px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Events Started</p>
                    <p class="text-3xl font-bold text-purple-600">{{ $today_events_started ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for Export Functions and Charts -->
<script>
    // Store chart instances globally
    let userGrowthChart, approvalChart, heatmapChart, contentChart;

    document.addEventListener('DOMContentLoaded', function() {
        initializeCharts();
    });

    function changeDateRange() {
        const dateRange = document.getElementById('dateRange').value;
        window.location.href = `{{ route('admin.reports') }}?range=${dateRange}`;
    }

    function exportReportExcel() {
        const reportType = document.getElementById('reportType').value;
        const dateRange = document.getElementById('dateRange').value;

        const url = new URL(`{{ route('admin.reports.export-excel') }}`);
        url.searchParams.append('type', reportType);
        url.searchParams.append('range', dateRange);

        window.location.href = url.toString();
    }

    function exportApplications(status) {
        const url = `{{ route('admin.reports.export-applications') }}?status=${status}`;
        showToast(`Downloading ${status === 'all' ? 'all' : status} applications...`, 'info');
        window.location.href = url;
    }

    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg text-white z-50 shadow-lg
        ${type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500'}`;
        toast.textContent = message;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }

    function initializeCharts() {
        // Initialize User Growth Chart
        fetch('{{ route("admin.reports.user-growth") }}')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const ctx = document.getElementById('userGrowthChart').getContext('2d');
                    userGrowthChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: 'Total Users',
                                data: data.data,
                                borderColor: 'rgb(54, 162, 235)',
                                backgroundColor: 'rgba(54, 162, 235, 0.1)',
                                tension: 0.4,
                                fill: true,
                                pointBackgroundColor: 'rgb(54, 162, 235)',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 2,
                                pointRadius: 5,
                                pointHoverRadius: 7,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                            plugins: {
                                legend: {
                                    display: true,
                                    labels: { color: '#6B7280', font: { size: 12 } }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: { color: '#6B7280' },
                                    grid: { color: '#E5E7EB' }
                                },
                                x: {
                                    ticks: { color: '#6B7280' },
                                    grid: { color: '#E5E7EB' }
                                }
                            }
                        }
                    });
                }
            })
            .catch(error => console.error('Error loading user growth chart:', error));

        // Initialize Approval Status Chart
        fetch('{{ route("admin.reports.approval-stats") }}')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const ctx = document.getElementById('approvalChart').getContext('2d');
                    approvalChart = new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                data: data.data,
                                backgroundColor: data.backgroundColor,
                                borderColor: data.borderColor,
                                borderWidth: 2,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                            plugins: {
                                legend: {
                                    display: true,
                                    labels: { color: '#6B7280', font: { size: 12 } }
                                }
                            }
                        }
                    });
                }
            })
            .catch(error => console.error('Error loading approval chart:', error));

        // Initialize Activity Heatmap Chart
        fetch('{{ route("admin.reports.activity-heatmap") }}')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const ctx = document.getElementById('heatmapChart').getContext('2d');
                    heatmapChart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: 'Activity Logs',
                                data: data.data,
                                backgroundColor: 'rgba(239, 68, 68, 0.6)',
                                borderColor: 'rgba(239, 68, 68, 1)',
                                borderWidth: 2,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                            plugins: {
                                legend: {
                                    display: true,
                                    labels: { color: '#6B7280', font: { size: 12 } }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: { color: '#6B7280' },
                                    grid: { color: '#E5E7EB' }
                                },
                                x: {
                                    ticks: { color: '#6B7280' },
                                    grid: { color: '#E5E7EB' }
                                }
                            }
                        }
                    });
                }
            })
            .catch(error => console.error('Error loading activity chart:', error));

        // Initialize Content Distribution Chart
        fetch('{{ route("admin.reports.content-distribution") }}')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const ctx = document.getElementById('contentChart').getContext('2d');
                    contentChart = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                data: data.data,
                                backgroundColor: data.backgroundColor,
                                borderColor: data.borderColor,
                                borderWidth: 2,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                            plugins: {
                                legend: {
                                    display: true,
                                    labels: { color: '#6B7280', font: { size: 12 } }
                                }
                            }
                        }
                    });
                }
            })
            .catch(error => console.error('Error loading content chart:', error));
    }

    // Add smooth transitions to KPI cards
    document.querySelectorAll('.border-t-4, .border-l-4').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
</script>

@endsection
