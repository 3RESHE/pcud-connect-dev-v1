@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div>
    <h1 class="text-3xl font-bold mb-8">Admin Dashboard</h1>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-6 rounded shadow">
            <div class="text-gray-600">Total Users</div>
            <div class="text-3xl font-bold">{{ $total_users }}</div>
        </div>
        <div class="bg-white p-6 rounded shadow">
            <div class="text-gray-600">Active Users</div>
            <div class="text-3xl font-bold">{{ $active_users }}</div>
        </div>
        <div class="bg-white p-6 rounded shadow">
            <div class="text-gray-600">Pending Approvals</div>
            <div class="text-3xl font-bold text-yellow-600">{{ $pending_approvals }}</div>
        </div>
        <div class="bg-white p-6 rounded shadow">
            <div class="text-gray-600">Total Jobs</div>
            <div class="text-3xl font-bold">{{ $total_jobs }}</div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <a href="{{ route('admin.users.create') }}" class="bg-blue-600 text-white p-4 rounded text-center hover:bg-blue-700">
            Create User
        </a>
        <a href="{{ route('admin.approvals.jobs') }}" class="bg-green-600 text-white p-4 rounded text-center hover:bg-green-700">
            Approve Jobs
        </a>
        <a href="{{ route('admin.activity-logs') }}" class="bg-purple-600 text-white p-4 rounded text-center hover:bg-purple-700">
            View Activity Logs
        </a>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-xl font-bold mb-4">Recent Activity</h2>
        <table class="w-full">
            <thead>
                <tr class="border-b">
                    <th class="text-left py-2">User</th>
                    <th class="text-left py-2">Action</th>
                    <th class="text-left py-2">Time</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($recent_activity as $log)
                    <tr class="border-b">
                        <td class="py-2">{{ $log->user->full_name ?? 'System' }}</td>
                        <td class="py-2">{{ $log->getActionDisplay() }}</td>
                        <td class="py-2">{{ $log->getCreatedAtDiffForHumans() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
