@php
    $userRole = auth()->user()->role;
    $layout = match($userRole) {
        'admin' => 'layouts.admin',
        'staff' => 'layouts.staff',
        'student' => 'layouts.student',
        'alumni' => 'layouts.alumni',
        'partner' => 'layouts.partner',
        default => 'layouts.app',
    };
@endphp

@extends($layout)

@section('title', 'Manage Password')

@section('content')
<div class="py-6">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Manage Password</h1>
            <p class="text-gray-600 mt-2">Update your password to keep your account secure</p>
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

        <!-- Password Management Form -->
        <div class="bg-white rounded-lg shadow-sm p-6 sm:p-8">
            <div class="mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-2">Change Your Password</h2>
                <p class="text-sm text-gray-600">Ensure your account is using a long, random password to stay secure.</p>
            </div>

            <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                @csrf
                @method('put')

                <!-- Current Password -->
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">
                        Current Password *
                    </label>
                    <input
                        id="current_password"
                        name="current_password"
                        type="password"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                        autocomplete="current-password"
                        placeholder="Enter your current password"
                    />
                    @error('current_password', 'updatePassword')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- New Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        New Password *
                    </label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                        autocomplete="new-password"
                        placeholder="Enter your new password"
                    />
                    @error('password', 'updatePassword')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Password must be at least 8 characters long</p>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                        Confirm New Password *
                    </label>
                    <input
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                        autocomplete="new-password"
                        placeholder="Confirm your new password"
                    />
                    @error('password_confirmation', 'updatePassword')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Success Message -->
                @if (session('status') === 'password-updated')
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <p class="text-sm text-green-800 font-medium">✓ Password updated successfully!</p>
                    </div>
                @endif

                <!-- Buttons -->
                <div class="flex gap-3 pt-6 border-t">
                    <button
                        type="submit"
                        class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-blue-700 transition-colors font-medium"
                    >
                        Update Password
                    </button>
                    <a
                        href="{{ route($userRole . '.dashboard') }}"
                        class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors font-medium"
                    >
                        Back to Dashboard
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
