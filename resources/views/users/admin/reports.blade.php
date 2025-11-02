@extends('layouts.admin')

@section('title', 'Reports & Analytics - PCU-DASMA Connect')
@section('page-title', 'Reports & Analytics')

@section('content')
<!-- Header -->
<div class="mb-8">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Reports & Analytics</h1>
            <p class="text-gray-600">Comprehensive system analytics and reporting dashboard</p>
        </div>
        <div class="flex space-x-3">
            <select id="dateRange" onchange="changeDateRange()" class="px-4 py-2 border border-gray-300 rounded-md text-sm focus:outline-none focus:ring-primary focus:border-primary">
                <option value="today">Today</option>
                <option value="week" selected>This Week</option>
                <option value="month">This Month</option>
                <option value="quarter">This Quarter</option>
                <option value="year">This Year</option>
                <option value="all">All Time</option>
            </select>
            <button
                onclick="exportReport()"
                class="px-4 py-2 bg-primary text-white text-sm rounded-md hover:bg-blue-700 flex items-center"
            >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 18H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Generate Report
            </button>
        </div>
    </div>
</div>

<!-- KPI Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-lg shadow-sm border-t-4 border-blue-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Users</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $total_users ?? 1250 }}</p>
                <p class="text-xs text-green-600 mt-2">↑ 8% from last month</p>
            </div>
            <div class="bg-blue-100 p-3 rounded-lg">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-sm border-t-4 border-green-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Active Partnerships</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $active_partnerships ?? 24 }}</p>
                <p class="text-xs text-green-600 mt-2">↑ 5 new this month</p>
            </div>
            <div class="bg-green-100 p-3 rounded-lg">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-sm border-t-4 border-orange-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Job Postings</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $active_jobs ?? 156 }}</p>
                <p class="text-xs text-orange-600 mt-2">{{ $pending_jobs ?? 3 }} pending approval</p>
            </div>
            <div class="bg-orange-100 p-3 rounded-lg">
                <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-sm border-t-4 border-purple-500">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Upcoming Events</p>
                <p class="text-3xl font-bold text-gray-900 mt-2">{{ $upcoming_events ?? 12 }}</p>
                <p class="text-xs text-purple-600 mt-2">{{ $registered_total ?? 892 }} registrations</p>
            </div>
            <div class="bg-purple-100 p-3 rounded-lg">
                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 9l2 2 4-4M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- User Growth Chart -->
    <div class="bg-white p-6 rounded-lg shadow-sm">
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-900">User Growth (Last 12 Months)</h3>
            <p class="text-sm text-gray-500">Total registered users over time</p>
        </div>
        <div id="userGrowthChart" class="w-full h-64">
            <!-- Chart placeholder - Chart.js or similar would be rendered here -->
            <div class="flex items-center justify-center h-full bg-gray-50 rounded-lg">
                <p class="text-gray-400">📈 User Growth Chart</p>
            </div>
        </div>
    </div>

    <!-- Approval Status Chart -->
    <div class="bg-white p-6 rounded-lg shadow-sm">
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Approval Status</h3>
            <p class="text-sm text-gray-500">Content pending approval by type</p>
        </div>
        <div id="approvalChart" class="w-full h-64">
            <!-- Chart placeholder -->
            <div class="flex items-center justify-center h-full bg-gray-50 rounded-lg">
                <p class="text-gray-400">📊 Approval Status Chart</p>
            </div>
        </div>
    </div>

    <!-- Activity Heatmap -->
    <div class="bg-white p-6 rounded-lg shadow-sm">
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-900">System Activity Heatmap</h3>
            <p class="text-sm text-gray-500">Activity by day of week and time</p>
        </div>
        <div id="heatmapChart" class="w-full h-64">
            <!-- Chart placeholder -->
            <div class="flex items-center justify-center h-full bg-gray-50 rounded-lg">
                <p class="text-gray-400">🔥 Activity Heatmap</p>
            </div>
        </div>
    </div>

    <!-- Content Distribution -->
    <div class="bg-white p-6 rounded-lg shadow-sm">
        <div class="mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Content Distribution</h3>
            <p class="text-sm text-gray-500">Breakdown by content type</p>
        </div>
        <div id="contentChart" class="w-full h-64">
            <!-- Chart placeholder -->
            <div class="flex items-center justify-center h-full bg-gray-50 rounded-lg">
                <p class="text-gray-400">📋 Content Distribution Chart</p>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Statistics Tables -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- User Statistics -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-900">User Statistics by Role</h3>
        </div>
        <div class="divide-y divide-gray-200">
            <div class="px-6 py-4 flex justify-between items-center hover:bg-gray-50">
                <div class="flex items-center">
                    <div class="w-2 h-2 bg-blue-500 rounded-full mr-3"></div>
                    <span class="text-sm font-medium text-gray-700">Students</span>
                </div>
                <div class="text-right">
                    <p class="text-lg font-semibold text-gray-900">{{ $students_count ?? 650 }}</p>
                    <p class="text-xs text-gray-500">52% of total</p>
                </div>
            </div>
            <div class="px-6 py-4 flex justify-between items-center hover:bg-gray-50">
                <div class="flex items-center">
                    <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                    <span class="text-sm font-medium text-gray-700">Alumni</span>
                </div>
                <div class="text-right">
                    <p class="text-lg font-semibold text-gray-900">{{ $alumni_count ?? 350 }}</p>
                    <p class="text-xs text-gray-500">28% of total</p>
                </div>
            </div>
            <div class="px-6 py-4 flex justify-between items-center hover:bg-gray-50">
                <div class="flex items-center">
                    <div class="w-2 h-2 bg-orange-500 rounded-full mr-3"></div>
                    <span class="text-sm font-medium text-gray-700">Partners</span>
                </div>
                <div class="text-right">
                    <p class="text-lg font-semibold text-gray-900">{{ $partners_count ?? 120 }}</p>
                    <p class="text-xs text-gray-500">10% of total</p>
                </div>
            </div>
            <div class="px-6 py-4 flex justify-between items-center hover:bg-gray-50">
                <div class="flex items-center">
                    <div class="w-2 h-2 bg-purple-500 rounded-full mr-3"></div>
                    <span class="text-sm font-medium text-gray-700">Staff</span>
                </div>
                <div class="text-right">
                    <p class="text-lg font-semibold text-gray-900">{{ $staff_count ?? 130 }}</p>
                    <p class="text-xs text-gray-500">10% of total</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Statistics -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-900">Content Statistics</h3>
        </div>
        <div class="divide-y divide-gray-200">
            <div class="px-6 py-4 flex justify-between items-center hover:bg-gray-50">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                    </svg>
                    <span class="text-sm font-medium text-gray-700">Job Postings</span>
                </div>
                <div class="text-right">
                    <p class="text-lg font-semibold text-gray-900">{{ $total_jobs ?? 156 }}</p>
                    <p class="text-xs text-gray-500">{{ $published_jobs ?? 150 }} published</p>
                </div>
            </div>
            <div class="px-6 py-4 flex justify-between items-center hover:bg-gray-50">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 9l2 2 4-4M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span class="text-sm font-medium text-gray-700">Events</span>
                </div>
                <div class="text-right">
                    <p class="text-lg font-semibold text-gray-900">{{ $total_events ?? 28 }}</p>
                    <p class="text-xs text-gray-500">{{ $upcoming_events ?? 12 }} upcoming</p>
                </div>
            </div>
            <div class="px-6 py-4 flex justify-between items-center hover:bg-gray-50">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-orange-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                    </svg>
                    <span class="text-sm font-medium text-gray-700">News Articles</span>
                </div>
                <div class="text-right">
                    <p class="text-lg font-semibold text-gray-900">{{ $total_news ?? 89 }}</p>
                    <p class="text-xs text-gray-500">{{ $published_news ?? 85 }} published</p>
                </div>
            </div>
            <div class="px-6 py-4 flex justify-between items-center hover:bg-gray-50">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span class="text-sm font-medium text-gray-700">Partnerships</span>
                </div>
                <div class="text-right">
                    <p class="text-lg font-semibold text-gray-900">{{ $total_partnerships ?? 24 }}</p>
                    <p class="text-xs text-gray-500">{{ $active_partnerships ?? 20 }} active</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity Snapshot -->
