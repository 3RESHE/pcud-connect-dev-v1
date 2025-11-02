@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @include('users.admin._stats-card', [
            'title' => 'Total Users',
            'value' => $total_users,
            'icon' => 'users',
            'color' => 'blue',
        ])
        @include('users.admin._stats-card', [
            'title' => 'Active Users',
            'value' => $active_users,
            'icon' => 'check-circle',
            'color' => 'green',
        ])
        @include('users.admin._stats-card', [
            'title' => 'Pending Approvals',
            'value' => $pending_approvals,
            'icon' => 'clock',
            'color' => 'yellow',
        ])
        @include('users.admin._stats-card', [
            'title' => 'Total Jobs',
            'value' => $total_jobs,
            'icon' => 'briefcase',
            'color' => 'purple',
        ])
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('admin.users.create') }}" class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6 rounded-lg shadow hover:shadow-lg transition">
            <h3 class="font-semibold mb-2">+ Create User</h3>
            <p class="text-sm text-blue-100">Add a new user to the system</p>
        </a>
        <a href="{{ route('admin.approvals.jobs') }}" class="bg-gradient-to-r from-green-500 to-green-600 text-white p-6 rounded-lg shadow hover:shadow-lg transition">
            <h3 class="font-semibold mb-2">Review Jobs</h3>
            <p class="text-sm text-green-100">{{ $pending_approvals }} items pending</p>
        </a>
        <a href="{{ route('admin.activity-logs') }}" class="bg-gradient-to-r from-purple-500 to-purple-600 text-white p-6 rounded-lg shadow hover:shadow-lg transition">
            <h3 class="font-semibold mb-2">Activity Logs</h3>
            <p class="text-sm text-purple-100">View system activities</p>
        </a>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6 border-b">
            <h3 class="text-lg font-semibold">Recent Activity</h3>
        </div>
        <div class="divide-y">
            @forelse($recent_activity as $log)
                @include('users.admin._activity-item', ['log' => $log])
            @empty
                <div class="p-6 text-center text-gray-500">
                    No recent activity
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
