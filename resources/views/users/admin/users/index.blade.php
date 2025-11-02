@extends('layouts.admin')

@section('title', 'User Management - PCU-DASMA Connect')
@section('page-title', 'User Management')

@section('content')
<!-- Header -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">User Management</h1>
        <p class="text-gray-600">Manage all platform users and their roles</p>
    </div>
    <div class="flex space-x-3">
        <button
            onclick="openBulkUploadModal()"
            class="px-4 py-2 bg-green-600 text-white text-sm rounded-md hover:bg-green-700 flex items-center"
        >
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
            </svg>
            Bulk Upload
        </button>
        <button
            onclick="openCreateUserModal()"
            class="px-4 py-2 bg-primary text-white text-sm rounded-md hover:bg-blue-700 flex items-center"
        >
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Create Account
        </button>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-6 mb-8">
    @include('users.admin.users._stats-card', ['title' => 'Total Users', 'value' => 1234, 'color' => 'blue', 'icon' => 'users'])
    @include('users.admin.users._stats-card', ['title' => 'Alumni', 'value' => 789, 'color' => 'blue', 'icon' => 'users'])
    @include('users.admin.users._stats-card', ['title' => 'Students', 'value' => 400, 'color' => 'indigo', 'icon' => 'document'])
    @include('users.admin.users._stats-card', ['title' => 'Partners', 'value' => 32, 'color' => 'purple', 'icon' => 'building'])
    @include('users.admin.users._stats-card', ['title' => 'Staff', 'value' => 10, 'color' => 'yellow', 'icon' => 'shield'])
    @include('users.admin.users._stats-card', ['title' => 'Admins', 'value' => 3, 'color' => 'orange', 'icon' => 'cog'])
</div>

<!-- Filter Tabs -->
<div class="mb-8">
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8 overflow-x-auto">
            <button onclick="filterUsers('all')" class="user-filter active border-b-2 border-primary text-primary py-2 px-1 text-sm font-medium whitespace-nowrap">
                All (1,234)
            </button>
            <button onclick="filterUsers('alumni')" class="user-filter border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-2 px-1 text-sm font-medium whitespace-nowrap">
                Alumni (789)
            </button>
            <button onclick="filterUsers('student')" class="user-filter border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-2 px-1 text-sm font-medium whitespace-nowrap">
                Student (400)
            </button>
            <button onclick="filterUsers('partner')" class="user-filter border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-2 px-1 text-sm font-medium whitespace-nowrap">
                Partners (32)
            </button>
            <button onclick="filterUsers('staff')" class="user-filter border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-2 px-1 text-sm font-medium whitespace-nowrap">
                Staff (10)
            </button>
            <button onclick="filterUsers('admin')" class="user-filter border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 py-2 px-1 text-sm font-medium whitespace-nowrap">
                Admin (3)
            </button>
        </nav>
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
                placeholder="Search users by name, email, or ID..."
                oninput="searchUsers(this.value)"
                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-primary focus:border-primary"
            />
        </div>
    </div>
    <div class="flex space-x-2">
        <select class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary">
            <option>All Roles</option>
            <option>Alumni</option>
            <option>Student</option>
            <option>Partner</option>
            <option>Staff</option>
            <option>Admin</option>
        </select>
        <select class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary">
            <option>All Status</option>
            <option>Active</option>
            <option>Inactive</option>
        </select>
    </div>
</div>

<!-- Users Table -->
<div class="bg-white shadow-sm rounded-lg overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">User List</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Active</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody id="userTableBody" class="bg-white divide-y divide-gray-200">
                <!-- Users will be populated by JavaScript -->
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-gray-200">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Showing <span class="font-medium">1</span> to <span class="font-medium">10</span> of <span class="font-medium">1,234</span> results
            </div>
            <nav class="flex items-center space-x-2">
                <button class="px-3 py-1 text-sm bg-white border border-gray-300 text-gray-500 rounded-md">Previous</button>
                <button class="px-3 py-1 text-sm bg-primary text-white rounded-md">1</button>
                <button class="px-3 py-1 text-sm bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">2</button>
                <button class="px-3 py-1 text-sm bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">3</button>
                <button class="px-3 py-1 text-sm bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">Next</button>
            </nav>
        </div>
    </div>
</div>

<!-- Modals -->
@include('users.admin.users.modals.create-user-modal')
@include('users.admin.users.modals.edit-user-modal')
@include('users.admin.users.modals.bulk-upload-modal')

<script src="{{ asset('js/admin/users.js') }}"></script>
@endsection
