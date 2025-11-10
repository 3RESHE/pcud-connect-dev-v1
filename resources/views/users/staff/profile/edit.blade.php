@extends('layouts.staff')

@section('title', 'Edit Profile')

@section('content')
<div class="py-6">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Edit Profile</h1>
            <p class="text-gray-600 mt-2">Update your profile information</p>
        </div>

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                <p class="text-sm font-medium text-red-800 mb-2">Please fix the following errors:</p>
                <ul class="list-disc list-inside text-sm text-red-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Edit Form -->
        <div class="bg-white rounded-lg shadow-sm p-6 sm:p-8">
            <form action="{{ route('staff.profile.update') }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- First Name -->
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                        First Name *
                    </label>
                    <input
                        type="text"
                        id="first_name"
                        name="first_name"
                        value="{{ old('first_name', $user->first_name) }}"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                        placeholder="Enter first name"
                    >
                </div>

                <!-- Last Name -->
                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Last Name *
                    </label>
                    <input
                        type="text"
                        id="last_name"
                        name="last_name"
                        value="{{ old('last_name', $user->last_name) }}"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                        placeholder="Enter last name"
                    >
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                        Phone Number
                    </label>
                    <input
                        type="tel"
                        id="phone"
                        name="phone"
                        value="{{ old('phone', $user->staffProfile?->phone) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                        placeholder="+63 9XX XXX XXXX"
                    >
                </div>

                <!-- Staff Department -->
                <div>
                    <label for="staff_department" class="block text-sm font-medium text-gray-700 mb-2">
                        Department
                    </label>
                    <select
                        id="staff_department"
                        name="staff_department"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                    >
                        <option value="">-- Select Department --</option>
                        <option value="Events" {{ old('staff_department', $user->staffProfile?->staff_department) === 'Events' ? 'selected' : '' }}>Events</option>
                        <option value="Communications" {{ old('staff_department', $user->staffProfile?->staff_department) === 'Communications' ? 'selected' : '' }}>Communications</option>
                        <option value="Administration" {{ old('staff_department', $user->staffProfile?->staff_department) === 'Administration' ? 'selected' : '' }}>Administration</option>
                        <option value="Finance" {{ old('staff_department', $user->staffProfile?->staff_department) === 'Finance' ? 'selected' : '' }}>Finance</option>
                        <option value="Other" {{ old('staff_department', $user->staffProfile?->staff_department) === 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <!-- Position -->
                <div>
                    <label for="position" class="block text-sm font-medium text-gray-700 mb-2">
                        Position
                    </label>
                    <input
                        type="text"
                        id="position"
                        name="position"
                        value="{{ old('position', $user->staffProfile?->position) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                        placeholder="e.g., Events Coordinator"
                    >
                </div>

                <!-- Employee ID -->
                <div>
                    <label for="employee_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Employee ID
                    </label>
                    <input
                        type="text"
                        id="employee_id"
                        name="employee_id"
                        value="{{ old('employee_id', $user->staffProfile?->employee_id) }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                        placeholder="Employee ID number"
                    >
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 pt-6 border-t">
                    <button
                        type="submit"
                        class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-blue-700 transition-colors font-medium"
                    >
                        Save Changes
                    </button>
                    <a
                        href="{{ route('staff.profile') }}"
                        class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors font-medium"
                    >
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
