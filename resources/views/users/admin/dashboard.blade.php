@extends('layouts.admin')

@section('title', 'Admin Dashboard - PCU-DASMA Connect')
@section('page-title', 'Admin Dashboard')

@section('content')
<!-- Welcome Section -->
<div class="mb-8">
    <div class="bg-gradient-to-r from-primary to-secondary text-white rounded-lg p-6 shadow-lg">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold mb-2">Admin Dashboard</h1>
                <p class="opacity-90">Welcome back! Monitor platform activity and manage user content</p>
            </div>
            <div class="text-right">
                <p class="text-sm opacity-75">Last updated: {{ now()->format('M d, Y H:i') }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Top Stats Row 1: User Statistics -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
    <!-- Total Users -->
    <div class="bg-white p-5 rounded-lg shadow-sm border-l-4 border-blue-500 hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Users</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $total_users ?? 0 }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Active Users -->
    <div class="bg-white p-5 rounded-lg shadow-sm border-l-4 border-green-500 hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Active Users</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $active_users ?? 0 }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $inactive_users ?? 0 }} inactive</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM9 19c-4.3 0-8-1.343-8-3s3.582-3 8-3 8 1.343 8 3-3.582 3-8 3z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Pending Approvals -->
    <div class="bg-white p-5 rounded-lg shadow-sm border-l-4 border-yellow-500 hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Pending Approvals</p>
                <p class="text-2xl font-bold text-yellow-600 mt-1">{{ $pending_approvals ?? 0 }}</p>
                <p class="text-xs text-gray-500 mt-1"><a href="{{ route('admin.approvals.jobs.index') }}" class="text-primary hover:underline">View details</a></p>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Total Jobs -->
    <div class="bg-white p-5 rounded-lg shadow-sm border-l-4 border-purple-500 hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Jobs</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $total_jobs ?? 0 }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $published_jobs ?? 0 }} published</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Activity Today -->
    <div class="bg-white p-5 rounded-lg shadow-sm border-l-4 border-indigo-500 hover:shadow-md transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Activity Today</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $activity_today ?? 0 }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ $activity_week ?? 0 }} this week</p>
            </div>
            <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- User Breakdown -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Users by Role -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Users by Role</h3>
            <a href="{{ route('admin.users.index') }}" class="text-sm text-primary hover:underline">View all</a>
        </div>
        <div class="space-y-3">
            @php
                $roleColors = [
                    'admin' => 'red',
                    'staff' => 'blue',
                    'student' => 'green',
                    'partner' => 'purple',
                    'alumni' => 'indigo'
                ];
                $roleIcons = [
                    'admin' => '👨‍💼',
                    'staff' => '👩‍🏫',
                    'student' => '🎓',
                    'partner' => '🤝',
                    'alumni' => '🌟'
                ];
            @endphp
            @foreach($users_by_role as $role => $count)
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <span class="text-lg mr-2">{{ $roleIcons[$role] ?? '👤' }}</span>
                        <span class="capitalize font-medium text-gray-700">{{ $role }}</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-32 bg-gray-200 rounded-full h-2">
                            @php
                                $percentage = $total_users > 0 ? ($count / $total_users * 100) : 0;
                                $bgColor = 'bg-' . $roleColors[$role] . '-500';
                            @endphp
                            <div class="h-full rounded-full {{ $bgColor }}" style="width: {{ $percentage }}%"></div>
                        </div>
                        <span class="font-bold text-gray-900 min-w-12 text-right">{{ $count ?? 0 }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Approval Breakdown -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Pending by Type</h3>
            <span class="text-sm font-semibold text-yellow-600 bg-yellow-100 px-3 py-1 rounded-full">{{ $pending_approvals ?? 0 }} Total</span>
        </div>
        <div class="space-y-3">
            <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-red-500 rounded-full mr-3"></div>
                    <span class="font-medium text-gray-700">Pending Jobs</span>
                </div>
                <span class="font-bold text-gray-900">{{ $pending_jobs ?? 0 }}</span>
            </div>
            <div class="flex items-center justify-between p-3 bg-orange-50 rounded-lg">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-orange-500 rounded-full mr-3"></div>
                    <span class="font-medium text-gray-700">Pending Events</span>
                </div>
                <span class="font-bold text-gray-900">{{ $pending_events ?? 0 }}</span>
            </div>
            <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-yellow-500 rounded-full mr-3"></div>
                    <span class="font-medium text-gray-700">Pending Articles</span>
                </div>
                <span class="font-bold text-gray-900">{{ $pending_articles ?? 0 }}</span>
            </div>
            <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-purple-500 rounded-full mr-3"></div>
                    <span class="font-medium text-gray-700">Pending Partnerships</span>
                </div>
                <span class="font-bold text-gray-900">{{ $pending_partnerships ?? 0 }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Content Statistics -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <!-- Events Stats -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Events</h3>
            <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
        </div>
        <div class="space-y-2 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-600">Total Events</span>
                <span class="font-bold text-gray-900">{{ $total_events ?? 0 }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Published</span>
                <span class="font-bold text-green-600">{{ $published_events ?? 0 }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Featured</span>
                <span class="font-bold text-yellow-600">{{ $featured_events ?? 0 }}</span>
            </div>
            <div class="flex justify-between pt-2 border-t">
                <span class="text-gray-600">Upcoming</span>
                <span class="font-bold text-blue-600">{{ $upcoming_events ?? 0 }}</span>
            </div>
        </div>
        <a href="{{ route('admin.approvals.events.index') }}" class="text-primary text-sm mt-4 block hover:underline">View Events →</a>
    </div>

    <!-- News Stats -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">News Articles</h3>
            <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2v-5.5a2.972 2.972 0 002-2.5V9m0 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2zm0 0V5a2 2 0 012-2h2a2 2 0 012 2v6a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
        </div>
        <div class="space-y-2 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-600">Total Articles</span>
                <span class="font-bold text-gray-900">{{ $total_articles ?? 0 }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Published</span>
                <span class="font-bold text-green-600">{{ $published_articles ?? 0 }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Featured</span>
                <span class="font-bold text-yellow-600">{{ $featured_articles ?? 0 }}</span>
            </div>
            <div class="flex justify-between pt-2 border-t">
                <span class="text-gray-600">Draft</span>
                <span class="font-bold text-gray-500">{{ ($total_articles ?? 0) - ($published_articles ?? 0) }}</span>
            </div>
        </div>
        <a href="{{ route('admin.approvals.news.index') }}" class="text-primary text-sm mt-4 block hover:underline">View Articles →</a>
    </div>

    <!-- Partnerships Stats -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Partnerships</h3>
            <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
        </div>
        <div class="space-y-2 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-600">Total Partnerships</span>
                <span class="font-bold text-gray-900">{{ $total_partnerships ?? 0 }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Approved</span>
                <span class="font-bold text-green-600">{{ $approved_partnerships ?? 0 }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Completed</span>
                <span class="font-bold text-blue-600">{{ $completed_partnerships ?? 0 }}</span>
            </div>
            <div class="flex justify-between pt-2 border-t">
                <span class="text-gray-600">Pending</span>
                <span class="font-bold text-yellow-600">{{ $pending_partnerships ?? 0 }}</span>
            </div>
        </div>
        <a href="{{ route('admin.approvals.partnerships.index') }}" class="text-primary text-sm mt-4 block hover:underline">View Partnerships →</a>
    </div>
</div>

<!-- Main Grid: Pending Approvals & Recent Activity -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Pending Approvals Section -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-yellow-50 to-orange-50">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">Recent Activity</h3>
                    <a href="{{ route('admin.activity-logs') }}" class="text-sm text-primary hover:underline">View all logs</a>
                </div>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($recent_activity as $log)
                    <div class="p-4 hover:bg-gray-50 transition">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    @php
                                        $actionBadgeColors = [
                                            'created' => 'green',
                                            'updated' => 'blue',
                                            'deleted' => 'red',
                                            'approved' => 'green',
                                            'rejected' => 'red',
                                            'published' => 'green',
                                            'featured' => 'yellow',
                                            'unfeatured' => 'gray',
                                            'default' => 'gray'
                                        ];
                                        $badgeColor = $actionBadgeColors[$log->action] ?? $actionBadgeColors['default'];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $badgeColor }}-100 text-{{ $badgeColor }}-800">
                                        {{ ucfirst($log->action) }}
                                    </span>
                                    <span class="text-xs text-gray-500 ml-2">{{ $log->created_at->diffForHumans() }}</span>
                                </div>
                                <h4 class="font-medium text-gray-900 text-sm mb-1">{{ $log->description ?? 'Activity recorded' }}</h4>
                                <p class="text-xs text-gray-600">{{ $log->user?->first_name ?? 'System' }} {{ $log->user?->last_name ?? '' }}</p>
                            </div>
                            <div class="text-xs text-gray-500">{{ $log->ip_address ?? 'N/A' }}</div>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-500">
                        <svg class="w-12 h-12 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        <p>No recent activity</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Sidebar: Quick Stats & Top Users -->
    <div class="space-y-6">
        <!-- Top Active Users -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Active Users</h3>
            @if(!empty($top_active_users) && is_array($top_active_users))
                <div class="space-y-3">
                    @foreach($top_active_users as $index => $item)
                        <div class="flex items-center justify-between p-2 hover:bg-gray-50 rounded">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 text-white flex items-center justify-center text-xs font-bold">
                                    {{ $index + 1 }}
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $item['user']?->first_name ?? 'User' }} {{ $item['user']?->last_name ?? '' }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $item['user']?->email ?? '' }}</p>
                                </div>
                            </div>
                            <span class="text-sm font-bold text-primary">{{ $item['activity_count'] ?? 0 }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-sm text-gray-500">No user activity data available</p>
            @endif
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
            <div class="space-y-2">
                <a href="{{ route('admin.users.index') }}" class="flex items-center p-2 hover:bg-blue-50 rounded text-primary hover:text-blue-700 text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Create New User
                </a>
                <a href="{{ route('admin.approvals.jobs.index') }}" class="flex items-center p-2 hover:bg-purple-50 rounded text-purple-600 hover:text-purple-700 text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Approve Job Postings
                </a>
                <a href="{{ route('admin.approvals.events.index') }}" class="flex items-center p-2 hover:bg-blue-50 rounded text-blue-600 hover:text-blue-700 text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Approve Events
                </a>
                <a href="{{ route('admin.approvals.news.index') }}" class="flex items-center p-2 hover:bg-green-50 rounded text-green-600 hover:text-green-700 text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2v-5.5a2.972 2.972 0 002-2.5V9m0 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2zm0 0V5a2 2 0 012-2h2a2 2 0 012 2v6a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Approve Articles
                </a>
                <a href="{{ route('admin.activity-logs') }}" class="flex items-center p-2 hover:bg-orange-50 rounded text-orange-600 hover:text-orange-700 text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    View Activity Logs
                </a>
            </div>
        </div>

        <!-- System Status -->
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-green-500">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">System Status</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Platform Status</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                        Online
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Database</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                        Connected
                    </span>
                </div>
                <div class="flex justify-between items-center pt-2 border-t">
                    <span class="text-gray-600">Last Updated</span>
                    <span class="text-xs text-gray-500">{{ now()->format('g:i A') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Content Trends (Last 7 Days) -->
@if(!empty($content_creation_trends) && is_array($content_creation_trends))
<div class="mt-6 bg-white rounded-lg shadow-sm p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-6">Content Creation Trends (Last 7 Days)</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-gray-700">Date</th>
                    <th class="px-4 py-3 text-center font-semibold text-gray-700">Jobs</th>
                    <th class="px-4 py-3 text-center font-semibold text-gray-700">Events</th>
                    <th class="px-4 py-3 text-center font-semibold text-gray-700">Articles</th>
                    <th class="px-4 py-3 text-center font-semibold text-gray-700">Partnerships</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @foreach($content_creation_trends as $date => $trend)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-900 font-medium">{{ \Carbon\Carbon::parse($date)->format('M d, Y') }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full @if(($trend['jobs'] ?? 0) > 0) bg-purple-100 text-purple-600 @else bg-gray-100 text-gray-600 @endif">
                                {{ $trend['jobs'] ?? 0 }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full @if(($trend['events'] ?? 0) > 0) bg-blue-100 text-blue-600 @else bg-gray-100 text-gray-600 @endif">
                                {{ $trend['events'] ?? 0 }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full @if(($trend['articles'] ?? 0) > 0) bg-green-100 text-green-600 @else bg-gray-100 text-gray-600 @endif">
                                {{ $trend['articles'] ?? 0 }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full @if(($trend['partnerships'] ?? 0) > 0) bg-indigo-100 text-indigo-600 @else bg-gray-100 text-gray-600 @endif">
                                {{ $trend['partnerships'] ?? 0 }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection
