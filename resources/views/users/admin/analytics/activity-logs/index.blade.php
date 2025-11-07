@extends('layouts.admin')

@section('title', 'Activity Logs - PCU-DASMA Connect')
@section('page-title', 'Activity Logs')

@section('content')
<!-- Header -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Activity Logs</h1>
        <p class="text-gray-600">Track all system activities and user actions</p>
    </div>
    <div class="flex space-x-3">
        <form action="{{ route('admin.activity-logs.export') }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="px-4 py-2 bg-green-600 text-white text-sm rounded-md hover:bg-green-700 flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2m0 0v-8m0 8l-6-4m6 4l6-4"></path>
                </svg>
                Export CSV
            </button>
        </form>
    </div>
</div>

<!-- Filters -->
<div class="bg-white shadow-sm rounded-lg p-6 mb-6">
    <form method="GET" action="{{ route('admin.activity-logs') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Action</label>
            <select name="action" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary">
                <option value="">All Actions</option>
                @foreach(['created', 'updated', 'deleted', 'approved', 'rejected', 'published'] as $action)
                    <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                        {{ ucfirst($action) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
            <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
            <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary">
        </div>

        <div class="flex items-end">
            <button type="submit" class="w-full px-4 py-2 bg-primary text-white rounded-md hover:bg-blue-700">
                Filter
            </button>
        </div>
    </form>
</div>

<!-- Activity Logs Table -->
<div class="bg-white shadow-sm rounded-lg overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Recent Activities</h3>
    </div>

    <div class="overflow-x-auto">
        @include('users.admin.analytics.activity-logs._table')
    </div>

    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-gray-200 flex justify-between items-center">
        <div class="text-sm text-gray-700">
            Showing {{ $logs->firstItem() ?? 0 }} to {{ $logs->lastItem() ?? 0 }} of {{ $logs->total() }} logs
        </div>
        <div>
            {{ $logs->links() }}
        </div>
    </div>
</div>

<!-- Log Details Modal -->
<div id="logDetailsModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <div class="relative bg-white rounded-lg max-w-2xl w-full shadow-xl">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-xl font-semibold text-gray-900">Log Details</h3>
                <button onclick="closeLogDetailsModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-6" id="logDetailsContent">
                <!-- Content loaded via AJAX -->
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/admin/activity-logs.js') }}"></script>
@endsection
