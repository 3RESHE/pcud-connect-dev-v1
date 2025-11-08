<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Your Password - PCU-DASMA Connect</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        <!-- Card -->
        <div class="bg-white rounded-lg shadow-xl p-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900">Set Your Password</h1>
                <p class="text-gray-600 text-sm mt-2">This is your first login. Please set a strong password.</p>
            </div>

            <!-- Warning Alert -->
            <div class="bg-amber-50 border-l-4 border-amber-400 p-4 mb-6">
                <div class="flex">
                    <svg class="w-5 h-5 text-amber-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="text-sm text-amber-800"><strong>Important:</strong> You cannot proceed without setting a password.</p>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <form id="passwordForm" class="space-y-4">
                @csrf

                <!-- Password Field -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        New Password <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" id="password" name="password" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Enter your password">
                        <button type="button" onclick="togglePasswordVisibility('password')" class="absolute right-3 top-3 text-gray-500">
                            <svg id="eye-password" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                    <span id="passwordError" class="text-red-500 text-xs mt-1 hidden"></span>
                </div>

                <!-- Password Confirmation Field -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Confirm Password <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Confirm your password">
                        <button type="button" onclick="togglePasswordVisibility('password_confirmation')" class="absolute right-3 top-3 text-gray-500">
                            <svg id="eye-password_confirmation" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </button>
                    </div>
                    <span id="password_confirmationError" class="text-red-500 text-xs mt-1 hidden"></span>
                </div>

                <!-- Password Requirements -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-sm">
                    <p class="font-semibold text-blue-900 mb-2">Password Requirements:</p>
                    <ul class="space-y-1 text-blue-800">
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

                <!-- Submit Button -->
                <button type="submit" id="submitBtn"
                    class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition disabled:opacity-50 disabled:cursor-not-allowed font-medium mt-6">
                    Set Password & Continue
                </button>
            </form>

            <!-- Info Message -->
            <p class="text-xs text-gray-500 text-center mt-4">
                Your password is encrypted and secure
            </p>
        </div>
    </div>

    <!-- Toast Notification -->
    <div id="toastContainer" class="fixed bottom-4 right-4 z-50"></div>

    <script>
        // Validate password requirements in real-time
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            updateRequirements(password);
        });

        function updateRequirements(password) {
            // Length check (min 8)
            const lengthValid = password.length >= 8;
            updateRequirement('req-length', lengthValid);

            // Uppercase check
            const upperValid = /[A-Z]/.test(password);
            updateRequirement('req-upper', upperValid);

            // Lowercase check
            const lowerValid = /[a-z]/.test(password);
            updateRequirement('req-lower', lowerValid);

            // Number check
            const numberValid = /\d/.test(password);
            updateRequirement('req-number', numberValid);

            // Special character check
            const specialValid = /[@$!%*?&]/.test(password);
            updateRequirement('req-special', specialValid);
        }

        function updateRequirement(id, isValid) {
            const element = document.getElementById(id);
            const svg = element.querySelector('svg');

            if (isValid) {
                svg.classList.add('text-green-500');
                svg.classList.remove('text-gray-400');
            } else {
                svg.classList.remove('text-green-500');
                svg.classList.add('text-gray-400');
            }
        }

        // Toggle password visibility
        function togglePasswordVisibility(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById('eye-' + fieldId);

            if (field.type === 'password') {
                field.type = 'text';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-4.803m5.596-3.856a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>';
            } else {
                field.type = 'password';
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
            }
        }

        // Handle form submission
        document.getElementById('passwordForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;

            try {
                const response = await fetch('{{ route("password.update-first") }}', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: new FormData(this)
                });

                const data = await response.json();

                if (data.success) {
                    showToast('✅ ' + data.message, 'success');
                    setTimeout(() => {
                        window.location.href = data.redirect || '/dashboard';
                    }, 1500);
                } else {
                    showToast(data.message || 'Failed to update password', 'error');
                    if (data.errors) {
                        displayErrors(data.errors);
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('An error occurred', 'error');
            } finally {
                submitBtn.disabled = false;
            }
        });

        function displayErrors(errors) {
            Object.keys(errors).forEach(field => {
                const errorEl = document.getElementById(field + 'Error');
                if (errorEl) {
                    errorEl.textContent = errors[field][0];
                    errorEl.classList.remove('hidden');
                }
            });
        }

        function showToast(message, type = 'info') {
            const container = document.getElementById('toastContainer');
            const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';

            const toast = document.createElement('div');
            toast.className = `${bgColor} text-white px-6 py-3 rounded-lg shadow-lg mb-2 flex items-center justify-between`;
            toast.innerHTML = `
                <span>${message}</span>
                <button onclick="this.parentElement.remove()" class="ml-4 text-white">✕</button>
            `;

            container.appendChild(toast);
            setTimeout(() => toast.remove(), 5000);
        }
    </script>
</body>
</html>
