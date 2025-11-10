@extends('layouts.staff')

@section('title', 'My Profile')

@section('content')
<div class="py-6">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Staff Profile</h1>
            <p class="text-gray-600 mt-2">View and manage your profile information</p>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-3 text-sm font-medium text-green-800">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <!-- Profile Card -->
        <div class="bg-white rounded-lg shadow-sm p-6 sm:p-8 mb-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Personal Information</h2>
                <a href="{{ route('staff.profile.edit') }}"
                   class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Edit Profile
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- First Name -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm font-medium text-gray-600 mb-1">First Name</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $user->first_name }}</p>
                </div>

                <!-- Last Name -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm font-medium text-gray-600 mb-1">Last Name</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $user->last_name }}</p>
                </div>

                <!-- Email -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm font-medium text-gray-600 mb-1">Email</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $user->email }}</p>
                </div>

                <!-- Phone -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm font-medium text-gray-600 mb-1">Phone</p>
                    <p class="text-lg font-semibold text-gray-900">
                        {{ $user->staffProfile?->phone ?? 'Not provided' }}
                    </p>
                </div>

                <!-- Department -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm font-medium text-gray-600 mb-1">Department</p>
                    <p class="text-lg font-semibold text-gray-900">
                        {{ $user->staffProfile?->staff_department ?? 'Not specified' }}
                    </p>
                </div>

                <!-- Position -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm font-medium text-gray-600 mb-1">Position</p>
                    <p class="text-lg font-semibold text-gray-900">
                        {{ $user->staffProfile?->position ?? 'Not specified' }}
                    </p>
                </div>

                <!-- Employee ID -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm font-medium text-gray-600 mb-1">Employee ID</p>
                    <p class="text-lg font-semibold text-gray-900">
                        {{ $user->staffProfile?->employee_id ?? 'Not assigned' }}
                    </p>
                </div>

                <!-- Member Since -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm font-medium text-gray-600 mb-1">Member Since</p>
                    <p class="text-lg font-semibold text-gray-900">
                        {{ $user->created_at->format('M d, Y') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
