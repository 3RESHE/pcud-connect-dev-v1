@extends('layouts.admin')

@section('title', 'Department Management - PCU-DASMA Connect')
@section('page-title', 'Department Management')

@section('content')
<!-- Header -->
<div class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Department Management</h1>
        <p class="text-gray-600">Manage all departments in the system</p>
    </div>
    <div>
        <button
            onclick="openAddDepartmentModal()"
            class="px-4 py-2 bg-primary text-white text-sm rounded-md hover:bg-blue-700 flex items-center"
        >
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Add Department
        </button>
    </div>
</div>

<!-- Departments Table -->
<div class="bg-white shadow-sm rounded-lg overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Department List</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Department Name
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Department Code
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Created
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody id="departmentTableBody" class="bg-white divide-y divide-gray-200">
                <!-- Departments will be populated by JavaScript -->
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="px-6 py-4 border-t border-gray-200">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Showing <span class="font-medium">1</span> to
                <span class="font-medium">9</span> of
                <span class="font-medium">9</span> departments
            </div>
            <nav class="flex items-center space-x-2">
                <button class="px-3 py-1 text-sm bg-white border border-gray-300 text-gray-500 rounded-md">
                    Previous
                </button>
                <button class="px-3 py-1 text-sm bg-primary text-white rounded-md">
                    1
                </button>
                <button class="px-3 py-1 text-sm bg-white border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
                    Next
                </button>
            </nav>
        </div>
    </div>
</div>

<!-- Modals -->
@include('users.admin.departments._modals.add-department-modal')
@include('users.admin.departments._modals.edit-department-modal')

<script src="{{ asset('js/admin/departments.js') }}"></script>
@endsection
