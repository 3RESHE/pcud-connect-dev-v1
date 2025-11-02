@extends('layouts.admin')

@section('title', 'Admin Dashboard - PCU-DASMA Connect')
@section('page-title', 'Admin Dashboard')

@section('content')
<!-- Welcome Section -->
<div class="mb-8">
    <div class="bg-gradient-to-r from-primary to-secondary text-white rounded-lg p-6">
        <h1 class="text-2xl font-bold mb-2">Admin Dashboard</h1>
        <p class="opacity-90">Monitor platform activity and manage user content</p>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-lg shadow-sm">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Users</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $total_users }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-sm">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Active Job Postings</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $total_jobs }}</p>
            </div>
        </div>
    </div>

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
                <p class="text-sm font-medium text-gray-600">Pending Approvals</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $pending_approvals }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-sm">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Active Users</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $active_users }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Main Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Pending Approvals -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">Pending Approvals</h3>
                    <a href="{{ route('admin.approvals.events.index') }}" class="text-sm text-primary hover:underline">View all</a>
                </div>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($recent_activity->take(3) as $log)
                    <div class="p-6">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs mr-2">{{ $log->getSubjectTypeDisplay() }}</span>
                                    <span class="text-sm text-gray-500">{{ $log->getCreatedAtDiffForHumans() }}</span>
                                </div>
                                <h4 class="text-lg font-medium text-gray-900 mb-1">{{ $log->description }}</h4>
                                <p class="text-gray-600 mb-2">{{ $log->user->full_name ?? 'System' }}</p>
                                <p class="text-sm text-gray-500">{{ $log->getActionDisplay() }}</p>
                            </div>
                            <div class="ml-4 flex space-x-2">
                                <button class="px-3 py-1 bg-green-600 text-white text-sm rounded-md hover:bg-green-700">Approve</button>
                                <button class="px-3 py-1 bg-red-600 text-white text-sm rounded-md hover:bg-red-700">Reject</button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-6 text-center text-gray-500">No pending approvals</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Recent Activity -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Recent Activity</h3>
            </div>
            <div class="p-6 space-y-4">
                @forelse($recent_activity->take(4) as $log)
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                        <div class="ml-3">
                            <p class="text-sm text-gray-900">{{ $log->description }}</p>
                            <p class="text-xs text-gray-500">{{ $log->user->full_name ?? 'System' }}</p>
                            <p class="text-xs text-gray-500">{{ $log->getCreatedAtDiffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-sm text-gray-500">No recent activity</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
