@extends('layouts.partner')

@section('title', 'Edit Job - ' . $jobPosting->title . ' - PCU-DASMA Connect')

@section('content')
    <div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-4 mb-4">
                <a href="{{ route('partner.job-postings.show', $jobPosting->id) }}" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div class="flex-1 min-w-0">
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 break-words">Edit Job Posting</h1>
                    <p class="text-gray-600 text-sm sm:text-base mt-1">{{ $jobPosting->title }}</p>
                </div>
            </div>
        </div>

        <!-- Status Alert -->
        @if ($jobPosting->status === 'rejected')
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <p class="text-sm text-yellow-800">
                    <strong>Note:</strong> This job posting was previously rejected. Please review the rejection reason and
                    make necessary changes before resubmitting.
                </p>
            </div>
        @endif

        <!-- Form -->
        <form id="editJobForm" action="{{ route('partner.job-postings.update', $jobPosting->id) }}" method="POST"
            class="space-y-6">
            @csrf
            @method('PUT')

            <div class="bg-white shadow rounded-lg p-4 sm:p-6">
                <!-- Title -->
                <div class="mb-6">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Job Title *</label>
                    <input type="text" id="title" name="title" value="{{ old('title', $jobPosting->title) }}"
                        class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-sm"
                        placeholder="e.g., Senior Software Engineer" required>
                    @error('title')
                        <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Job Type & Experience Level - Row 1 -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-6">
                    <div>
                        <label for="job_type" class="block text-sm font-medium text-gray-700 mb-2">Job Type *</label>
                        <select id="job_type" name="job_type"
                            class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-sm"
                            required>
                            <option value="">Select Job Type</option>
                            <option value="fulltime" @if (old('job_type', $jobPosting->job_type) === 'fulltime') selected @endif>Full-time</option>
                            <option value="parttime" @if (old('job_type', $jobPosting->job_type) === 'parttime') selected @endif>Part-time</option>
                            <option value="internship" @if (old('job_type', $jobPosting->job_type) === 'internship') selected @endif>Internship</option>
                            <option value="other" @if (old('job_type', $jobPosting->job_type) === 'other') selected @endif>Other</option>
                        </select>
                        @error('job_type')
                            <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="experience_level" class="block text-sm font-medium text-gray-700 mb-2">Experience Level
                            *</label>
                        <select id="experience_level" name="experience_level"
                            class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-sm"
                            required>
                            <option value="">Select Experience Level</option>
                            <option value="entry" @if (old('experience_level', $jobPosting->experience_level) === 'entry') selected @endif>Entry Level</option>
                            <option value="mid" @if (old('experience_level', $jobPosting->experience_level) === 'mid') selected @endif>Mid Level</option>
                            <option value="senior" @if (old('experience_level', $jobPosting->experience_level) === 'senior') selected @endif>Senior Level (6+ years)
                            </option>
                            <option value="lead" @if (old('experience_level', $jobPosting->experience_level) === 'lead') selected @endif>Lead/Manager</option>
                            <option value="student" @if (old('experience_level', $jobPosting->experience_level) === 'student') selected @endif>Student/Fresh Graduate
                            </option>
                        </select>
                        @error('experience_level')
                            <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Department & Location - Row 2 -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-6">
                    <div>
                        <label for="department" class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                        <input type="text" id="department" name="department"
                            value="{{ old('department', $jobPosting->department) }}"
                            class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-sm"
                            placeholder="e.g., Engineering, Sales">
                        @error('department')
                            <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-2">Location (for on-site)
                            *</label>
                        <input type="text" id="location" name="location"
                            value="{{ old('location', $jobPosting->location) }}"
                            class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-sm"
                            placeholder="e.g., Manila, Quezon City">
                        @error('location')
                            <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Work Setup & Positions - Row 3 -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-6">
                    <div>
                        <label for="work_setup" class="block text-sm font-medium text-gray-700 mb-2">Work Setup *</label>
                        <select id="work_setup" name="work_setup"
                            class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-sm"
                            required onchange="toggleLocationRequirement()">
                            <option value="">Select Work Setup</option>
                            <option value="onsite" @if (old('work_setup', $jobPosting->work_setup) === 'onsite') selected @endif>On-site</option>
                            <option value="remote" @if (old('work_setup', $jobPosting->work_setup) === 'remote') selected @endif>Remote</option>
                            <option value="hybrid" @if (old('work_setup', $jobPosting->work_setup) === 'hybrid') selected @endif>Hybrid</option>
                        </select>
                        @error('work_setup')
                            <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="positions_available" class="block text-sm font-medium text-gray-700 mb-2">Positions
                            Available *</label>
                        <input type="number" id="positions_available" name="positions_available"
                            value="{{ old('positions_available', $jobPosting->positions_available) }}"
                            class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-sm"
                            min="1" max="100" required>
                        @error('positions_available')
                            <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Compensation Section -->
                <div class="border-t border-gray-200 pt-6 mb-6">
                    <h3 class="text-sm font-semibold text-gray-900 mb-4">Compensation</h3>

                    <div class="mb-4">
                        <label class="flex items-center">
                            <input type="hidden" name="is_unpaid" value="0">
                            <input type="checkbox" id="is_unpaid" name="is_unpaid" value="1"
                                @if (old('is_unpaid', $jobPosting->is_unpaid)) checked @endif
                                class="rounded border-gray-300 text-primary focus:ring-primary"
                                onchange="toggleSalaryFields()">
                            <span class="ml-2 text-sm text-gray-700">This is an unpaid position</span>
                        </label>
                    </div>

                    <div id="salarySection"
                        class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 @if (old('is_unpaid', $jobPosting->is_unpaid)) hidden @endif">
                        <div>
                            <label for="salary_min" class="block text-sm font-medium text-gray-700 mb-2">Minimum Salary
                                (₱)</label>
                            <input type="number" id="salary_min" name="salary_min" step="0.01"
                                value="{{ old('salary_min', $jobPosting->salary_min) }}"
                                class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-sm"
                                placeholder="e.g., 20000">
                            @error('salary_min')
                                <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="salary_max" class="block text-sm font-medium text-gray-700 mb-2">Maximum Salary
                                (₱)</label>
                            <input type="number" id="salary_max" name="salary_max" step="0.01"
                                value="{{ old('salary_max', $jobPosting->salary_max) }}"
                                class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-sm"
                                placeholder="e.g., 50000">
                            @error('salary_max')
                                <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="salary_period" class="block text-sm font-medium text-gray-700 mb-2">Salary Period
                                *</label>
                            <select id="salary_period" name="salary_period"
                                class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-sm">
                                <option value="">Select Period</option>
                                <option value="monthly" @if (old('salary_period', $jobPosting->salary_period) === 'monthly') selected @endif>Monthly
                                </option>
                                <option value="hourly" @if (old('salary_period', $jobPosting->salary_period) === 'hourly') selected @endif>Hourly</option>
                                <option value="daily" @if (old('salary_period', $jobPosting->salary_period) === 'daily') selected @endif>Daily</option>
                                <option value="project" @if (old('salary_period', $jobPosting->salary_period) === 'project') selected @endif>Per Project
                                </option>
                            </select>
                            @error('salary_period')
                                <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Application Deadline & Duration -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-6">
                    <div>
                        <label for="application_deadline" class="block text-sm font-medium text-gray-700 mb-2">Application
                            Deadline *</label>
                        <input type="date" id="application_deadline" name="application_deadline"
                            value="{{ old('application_deadline', $jobPosting->application_deadline->format('Y-m-d')) }}"
                            class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-sm"
                            required>
                        @error('application_deadline')
                            <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="duration_months" class="block text-sm font-medium text-gray-700 mb-2">Duration
                            (Months)</label>
                        <input type="number" id="duration_months" name="duration_months"
                            value="{{ old('duration_months', $jobPosting->duration_months) }}"
                            class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-sm"
                            min="1" max="60" placeholder="e.g., 6">
                        @error('duration_months')
                            <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Preferred Start Date -->
                <div class="mb-6">
                    <label for="preferred_start_date" class="block text-sm font-medium text-gray-700 mb-2">Preferred Start
                        Date</label>
                    <input type="date" id="preferred_start_date" name="preferred_start_date"
                        value="{{ old('preferred_start_date', $jobPosting->preferred_start_date?->format('Y-m-d')) }}"
                        class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-sm">
                    @error('preferred_start_date')
                        <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Description Section -->
            <div class="bg-white shadow rounded-lg p-4 sm:p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-6">Job Details</h2>

                <!-- Description -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Job Description
                        *</label>
                    <textarea id="description" name="description" rows="6"
                        class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-sm"
                        placeholder="Describe the job role, responsibilities, and requirements..." required>{{ old('description', $jobPosting->description) }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Minimum 50 characters</p>
                    @error('description')
                        <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Education Requirements -->
                <div class="mb-6">
                    <label for="education_requirements" class="block text-sm font-medium text-gray-700 mb-2">Education &
                        Requirements</label>
                    <textarea id="education_requirements" name="education_requirements" rows="4"
                        class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-sm"
                        placeholder="e.g., Bachelor's degree in Computer Science, 3+ years of experience...">{{ old('education_requirements', $jobPosting->education_requirements) }}</textarea>
                    @error('education_requirements')
                        <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Experience Requirements -->
                <div class="mb-6">
                    <label for="experience_requirements" class="block text-sm font-medium text-gray-700 mb-2">Additional
                        Experience</label>
                    <textarea id="experience_requirements" name="experience_requirements" rows="4"
                        class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-sm"
                        placeholder="Any additional experience or certifications...">{{ old('experience_requirements', $jobPosting->experience_requirements) }}</textarea>
                    @error('experience_requirements')
                        <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Technical Skills -->
                <div class="mb-6">
                    <label for="technical_skills" class="block text-sm font-medium text-gray-700 mb-2">Technical
                        Skills</label>
                    <input type="text" id="technical_skills" name="technical_skills"
                        value="{{ old('technical_skills', $technicalSkills) }}"
                        class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-sm"
                        placeholder="e.g., JavaScript, Python, React (comma-separated)">
                    <p class="text-xs text-gray-500 mt-1">Enter skills separated by commas</p>
                    @error('technical_skills')
                        <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Benefits -->
                <div class="mb-6">
                    <label for="benefits" class="block text-sm font-medium text-gray-700 mb-2">Benefits & Perks</label>
                    <textarea id="benefits" name="benefits" rows="4"
                        class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-sm"
                        placeholder="e.g., Health insurance, Work from home, Training opportunities...">{{ old('benefits', $jobPosting->benefits) }}</textarea>
                    @error('benefits')
                        <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Application Process -->
                <div class="mb-6">
                    <label for="application_process" class="block text-sm font-medium text-gray-700 mb-2">Application
                        Process</label>
                    <textarea id="application_process" name="application_process" rows="4"
                        class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-sm"
                        placeholder="Describe the steps applicants need to follow...">{{ old('application_process', $jobPosting->application_process) }}</textarea>
                    @error('application_process')
                        <p class="text-red-500 text-xs sm:text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex flex-col sm:flex-row gap-3 justify-between">
                <a href="{{ route('partner.job-postings.show', $jobPosting->id) }}"
                    class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors duration-200 text-center text-sm sm:text-base">
                    Cancel
                </a>
                <button type="submit"
                    class="px-6 py-3 bg-primary text-white rounded-lg font-medium hover:bg-blue-700 transition-colors duration-200 text-sm sm:text-base">
                    {{ $jobPosting->status === 'rejected' ? 'Resubmit for Approval' : 'Update Job Posting' }}
                </button>
            </div>
        </form>
    </div>

    <script>
        function toggleSalaryFields() {
            const isUnpaid = document.getElementById('is_unpaid').checked;
            const salarySection = document.getElementById('salarySection');

            if (isUnpaid) {
                salarySection.classList.add('hidden');
            } else {
                salarySection.classList.remove('hidden');
            }
        }

        function toggleLocationRequirement() {
            const workSetup = document.getElementById('work_setup').value;
            const locationField = document.getElementById('location');

            if (workSetup === 'onsite') {
                locationField.parentElement.querySelector('label').innerHTML += ' <span class="text-red-500">*</span>';
                locationField.required = true;
            } else {
                locationField.parentElement.querySelector('label').innerHTML = 'Location (for on-site) *';
                locationField.required = false;
            }
        }

        // ✅ NEW: Handle form submission
        document.getElementById('editJobForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const form = this;
            const formData = new FormData(form);

            fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(data => {
                            throw data;
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Show success message
                        showSuccessMessage(data.message);

                        // Redirect after 2 seconds
                        setTimeout(() => {
                            window.location.href = data.redirect;
                        }, 2000);
                    }
                })
                .catch(error => {
                    if (error.errors) {
                        // Display validation errors
                        displayErrors(error.errors);
                        showErrorMessage(error.message || 'Validation failed');
                    } else {
                        showErrorMessage(error.message || 'An error occurred');
                    }
                });
        });

        // Helper: Show success message
        function showSuccessMessage(message) {
            const alertDiv = document.createElement('div');
            alertDiv.className =
                'fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-lg z-50 max-w-sm';
            alertDiv.innerHTML = `
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span>${message}</span>
        </div>
    `;
            document.body.appendChild(alertDiv);

            setTimeout(() => alertDiv.remove(), 3000);
        }

        // Helper: Show error message
        function showErrorMessage(message) {
            const alertDiv = document.createElement('div');
            alertDiv.className =
                'fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow-lg z-50 max-w-sm';
            alertDiv.innerHTML = `
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
            <span>${message}</span>
        </div>
    `;
            document.body.appendChild(alertDiv);

            setTimeout(() => alertDiv.remove(), 5000);
        }

        // Helper: Display validation errors
        function displayErrors(errors) {
            // Clear previous errors
            document.querySelectorAll('.error-message').forEach(el => el.remove());

            // Display new errors
            Object.keys(errors).forEach(field => {
                const fieldElement = document.getElementById(field);
                if (fieldElement) {
                    const errorDiv = document.createElement('p');
                    errorDiv.className = 'error-message text-red-500 text-xs sm:text-sm mt-1';
                    errorDiv.textContent = errors[field][0];
                    fieldElement.parentElement.appendChild(errorDiv);
                }
            });
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleSalaryFields();
            toggleLocationRequirement();
        });
    </script>

@endsection
