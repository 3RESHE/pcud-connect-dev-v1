<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Your Password - PCU-DASMA Connect</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full">
        <!-- Main Card -->
        <div class="bg-white rounded-xl shadow-2xl overflow-hidden">
            <!-- Header Section -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-8 py-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">Set Your Password</h1>
                        <p class="text-blue-100">Secure your account with a strong password</p>
                    </div>
                    <div class="hidden md:flex items-center justify-center w-20 h-20 rounded-full bg-blue-500 bg-opacity-20">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="px-8 py-8">
                <!-- Alert Box -->
                <div class="bg-amber-50 border-l-4 border-amber-400 rounded-lg p-4 mb-8">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-amber-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0-12a9 9 0 110 18 9 9 0 010-18z"></path>
                        </svg>
                        <div>
                            <p class="text-sm font-semibold text-amber-800">Important</p>
                            <p class="text-sm text-amber-700 mt-1">This is your first login. Setting a strong password is required to proceed and secure your account.</p>
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <form id="passwordForm" class="space-y-6">
                    @csrf

                    <!-- Password Field -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            New Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" id="password" name="password" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                placeholder="Enter a strong password">
                            <button type="button" onclick="togglePasswordVisibility('password')" class="absolute right-3 top-3.5 text-gray-400 hover:text-gray-600 transition">
                                <svg id="eye-password" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                        </div>
                        <span id="passwordError" class="text-red-500 text-xs mt-2 hidden flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <span id="passwordErrorText"></span>
                        </span>
                    </div>

                    <!-- Confirm Password Field -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">
                            Confirm Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="password" id="password_confirmation" name="password_confirmation" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                                placeholder="Re-enter your password">
                            <button type="button" onclick="togglePasswordVisibility('password_confirmation')" class="absolute right-3 top-3.5 text-gray-400 hover:text-gray-600 transition">
                                <svg id="eye-password_confirmation" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                            <!-- Match Status Indicator -->
                            <div class="absolute -right-12 top-3.5 hidden" id="matchIndicator">
                                <svg id="matchIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </div>
                        <span id="password_confirmationError" class="text-red-500 text-xs mt-2 hidden flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <span id="password_confirmationErrorText"></span>
                        </span>
                    </div>

                    <!-- Password Match Status -->
                    <div id="matchStatus" class="flex items-center space-x-2 text-sm hidden">
                        <svg id="matchStatusIcon" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span id="matchStatusText" class="text-green-600 font-medium">Passwords match</span>
                    </div>

                    <!-- Password Requirements Section -->
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200 rounded-lg p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-semibold text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m7 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Password Requirements
                            </h3>
                            <span id="requirementScore" class="text-xs font-semibold px-3 py-1 rounded-full bg-gray-200 text-gray-700">0/5</span>
                        </div>

                        <!-- Requirements List -->
                        <ul class="space-y-2">
                            <li class="flex items-center text-sm">
                                <svg id="req-length" class="w-4 h-4 mr-3 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700">At least <strong>8 characters</strong></span>
                            </li>
                            <li class="flex items-center text-sm">
                                <svg id="req-upper" class="w-4 h-4 mr-3 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700">One <strong>uppercase</strong> letter (A-Z)</span>
                            </li>
                            <li class="flex items-center text-sm">
                                <svg id="req-lower" class="w-4 h-4 mr-3 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700">One <strong>lowercase</strong> letter (a-z)</span>
                            </li>
                            <li class="flex items-center text-sm">
                                <svg id="req-number" class="w-4 h-4 mr-3 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700">One <strong>number</strong> (0-9)</span>
                            </li>
                            <li class="flex items-center text-sm">
                                <svg id="req-special" class="w-4 h-4 mr-3 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700">One <strong>special character</strong> (@$!%*?&)</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Progress Bar -->
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-medium text-gray-600">Password Strength</span>
                            <span id="strengthText" class="text-xs font-semibold text-gray-500">Not set</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                            <div id="strengthBar" class="h-full w-0 bg-gray-300 rounded-full transition-all duration-300"></div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" id="submitBtn"
                        class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white py-3 rounded-lg hover:from-blue-700 hover:to-blue-800 transition font-semibold disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center space-x-2 mt-8">
                        <span id="submitText">Set Password & Continue</span>
                        <svg id="submitIcon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>

                    <!-- Loading Spinner -->
                    <div id="loadingSpinner" class="hidden flex items-center justify-center">
                        <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </form>

                <!-- Footer -->
                <p class="text-xs text-gray-500 text-center mt-6">
                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    Your password is encrypted and securely stored
                </p>
            </div>
        </div>
    </div>

    <!-- Toast Notification Container -->
    <div id="toastContainer" class="fixed bottom-4 right-4 z-50 space-y-3"></div>

    <script>
        let passwordRequirements = {
            length: false,
            uppercase: false,
            lowercase: false,
            number: false,
            special: false
        };

        // Real-time password validation
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            validatePassword(password);
            checkPasswordMatch();
        });

        // Real-time confirmation password validation
        document.getElementById('password_confirmation').addEventListener('input', function() {
            checkPasswordMatch();
        });

        function validatePassword(password) {
            // Length check (min 8)
            passwordRequirements.length = password.length >= 8;
            updateRequirement('req-length', passwordRequirements.length);

            // Uppercase check
            passwordRequirements.uppercase = /[A-Z]/.test(password);
            updateRequirement('req-upper', passwordRequirements.uppercase);

            // Lowercase check
            passwordRequirements.lowercase = /[a-z]/.test(password);
            updateRequirement('req-lower', passwordRequirements.lowercase);

            // Number check
            passwordRequirements.number = /\d/.test(password);
            updateRequirement('req-number', passwordRequirements.number);

            // Special character check
            passwordRequirements.special = /[@$!%*?&]/.test(password);
            updateRequirement('req-special', passwordRequirements.special);

            updateStrengthBar();
            updateRequirementScore();
        }

        function updateRequirement(id, isValid) {
            const element = document.getElementById(id);
            const svg = element;

            if (isValid) {
                svg.classList.remove('text-gray-400');
                svg.classList.add('text-green-500');
            } else {
                svg.classList.add('text-gray-400');
                svg.classList.remove('text-green-500');
            }
        }

        function updateStrengthBar() {
            const bar = document.getElementById('strengthBar');
            const text = document.getElementById('strengthText');
            const metRequirements = Object.values(passwordRequirements).filter(Boolean).length;

            const strengthLevels = [
                { width: 0, text: 'Not set', color: 'bg-gray-300' },
                { width: 20, text: 'Very Weak', color: 'bg-red-500' },
                { width: 40, text: 'Weak', color: 'bg-orange-500' },
                { width: 60, text: 'Fair', color: 'bg-yellow-500' },
                { width: 80, text: 'Good', color: 'bg-blue-500' },
                { width: 100, text: 'Strong', color: 'bg-green-500' }
            ];

            const level = strengthLevels[metRequirements];
            bar.style.width = level.width + '%';
            bar.className = `h-full ${level.color} rounded-full transition-all duration-300`;
            text.textContent = level.text;
            text.className = `text-xs font-semibold ${
                metRequirements === 0 ? 'text-gray-500' :
                metRequirements <= 2 ? 'text-red-600' :
                metRequirements === 3 ? 'text-yellow-600' :
                metRequirements === 4 ? 'text-blue-600' :
                'text-green-600'
            }`;
        }

        function updateRequirementScore() {
            const score = Object.values(passwordRequirements).filter(Boolean).length;
            document.getElementById('requirementScore').textContent = `${score}/5`;
            document.getElementById('requirementScore').className = `text-xs font-semibold px-3 py-1 rounded-full ${
                score === 0 ? 'bg-gray-200 text-gray-700' :
                score <= 2 ? 'bg-red-100 text-red-700' :
                score === 3 ? 'bg-yellow-100 text-yellow-700' :
                score === 4 ? 'bg-blue-100 text-blue-700' :
                'bg-green-100 text-green-700'
            }`;
        }

        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmation = document.getElementById('password_confirmation').value;
            const matchStatus = document.getElementById('matchStatus');
            const matchStatusIcon = document.getElementById('matchStatusIcon');
            const matchStatusText = document.getElementById('matchStatusText');
            const confirmInput = document.getElementById('password_confirmation');

            if (confirmation.length === 0) {
                matchStatus.classList.add('hidden');
                confirmInput.classList.remove('border-green-500', 'border-red-500');
                confirmInput.classList.add('border-gray-300');
                return;
            }

            if (password === confirmation && password.length > 0) {
                matchStatus.classList.remove('hidden');
                matchStatusIcon.className = 'w-5 h-5 text-green-600';
                matchStatusText.className = 'text-green-600 font-medium';
                matchStatusText.textContent = '✓ Passwords match';
                confirmInput.classList.remove('border-red-500', 'border-gray-300');
                confirmInput.classList.add('border-green-500');
            } else if (confirmation.length > 0) {
                matchStatus.classList.remove('hidden');
                matchStatusIcon.className = 'w-5 h-5 text-red-600';
                matchStatusText.className = 'text-red-600 font-medium';
                matchStatusText.textContent = '✕ Passwords do not match';
                confirmInput.classList.remove('border-green-500', 'border-gray-300');
                confirmInput.classList.add('border-red-500');
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

            // Validate before submission
            const password = document.getElementById('password').value;
            const confirmation = document.getElementById('password_confirmation').value;

            // Check all requirements
            if (!Object.values(passwordRequirements).every(Boolean)) {
                showToast('Please meet all password requirements', 'error');
                return;
            }

            // Check match
            if (password !== confirmation) {
                showToast('Passwords do not match', 'error');
                return;
            }

            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const submitIcon = document.getElementById('submitIcon');
            const loadingSpinner = document.getElementById('loadingSpinner');

            submitBtn.disabled = true;
            submitText.classList.add('hidden');
            submitIcon.classList.remove('hidden');
            loadingSpinner.classList.remove('hidden');

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
                    showToast('✓ ' + data.message, 'success');
                    setTimeout(() => {
                        window.location.href = data.redirect || '/dashboard';
                    }, 2000);
                } else {
                    showToast(data.message || 'Failed to update password', 'error');
                    if (data.errors) {
                        displayErrors(data.errors);
                    }
                    submitBtn.disabled = false;
                    submitText.classList.remove('hidden');
                    submitIcon.classList.add('hidden');
                    loadingSpinner.classList.add('hidden');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('An error occurred. Please try again.', 'error');
                submitBtn.disabled = false;
                submitText.classList.remove('hidden');
                submitIcon.classList.add('hidden');
                loadingSpinner.classList.add('hidden');
            }
        });

        function displayErrors(errors) {
            Object.keys(errors).forEach(field => {
                const errorEl = document.getElementById(field + 'Error');
                const errorText = document.getElementById(field + 'ErrorText');
                if (errorEl && errorText) {
                    errorText.textContent = errors[field][0];
                    errorEl.classList.remove('hidden');
                }
            });
        }

        function showToast(message, type = 'info') {
            const container = document.getElementById('toastContainer');

            let bgColor, borderColor, textColor, icon;
            if (type === 'success') {
                bgColor = 'bg-green-50';
                borderColor = 'border-l-4 border-green-500';
                textColor = 'text-green-800';
                icon = '✓';
            } else if (type === 'error') {
                bgColor = 'bg-red-50';
                borderColor = 'border-l-4 border-red-500';
                textColor = 'text-red-800';
                icon = '✕';
            } else {
                bgColor = 'bg-blue-50';
                borderColor = 'border-l-4 border-blue-500';
                textColor = 'text-blue-800';
                icon = 'ℹ';
            }

            const toast = document.createElement('div');
            toast.className = `${bgColor} ${borderColor} ${textColor} px-6 py-4 rounded-lg shadow-lg flex items-center justify-between max-w-md animate-slideIn`;
            toast.innerHTML = `
                <div class="flex items-center">
                    <span class="font-bold mr-3">${icon}</span>
                    <span>${message}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="ml-4 font-bold hover:opacity-70">✕</button>
            `;

            container.appendChild(toast);
            setTimeout(() => {
                toast.classList.add('animate-slideOut');
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        }

        // Initialize strength bar
        updateStrengthBar();
    </script>

    <style>
        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }

        .animate-slideIn {
            animation: slideIn 0.3s ease-in-out;
        }

        .animate-slideOut {
            animation: slideOut 0.3s ease-in-out;
        }
    </style>
</body>
</html>
