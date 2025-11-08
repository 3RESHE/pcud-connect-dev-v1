@extends('layouts.app')

@section('title', 'Update Password - PCU-DASMA Connect')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-sm p-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Change Password</h1>
        <p class="text-gray-600 mb-8">Update your account password to keep your account secure</p>

        <form id="updatePasswordForm" class="space-y-6">
            @csrf

            <!-- Current Password -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Current Password <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="password" name="current_password" id="current_password" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Enter your current password">
                    <button type="button" onclick="togglePasswordVisibility('current_password')" class="absolute right-3 top-3">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </button>
                </div>
                <span id="current_passwordError" class="text-red-500 text-xs mt-1 hidden"></span>
            </div>

            <!-- New Password -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    New Password <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="password" name="password" id="password" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Enter your new password"
                        oninput="updateRequirements(this.value)">
                    <button type="button" onclick="togglePasswordVisibility('password')" class="absolute right-3 top-3">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </button>
                </div>
                <span id="passwordError" class="text-red-500 text-xs mt-1 hidden"></span>
            </div>

            <!-- Confirm Password -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Confirm Password <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Confirm your new password">
                    <button type="button" onclick="togglePasswordVisibility('password_confirmation')" class="absolute right-3 top-3">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </button>
                </div>
                <span id="password_confirmationError" class="text-red-500 text-xs mt-1 hidden"></span>
            </div>

            <!-- Requirements -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="font-semibold text-blue-900 mb-2">Password Requirements:</p>
                <ul class="space-y-1 text-sm text-blue-800">
                    <li class="flex items-center">
                        <svg id="req-length" class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        At least 8 characters
                    </li>
                    <li class="flex items-center">
                        <svg id="req-upper" class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Uppercase letter (A-Z)
                    </li>
                    <li class="flex items-center">
                        <svg id="req-lower" class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Lowercase letter (a-z)
                    </li>
                    <li class="flex items-center">
                        <svg id="req-number" class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Number (0-9)
                    </li>
                    <li class="flex items-center">
                        <svg id="req-special" class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Special character (@$!%*?&)
                    </li>
                </ul>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-3">
                <a href="/dashboard" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit" id="submitBtn"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50">
                    Update Password
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function togglePasswordVisibility(fieldId) {
        const field = document.getElementById(fieldId);
        field.type = field.type === 'password' ? 'text' : 'password';
    }

    function updateRequirements(password) {
        updateRequirement('req-length', password.length >= 8);
        updateRequirement('req-upper', /[A-Z]/.test(password));
        updateRequirement('req-lower', /[a-z]/.test(password));
        updateRequirement('req-number', /\d/.test(password));
        updateRequirement('req-special', /[@$!%*?&]/.test(password));
    }

    function updateRequirement(id, isValid) {
        const svg = document.getElementById(id).querySelector('svg');
        svg.classList.toggle('text-green-500', isValid);
        svg.classList.toggle('text-gray-400', !isValid);
    }

    document.getElementById('updatePasswordForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;

        try {
            const response = await fetch('{{ route("account.password.update") }}', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: new FormData(this)
            });

            const data = await response.json();

            if (data.success) {
                alert(data.message);
                window.location.href = '/dashboard';
            } else {
                alert(data.message);
                if (data.errors) {
                    Object.keys(data.errors).forEach(field => {
                        const errorEl = document.getElementById(field + 'Error');
                        if (errorEl) {
                            errorEl.textContent = data.errors[field][0];
                            errorEl.classList.remove('hidden');
                        }
                    });
                }
            }
        } catch (error) {
            console.error('Error:', error);
            alert('An error occurred');
        } finally {
            submitBtn.disabled = false;
        }
    });
</script>
@endsection
