@extends('layouts.admin')

@section('title', 'User Management - PCU-DASMA Connect')
@section('page-title', 'User Management')

@section('content')
<!-- Header -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">User Management</h1>
        <p class="text-gray-600">Create, manage, and monitor system users</p>
    </div>
    <div class="flex space-x-3">
        <a href="{{ route('admin.users.bulk-import-form') }}" class="px-4 py-2 bg-green-600 text-white text-sm rounded-md hover:bg-green-700 flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Bulk Import
        </a>
        <button onclick="openAddUserModal()" class="px-4 py-2 bg-primary text-white text-sm rounded-md hover:bg-blue-700 flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add User
        </button>
    </div>
</div>

<!-- Statistics -->
<div class="grid grid-cols-1 md:grid-cols-6 gap-4 mb-8">
    <div class="bg-white rounded-lg shadow p-6">
        <div class="text-2xl font-bold text-primary" id="statTotal">0</div>
        <div class="text-sm text-gray-600">Total Users</div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="text-2xl font-bold text-blue-600" id="statAdmin">0</div>
        <div class="text-sm text-gray-600">Admin</div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="text-2xl font-bold text-green-600" id="statStaff">0</div>
        <div class="text-sm text-gray-600">Staff</div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="text-2xl font-bold text-orange-600" id="statPartner">0</div>
        <div class="text-sm text-gray-600">Partner</div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="text-2xl font-bold text-purple-600" id="statStudent">0</div>
        <div class="text-sm text-gray-600">Student</div>
    </div>
    <div class="bg-white rounded-lg shadow p-6">
        <div class="text-2xl font-bold text-pink-600" id="statAlumni">0</div>
        <div class="text-sm text-gray-600">Alumni</div>
    </div>
</div>

<!-- Filters -->
<div class="bg-white shadow-sm rounded-lg p-6 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
            <input type="text" id="searchInput" placeholder="Name or email..." class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
            <select id="roleFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary">
                <option value="all">All Roles</option>
                <option value="admin">Admin</option>
                <option value="staff">Staff</option>
                <option value="partner">Partner</option>
                <option value="student">Student</option>
                <option value="alumni">Alumni</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
            <select id="statusFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary focus:border-primary">
                <option value="">All Status</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
        </div>
        <div class="flex items-end">
            <button onclick="filterUsers()" class="w-full px-4 py-2 bg-primary text-white rounded-md hover:bg-blue-700">
                Filter
            </button>
        </div>
    </div>
</div>

<!-- Users Table -->
<div class="bg-white shadow-sm rounded-lg overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Users List</h3>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody id="userTableBody" class="bg-white divide-y divide-gray-200">
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                        <div class="flex items-center justify-center">
                            <svg class="animate-spin h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-gray-200">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Showing <span id="showingStart" class="font-medium">1</span> to
                <span id="showingEnd" class="font-medium">10</span> of
                <span id="showingTotal" class="font-medium">0</span>
            </div>
            <nav class="flex items-center space-x-2" id="paginationContainer">
                <!-- Pagination buttons -->
            </nav>
        </div>
    </div>
</div>

<!-- Add User Modal -->
@include('users.admin.users._modals.add-user-modal')

<!-- Edit User Modal -->
@include('users.admin.users._modals.edit-user-modal')

<!-- Delete Confirmation Modal -->
<div id="deleteConfirmModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <div class="relative bg-white rounded-lg max-w-md w-full shadow-xl">
            <div class="p-6">
                <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full mb-4">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2 text-center">Delete User?</h3>
                <p id="deleteMessage" class="text-gray-600 text-center mb-6">This action cannot be undone.</p>
                <div class="flex justify-center space-x-3">
                    <button type="button" onclick="closeDeleteConfirmModal()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="button" onclick="confirmDelete()" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div id="toastContainer" class="fixed bottom-4 right-4 z-50"></div>

<script src="{{ asset('js/admin/users.js') }}"></script>
@endsection
