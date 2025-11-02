@extends('layouts.admin')

@section('title', 'Activity Logs - PCU-DASMA Connect')
@section('page-title', 'System Activity Logs')

@section('content')
<!-- Header -->
<div class="mb-8">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Activity Logs</h1>
            <p class="text-gray-600">Complete audit trail of all system activities and changes</p>
        </div>
        <div class="flex space-x-3">
            <button
                onclick="exportLogs()"
                class="px-4 py-2 bg-primary text-white text-sm rounded-md hover:bg-blue-700 flex items-center"
            >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 18H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2zM7 15H5a1 1 0 01-1-1V3a1 1 0 011-1h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2h-5.586a1 1 0 01-.707-.293l-5.414-5.414A1 1 0 007 15z"></path>
                </svg>
                Export Logs
            </button>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="mb-8 space-y-4">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none mt-1">
                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <select id="actionFilter" onchange="filterLogs()" class="mt-1 block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md text-sm leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-primary focus:border-primary">
                <option value="">All Actions</option>
                <option value="created">Created</option>
                <option value="updated">Updated</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
                <option value="deleted">Deleted</option>
                <option value="published">Published</option>
                <option value="completed">Completed</option>
                <option value="checked_in">Checked In</option>
            </select>
        </div>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none mt-1">
                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
            <select id="userFilter" onchange="filterLogs()" class="mt-1 block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md text-sm leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-primary focus:border-primary">
                <option value="">All Users</option>
                <option value="admin">Admin Actions</option>
                <option value="staff">Staff Actions</option>
                <option value="partner">Partner Actions</option>
                <option value="student">Student Actions</option>
                <option value="alumni">Alumni Actions</option>
            </select>
        </div>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none mt-1">
                <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </div>
            <select id="dateFilter" onchange="filterLogs()" class="mt-1 block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md text-sm leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-primary focus:border-primary">
                <option value="">All Time</option>
                <option value="today">Today</option>
                <option value="week">This Week</option>
                <option value="month">This Month</option>
                <option value="3months">Last 3 Months</option>
                <option value="6months">Last 6 Months</option>
            </select>
        </div>
    </div>
</div>

<!-- Quick Stats -->
<div class="mb-6 p-4 bg-white rounded-lg shadow-sm">
    <div class="flex items-center justify-between">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-6 h-6 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="ml-2">
                <p class="text-sm font-medium text-gray-600">Most Active User</p>
                <p class="text-base font-semibold text-gray-900">{{ $mostActiveUser ?? 'Admin' }}</p>
            </div>
        </div>
        <div class="text-right text-sm">
            <span class="text-gray-500">{{ $total_logs ?? 245 }} logs</span>
        </div>
    </div>
</div>

<!-- Activity Logs Table -->
<div class="bg-white shadow-sm rounded-lg overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Recent Activity</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                </tr>
            </thead>
            <tbody id="activityTableBody" class="bg-white divide-y divide-gray-200">
                <!-- Activity rows will be populated here -->
            </tbody>
        </table>
    </div>
    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-gray-200">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Showing <span class="font-medium">{{ $logs->firstItem() }}</span> to <span class="font-medium">{{ $logs->lastItem() }}</span> of <span class="font-medium">{{ $logs->total() }}</span> results
            </div>
            <nav class="flex items-center space-x-2">
                <button class="px-3 py-1 text-sm bg-white border border-gray-300 text-gray-500 rounded-md">Previous</button>
                <button class="px-3 py-1 text-sm bg-primary text-white rounded-md mx-1">1</button>
                <button class="px-3 py-1 text-sm bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 mx-1">2</button>
                <button class="px-3 py-1 text-sm bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 mx-1">3</button>
                <button class="px-3 py-1 text-sm bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">Next</button>
            </nav>
        </div>
    </div>
</div>

<!-- Export Modal -->
<div id="exportModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <div class="relative bg-white rounded-lg max-w-md w-full shadow-xl">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900">Export Activity Logs</h3>
                <button onclick="closeExportModal()" class="absolute top-0 right-0 p-2 text-gray-400 hover:text-gray-600 mt-2 mr-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-6">
                <form id="exportForm" action="" method="">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Export Format</label>
                            <select name="format" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary">
                                <option value="csv">CSV (Excel)</option>
                                <option value="pdf">PDF Report</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date Range</label>
                            <select name="date_range" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary">
                                <option value="today">Today</option>
                                <option value="week">This Week</option>
                                <option value="month">This Month</option>
                                <option value="all">All Time</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Include Details</label>
                            <select name="detail_level" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary">
                                <option value="summary">Summary</option>
                                <option value="detailed">Detailed (full records)</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="closeExportModal()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-md hover:bg-blue-700">
                            Export Logs
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/admin/activity-logs.js') }}"></script>
@endsection