<div class="bg-white rounded-lg shadow-sm">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Recent Activity Snapshot</h3>
    </div>
    <div class="divide-y divide-gray-200">
        <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50">
            <div class="flex items-center">
                <div class="w-2 h-2 bg-green-500 rounded-full mr-3"></div>
                <span class="text-sm text-gray-700"><strong>{{ $today_approvals ?? 12 }}</strong> items approved today</span>
            </div>
            <span class="text-xs text-gray-500">Last 24 hours</span>
        </div>
        <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50">
            <div class="flex items-center">
                <div class="w-2 h-2 bg-blue-500 rounded-full mr-3"></div>
                <span class="text-sm text-gray-700"><strong>{{ $today_registrations ?? 45 }}</strong> new registrations today</span>
            </div>
            <span class="text-xs text-gray-500">Last 24 hours</span>
        </div>
        <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50">
            <div class="flex items-center">
                <div class="w-2 h-2 bg-orange-500 rounded-full mr-3"></div>
                <span class="text-sm text-gray-700"><strong>{{ $today_jobs_posted ?? 3 }}</strong> new job postings today</span>
            </div>
            <span class="text-xs text-gray-500">Last 24 hours</span>
        </div>
        <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50">
            <div class="flex items-center">
                <div class="w-2 h-2 bg-purple-500 rounded-full mr-3"></div>
                <span class="text-sm text-gray-700"><strong>{{ $today_events_started ?? 2 }}</strong> events started today</span>
            </div>
            <span class="text-xs text-gray-500">Last 24 hours</span>
        </div>
    </div>
</div>

<script src="{{ asset('js/admin/reports.js') }}"></script>
@endsection
