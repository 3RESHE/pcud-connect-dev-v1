@extends('layouts.partner')

<meta name="csrf-token" content="{{ csrf_token() }}">

@section('title', 'Company Profile - PCU-DASMA Connect')

@section('content')
    <div class="w-full bg-gray-50 min-h-screen py-4 sm:py-6 lg:py-8">
        <div class="w-full max-w-6xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 sm:mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="min-w-0">
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 break-words">Company Profile</h1>
                        <p class="text-sm sm:text-base text-gray-600 mt-1 break-words">Manage your organization's profile and
                            visibility</p>
                    </div>
                    <a href="{{ route('partner.profile.show') }}"
                        class="px-3 sm:px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm sm:text-base whitespace-nowrap">
                        View Profile
                    </a>
                </div>
            </div>

            <!-- Alerts -->
            @if (!$profile || is_null($profile->company_name))
                <div class="mb-4 sm:mb-6 bg-amber-50 border-l-4 border-amber-400 p-3 sm:p-4 rounded-r">
                    <div class="flex gap-3">
                        <svg class="w-5 h-5 text-amber-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-xs sm:text-sm text-amber-800 break-words"><strong>Important:</strong> Complete your
                            company profile to unlock all partner features.</p>
                    </div>
                </div>
            @endif

            <!-- Error Alert Container -->
            <div id="errorAlertContainer"></div>

            <!-- Profile Form -->
            <form id="profileForm" class="space-y-4 sm:space-y-6 md:space-y-8" method="POST"
                action="{{ route('partner.profile.update') }}" enctype="multipart/form-data">
                @csrf

                <!-- Company Information Section -->
                <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                    <div class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 break-words">Company Information</h3>
                        <p class="text-xs sm:text-sm text-gray-600 mt-1 break-words">Basic information about your
                            organization</p>
                    </div>
                    <div class="px-3 sm:px-4 md:px-6 py-4 sm:py-6 space-y-4 sm:space-y-6">

                        <!-- Company Logo -->
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2 sm:mb-3">Company
                                Logo</label>
                            <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-6">
                                <div class="flex-shrink-0">
                                    @if ($profile && $profile->company_logo)
                                        <img id="logoPreview" src="{{ Storage::url($profile->company_logo) }}"
                                            alt="Company Logo" class="w-20 h-20 sm:w-24 sm:h-24 rounded-lg object-cover">
                                    @else
                                        <div id="logoPlaceholder"
                                            class="w-20 h-20 sm:w-24 sm:h-24 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="w-10 h-10 sm:w-12 sm:h-12 text-gray-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                                </path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <label class="inline-block">
                                        <input type="file" name="company_logo" id="company_logo" class="hidden"
                                            accept="image/*" onchange="previewLogo(event)">
                                        <span
                                            class="inline-block bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-xs sm:text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 cursor-pointer transition whitespace-nowrap">
                                            Change Logo
                                        </span>
                                    </label>
                                    <p class="text-xs text-gray-500 mt-1 sm:mt-2 break-words">PNG, JPG, GIF up to 2MB</p>
                                    <span id="logoError" class="text-red-500 text-xs mt-1 block break-words hidden"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Company Name & Industry -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                            <div class="min-w-0">
                                <label for="company_name"
                                    class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2 break-words">
                                    Company Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="company_name" name="company_name"
                                    value="{{ $profile?->company_name ?? '' }}" required
                                    class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                    placeholder="e.g., TechSolutions Inc.">
                                <span id="company_nameError"
                                    class="text-red-500 text-xs mt-1 block break-words hidden"></span>
                            </div>
                            <div class="min-w-0">
                                <label for="industry"
                                    class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2 break-words">
                                    Industry <span class="text-red-500">*</span>
                                </label>
                                <select id="industry" name="industry" required
                                    class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                    <option value="">Select Industry</option>
                                    <option value="technology" @selected($profile?->industry === 'technology')>Technology</option>
                                    <option value="finance" @selected($profile?->industry === 'finance')>Finance & Banking</option>
                                    <option value="healthcare" @selected($profile?->industry === 'healthcare')>Healthcare</option>
                                    <option value="education" @selected($profile?->industry === 'education')>Education</option>
                                    <option value="manufacturing" @selected($profile?->industry === 'manufacturing')>Manufacturing</option>
                                    <option value="retail" @selected($profile?->industry === 'retail')>Retail</option>
                                    <option value="consulting" @selected($profile?->industry === 'consulting')>Consulting</option>
                                    <option value="other" @selected($profile?->industry === 'other')>Other</option>
                                </select>
                                <span id="industryError" class="text-red-500 text-xs mt-1 block break-words hidden"></span>
                            </div>
                        </div>

                        <!-- Company Size & Founded Year -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                            <div class="min-w-0">
                                <label for="company_size"
                                    class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2 break-words">
                                    Company Size
                                </label>
                                <select id="company_size" name="company_size"
                                    class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                    <option value="">Select Company Size</option>
                                    <option value="1-10" @selected($profile?->company_size === '1-10')>1-10 employees</option>
                                    <option value="11-50" @selected($profile?->company_size === '11-50')>11-50 employees</option>
                                    <option value="51-200" @selected($profile?->company_size === '51-200')>51-200 employees</option>
                                    <option value="201-500" @selected($profile?->company_size === '201-500')>201-500 employees</option>
                                    <option value="501-1000" @selected($profile?->company_size === '501-1000')>501-1000 employees</option>
                                    <option value="1000+" @selected($profile?->company_size === '1000+')>1000+ employees</option>
                                </select>
                            </div>
                            <div class="min-w-0">
                                <label for="founded_year"
                                    class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2 break-words">
                                    Founded Year
                                </label>
                                <input type="number" id="founded_year" name="founded_year"
                                    value="{{ $profile?->founded_year ?? '' }}" min="1900" max="{{ date('Y') }}"
                                    class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                    placeholder="e.g., 2018">
                            </div>
                        </div>

                        <!-- Company Description -->
                        <div class="min-w-0">
                            <label for="description"
                                class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2 break-words">
                                Company Description <span class="text-red-500">*</span>
                            </label>
                            <textarea id="description" name="description" rows="4" required
                                class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm resize-none"
                                placeholder="Tell students about your company, mission, values, and what makes you a great place to work...">{{ $profile?->description ?? '' }}</textarea>
                            <div class="mt-1 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 sm:gap-0">
                                <p class="text-xs text-gray-500">Minimum 50 characters</p>
                                <span id="descriptionCount" class="text-xs text-gray-500">0/2000</span>
                            </div>
                            <span id="descriptionError" class="text-red-500 text-xs mt-1 block break-words hidden"></span>
                        </div>
                    </div>
                </div>

                <!-- Contact Information Section -->
                <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                    <div class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 break-words">Contact Information</h3>
                        <p class="text-xs sm:text-sm text-gray-600 mt-1 break-words">How students can reach your
                            organization</p>
                    </div>
                    <div class="px-3 sm:px-4 md:px-6 py-4 sm:py-6 space-y-4 sm:space-y-6">

                        <!-- Primary Contact -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                            <div class="min-w-0">
                                <label for="contact_person"
                                    class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2 break-words">
                                    Primary Contact Person <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="contact_person" name="contact_person"
                                    value="{{ $profile?->contact_person ?? '' }}" required
                                    class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                    placeholder="Full name">
                                <span id="contact_personError"
                                    class="text-red-500 text-xs mt-1 block break-words hidden"></span>
                            </div>
                            <div class="min-w-0">
                                <label for="contact_title"
                                    class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2 break-words">
                                    Job Title
                                </label>
                                <input type="text" id="contact_title" name="contact_title"
                                    value="{{ $profile?->contact_title ?? '' }}"
                                    class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                    placeholder="e.g., HR Manager">
                            </div>
                        </div>

                        <!-- Contact Details -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                            <div class="min-w-0">
                                <label for="phone"
                                    class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2 break-words">
                                    Phone Number
                                </label>
                                <input type="tel" id="phone" name="phone"
                                    value="{{ $profile?->phone ?? '' }}"
                                    class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                    placeholder="+63 9XX-XXXX-XXXX">
                            </div>
                            <div class="min-w-0">
                                <label for="website"
                                    class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2 break-words">
                                    Company Website
                                </label>
                                <input type="url" id="website" name="website"
                                    value="{{ $profile?->website ?? '' }}"
                                    class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                    placeholder="https://example.com">
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="min-w-0">
                            <label for="address"
                                class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2 break-words">
                                Business Address
                            </label>
                            <textarea id="address" name="address" rows="3"
                                class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm resize-none"
                                placeholder="Complete business address...">{{ $profile?->address ?? '' }}</textarea>
                        </div>

                        <!-- LinkedIn -->
                        <div class="min-w-0">
                            <label for="linkedin_url"
                                class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2 break-words">
                                LinkedIn Profile
                            </label>
                            <input type="url" id="linkedin_url" name="linkedin_url"
                                value="{{ $profile?->linkedin_url ?? '' }}"
                                class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                placeholder="https://linkedin.com/company/yourcompany">
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div
                    class="flex flex-col sm:flex-row gap-3 sm:justify-between sm:items-center bg-white shadow-sm rounded-lg p-3 sm:p-4 md:p-6">
                    <a href="{{ route('partner.profile.show') }}"
                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium text-sm text-center order-3 sm:order-1">
                        Cancel
                    </a>
                    <div class="flex flex-col sm:flex-row gap-3 order-2 sm:order-2">
                        <button type="reset"
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium text-sm whitespace-nowrap">
                            Reset
                        </button>
                        <button type="submit" id="submitBtn"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium text-sm disabled:opacity-50 disabled:cursor-not-allowed whitespace-nowrap">
                            Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Toast Container -->
    <div id="toastContainer" class="fixed bottom-4 right-4 z-50 max-w-xs mx-2 sm:max-w-sm"></div>

    <script>
        // Character counter for description
        document.getElementById('description').addEventListener('input', function() {
            const count = this.value.length;
            document.getElementById('descriptionCount').textContent = count + '/2000';
        });

        // Logo preview
        function previewLogo(event) {
            const file = event.target.files[0];
            const maxSize = 2 * 1024 * 1024;

            if (!file) return;

            if (file.size > maxSize) {
                showError('logoError', 'File size must not exceed 2MB');
                event.target.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const placeholder = document.getElementById('logoPlaceholder');
                const preview = document.getElementById('logoPreview');

                if (placeholder) {
                    placeholder.innerHTML =
                        `<img src="${e.target.result}" alt="Preview" class="w-full h-full rounded-lg object-cover">`;
                } else if (preview) {
                    preview.src = e.target.result;
                }
            };
            reader.readAsDataURL(file);
        }

        // Form submission
        document.getElementById('profileForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;

            clearAllErrors();

            const formData = new FormData(this);

            try {
                const response = await fetch('{{ route('partner.profile.update') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    showToast('✅ ' + data.message, 'success');
                    setTimeout(() => {
                        window.location.href = data.redirect || '{{ route('partner.profile.show') }}';
                    }, 1500);
                } else {
                    showErrorAlert(data.message || 'Failed to update profile');
                    if (data.errors) {
                        displayErrors(data.errors);
                    }
                    console.error('Response:', data); // Debug
                }
            } catch (error) {
                console.error('Error:', error);
                showErrorAlert('❌ Network error: ' + error.message);
            } finally {
                submitBtn.disabled = false;
            }
        });

        function showErrorAlert(message) {
            const container = document.getElementById('errorAlertContainer');
            const alertDiv = document.createElement('div');
            alertDiv.className = 'mb-4 sm:mb-6 bg-red-50 border-l-4 border-red-400 p-3 sm:p-4 rounded-r';
            alertDiv.id = 'errorAlert';
            alertDiv.innerHTML = `
            <div class="flex gap-3">
                <svg class="w-5 h-5 text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div class="flex-1 min-w-0">
                    <p class="text-xs sm:text-sm text-red-800 break-words"><strong>Error:</strong> ${message}</p>
                    <button onclick="document.getElementById('errorAlert').remove()" class="text-red-600 hover:text-red-800 text-xs mt-2 underline">Dismiss</button>
                </div>
            </div>
        `;
            container.innerHTML = '';
            container.appendChild(alertDiv);
            alertDiv.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }

        function clearAllErrors() {
            const errorAlert = document.getElementById('errorAlert');
            if (errorAlert) errorAlert.remove();

            document.querySelectorAll('[id$="Error"]').forEach(el => {
                el.classList.add('hidden');
                el.textContent = '';
            });
        }

        function displayErrors(errors) {
            Object.keys(errors).forEach(field => {
                const errorEl = document.getElementById(field + 'Error');
                if (errorEl) {
                    errorEl.textContent = errors[field][0];
                    errorEl.classList.remove('hidden');
                }
            });
        }

        function showError(elementId, message) {
            const element = document.getElementById(elementId);
            if (element) {
                element.textContent = message;
                element.classList.remove('hidden');
            }
        }

        function showToast(message, type = 'info') {
            const container = document.getElementById('toastContainer');
            const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';

            const toast = document.createElement('div');
            toast.className =
                `${bgColor} text-white px-4 py-3 rounded-lg shadow-lg mb-2 flex items-center justify-between gap-2 text-sm`;
            toast.innerHTML = `
            <span class="break-words">${message}</span>
            <button onclick="this.parentElement.remove()" class="text-white hover:text-gray-200 flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        `;

            container.appendChild(toast);
            setTimeout(() => toast.remove(), 5000);
        }

        // Initialize character count
        document.getElementById('descriptionCount').textContent = document.getElementById('description').value.length +
            '/2000';
    </script>
@endsection
