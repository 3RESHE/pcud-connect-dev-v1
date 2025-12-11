@extends('layouts.partner')

@section('title', 'Edit Job - ' . $jobPosting->title . ' - PCU-DASMA Connect')

@section('content')
<div class="max-w-6xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center mb-4">
            <a href="{{ route('partner.job-postings.show', $jobPosting->id) }}" class="text-gray-400 hover:text-gray-600 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Edit Job Posting</h1>
        </div>
        <p class="text-gray-600">Update your job posting details</p>
    </div>

    <!-- Status Alert -->
    @if ($jobPosting->status === 'rejected')
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-yellow-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <h3 class="font-semibold text-yellow-800 mb-1">Job Posting was Rejected</h3>
                    <p class="text-sm text-yellow-700 mb-2">{{ $jobPosting->rejection_reason }}</p>
                    <p class="text-xs text-yellow-600">Please review the rejection reason and make necessary changes before resubmitting.</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Toast Container -->
    <div id="toastContainer" class="fixed bottom-4 right-4 z-50 max-w-xs"></div>

    <!-- Form Container -->
    <div class="bg-white shadow rounded-lg">
        <form id="jobPostingForm" method="POST" action="{{ route('partner.job-postings.update', $jobPosting->id) }}" class="divide-y divide-gray-200">
            @csrf
            @method('PUT')

            <!-- Job Type Selection -->
            <div class="px-6 py-5">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Job Type</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Full-time Position -->
                    <div>
                        <label class="relative flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="job_type" value="fulltime" class="sr-only" onchange="toggleJobTypeFields()" {{ old('job_type', $jobPosting->job_type) === 'fulltime' ? 'checked' : '' }}>
                            <div class="flex-1">
                                <div class="flex items-center">
                                    <div class="job-type-icon w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m8 0a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-900">Full-time Position</h3>
                                        <p class="text-sm text-gray-500">Permanent employment</p>
                                    </div>
                                </div>
                            </div>
                            <div class="job-type-radio hidden">
                                <div class="w-4 h-4 bg-blue-100 text-blue-800 rounded-full flex items-center justify-center">
                                    <div class="w-2 h-2 bg-blue-600 rounded-full"></div>
                                </div>
                            </div>
                        </label>
                    </div>

                    <!-- Part-time Position -->
                    <div>
                        <label class="relative flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="job_type" value="parttime" class="sr-only" onchange="toggleJobTypeFields()" {{ old('job_type', $jobPosting->job_type) === 'parttime' ? 'checked' : '' }}>
                            <div class="flex-1">
                                <div class="flex items-center">
                                    <div class="job-type-icon w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-900">Part-time Position</h3>
                                        <p class="text-sm text-gray-500">Flexible schedule</p>
                                    </div>
                                </div>
                            </div>
                            <div class="job-type-radio hidden">
                                <div class="w-4 h-4 bg-green-100 text-green-800 rounded-full flex items-center justify-center">
                                    <div class="w-2 h-2 bg-green-600 rounded-full"></div>
                                </div>
                            </div>
                        </label>
                    </div>

                    <!-- Internship Program -->
                    <div>
                        <label class="relative flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="job_type" value="internship" class="sr-only" onchange="toggleJobTypeFields()" {{ old('job_type', $jobPosting->job_type) === 'internship' ? 'checked' : '' }}>
                            <div class="flex-1">
                                <div class="flex items-center">
                                    <div class="job-type-icon w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-900">Internship Program</h3>
                                        <p class="text-sm text-gray-500">Learning opportunity</p>
                                    </div>
                                </div>
                            </div>
                            <div class="job-type-radio hidden">
                                <div class="w-4 h-4 bg-purple-100 text-purple-800 rounded-full flex items-center justify-center">
                                    <div class="w-2 h-2 bg-purple-600 rounded-full"></div>
                                </div>
                            </div>
                        </label>
                    </div>

                    <!-- Other/Contract Position -->
                    <div>
                        <label class="relative flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="job_type" value="other" class="sr-only" onchange="toggleJobTypeFields()" {{ old('job_type', $jobPosting->job_type) === 'other' ? 'checked' : '' }}>
                            <div class="flex-1">
                                <div class="flex items-center">
                                    <div class="job-type-icon w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-900">Other/Contract</h3>
                                        <p class="text-sm text-gray-500">Freelance/Project</p>
                                    </div>
                                </div>
                            </div>
                            <div class="job-type-radio hidden">
                                <div class="w-4 h-4 bg-orange-100 text-orange-800 rounded-full flex items-center justify-center">
                                    <div class="w-2 h-2 bg-orange-600 rounded-full"></div>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Basic Information -->
            <div class="px-6 py-5 border-t border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Basic Information</h2>
                <div class="space-y-6">
                    <!-- Job Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">
                            <span id="title_label">Job Title</span> <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="title" name="title" required value="{{ old('title', $jobPosting->title) }}" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="e.g., Senior Software Developer">
                        <p class="mt-1 text-sm text-gray-500">Include relevant keywords to help job seekers find your posting.</p>
                    </div>

                    <!-- Department & Experience Level -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="department_id" class="block text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">
                                Department <span class="text-red-500">*</span>
                            </label>
                            <select id="department_id" name="department_id" required class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">Select Department</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}" {{ old('department_id', $jobPosting->department_id) == $department->id ? 'selected' : '' }}>
                                        {{ $department->title }} ({{ $department->formatted_code }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-sm text-gray-500">Choose the department this job posting belongs to.</p>
                        </div>
                        <div>
                            <label for="experience_level" class="block text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">
                                Experience Level <span class="text-red-500">*</span>
                            </label>
                            <select id="experience_level" name="experience_level" required class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">Select Level</option>
                                <option value="entry" {{ old('experience_level', $jobPosting->experience_level) === 'entry' ? 'selected' : '' }}>Entry Level (0-2 years)</option>
                                <option value="mid" {{ old('experience_level', $jobPosting->experience_level) === 'mid' ? 'selected' : '' }}>Mid Level (3-5 years)</option>
                                <option value="senior" {{ old('experience_level', $jobPosting->experience_level) === 'senior' ? 'selected' : '' }}>Senior Level (6+ years)</option>
                                <option value="lead" {{ old('experience_level', $jobPosting->experience_level) === 'lead' ? 'selected' : '' }}>Lead/Manager Level</option>
                                <option value="student" {{ old('experience_level', $jobPosting->experience_level) === 'student' ? 'selected' : '' }}>Student/Fresh Graduate</option>
                            </select>
                        </div>
                    </div>

                    <!-- Job Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">
                            <span id="description_label">Job Description</span> <span class="text-red-500">*</span>
                        </label>
                        <textarea id="description" name="description" rows="5" required class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Describe the role and responsibilities">{{ old('description', $jobPosting->description) }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Use one responsibility per line for clear formatting.</p>
                    </div>
                </div>
            </div>

            <!-- Location and Work Setup -->
            <div class="px-6 py-5 border-t border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Location & Work Setup</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="work_setup" class="block text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">
                            Work Setup <span class="text-red-500">*</span>
                        </label>
                        <select id="work_setup" name="work_setup" required onchange="toggleLocationField()" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">Select Work Setup</option>
                            <option value="onsite" {{ old('work_setup', $jobPosting->work_setup) === 'onsite' ? 'selected' : '' }}>On-site</option>
                            <option value="remote" {{ old('work_setup', $jobPosting->work_setup) === 'remote' ? 'selected' : '' }}>Remote</option>
                            <option value="hybrid" {{ old('work_setup', $jobPosting->work_setup) === 'hybrid' ? 'selected' : '' }}>Hybrid</option>
                        </select>
                    </div>
                    <div id="location_field">
                        <label for="location" class="block text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">
                            Location <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="location" name="location" value="{{ old('location', $jobPosting->location) }}" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="e.g., Makati City, Metro Manila">
                    </div>
                </div>
            </div>

            <!-- Compensation -->
            <div class="px-6 py-5 border-t border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 mb-4"><span id="compensation_title">Compensation</span></h2>

                <!-- Unpaid Internship Checkbox -->
                <div id="unpaid_internship_field" class="hidden mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" id="is_unpaid" name="is_unpaid" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" onchange="toggleAllowanceFields()" {{ old('is_unpaid', $jobPosting->is_unpaid) ? 'checked' : '' }}>
                        <span class="ml-2 text-sm text-gray-700">No Allowance (Unpaid Internship)</span>
                    </label>
                </div>

                <!-- Compensation Fields -->
                <div id="compensation_fields" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="salary_min" class="block text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">
                            <span id="salary_min_label">Minimum Salary</span> <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">₱</span>
                            <input type="number" id="salary_min" name="salary_min" min="0" value="{{ old('salary_min', $jobPosting->salary_min) }}" required class="block w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="30000">
                        </div>
                    </div>
                    <div>
                        <label for="salary_max" class="block text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">
                            <span id="salary_max_label">Maximum Salary</span> <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">₱</span>
                            <input type="number" id="salary_max" name="salary_max" min="0" value="{{ old('salary_max', $jobPosting->salary_max) }}" required class="block w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="50000">
                        </div>
                    </div>
                    <div id="salary_period_field">
                        <label for="salary_period" class="block text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">
                            Pay Period <span class="text-red-500">*</span>
                        </label>
                        <select id="salary_period" name="salary_period" required class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="monthly" {{ old('salary_period', $jobPosting->salary_period) === 'monthly' ? 'selected' : '' }}>Monthly</option>
                            <option value="hourly" {{ old('salary_period', $jobPosting->salary_period) === 'hourly' ? 'selected' : '' }}>Hourly</option>
                            <option value="daily" {{ old('salary_period', $jobPosting->salary_period) === 'daily' ? 'selected' : '' }}>Daily</option>
                            <option value="project" {{ old('salary_period', $jobPosting->salary_period) === 'project' ? 'selected' : '' }}>Per Project</option>
                        </select>
                    </div>
                </div>

                <!-- Internship Duration Field -->
                <div id="internship_duration" class="mt-6 hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="duration_months" class="block text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">
                                Internship Duration <span class="text-red-500">*</span>
                            </label>
                            <select id="duration_months" name="duration_months" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <option value="">Select Duration</option>
                                <option value="1" {{ old('duration_months', $jobPosting->duration_months) == 1 ? 'selected' : '' }}>1 month</option>
                                <option value="2" {{ old('duration_months', $jobPosting->duration_months) == 2 ? 'selected' : '' }}>2 months</option>
                                <option value="3" {{ old('duration_months', $jobPosting->duration_months) == 3 ? 'selected' : '' }}>3 months</option>
                                <option value="4" {{ old('duration_months', $jobPosting->duration_months) == 4 ? 'selected' : '' }}>4 months</option>
                                <option value="5" {{ old('duration_months', $jobPosting->duration_months) == 5 ? 'selected' : '' }}>5 months</option>
                                <option value="6" {{ old('duration_months', $jobPosting->duration_months) == 6 ? 'selected' : '' }}>6 months</option>
                                <option value="12" {{ old('duration_months', $jobPosting->duration_months) == 12 ? 'selected' : '' }}>12 months</option>
                            </select>
                        </div>
                        <div>
                            <label for="preferred_start_date" class="block text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">
                                Preferred Start Date
                            </label>
                            <input type="date" id="preferred_start_date" name="preferred_start_date" value="{{ old('preferred_start_date', $jobPosting->preferred_start_date?->format('Y-m-d')) }}" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Requirements -->
            <div class="px-6 py-5 border-t border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Requirements</h2>
                <div class="space-y-6">
                    <!-- Education -->
                    <div>
                        <label for="education_requirements" class="block text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">
                            Education & Experience Requirements <span class="text-red-500">*</span>
                        </label>
                        <textarea id="education_requirements" name="education_requirements" rows="3" required class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="One requirement per line...">{{ old('education_requirements', $jobPosting->education_requirements) }}</textarea>
                    </div>

                    <!-- Technical Skills -->
                    <div>
                        <label class="block text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">
                            Technical Skills <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" id="skills_input" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="e.g., JavaScript, React, Node.js (press Enter)" onkeydown="handleSkillsInput(event)">
                            <input type="hidden" id="technical_skills" name="technical_skills" value="{{ old('technical_skills', $technicalSkills ?? '') }}">
                        </div>
                        <div id="skills_tags" class="flex flex-wrap gap-2 mt-2"></div>
                        <p class="mt-1 text-sm text-gray-500">Type a skill and press Enter to add.</p>
                    </div>

                    <!-- Experience Requirements -->
                    <div>
                        <label for="experience_requirements" class="block text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">
                            Additional Experience Requirements
                        </label>
                        <textarea id="experience_requirements" name="experience_requirements" rows="3" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="One requirement per line...">{{ old('experience_requirements', $jobPosting->experience_requirements) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Application Process -->
            <div class="px-6 py-5 border-t border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Application Process</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="application_deadline" class="block text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">
                            Application Deadline <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="application_deadline" name="application_deadline" required value="{{ old('application_deadline', $jobPosting->application_deadline->format('Y-m-d')) }}" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="positions_available" class="block text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">
                            Number of Positions <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="positions_available" name="positions_available" min="1" required value="{{ old('positions_available', $jobPosting->positions_available) }}" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="1">
                    </div>
                </div>
                <div>
                    <label for="application_process" class="block text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">
                        Application Instructions
                    </label>
                    <textarea id="application_process" name="application_process" rows="3" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="One step per line...">{{ old('application_process', $jobPosting->application_process) }}</textarea>
                </div>
            </div>

            <!-- Benefits -->
            <div class="px-6 py-5 border-t border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Benefits & Perks</h2>
                <textarea id="benefits" name="benefits" rows="3" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="One benefit per line...">{{ old('benefits', $jobPosting->benefits) }}</textarea>
            </div>

            <!-- Action Buttons -->
            <div class="px-6 py-5 bg-gray-50 border-t border-gray-200">
                <div class="flex justify-between">
                    <a href="{{ route('partner.job-postings.show', $jobPosting->id) }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 font-medium">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-medium">
                        {{ $jobPosting->status === 'rejected' ? 'Resubmit for Approval' : 'Update Job Posting' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
let skillsList = [];

// ✅ Initialize skills from existing data
function initializeSkills() {
    const skillsInput = document.getElementById('technical_skills');
    if (skillsInput && skillsInput.value) {
        try {
            const parsed = JSON.parse(skillsInput.value);
            skillsList = Array.isArray(parsed) ? parsed : [];
        } catch (e) {
            skillsList = skillsInput.value.split(',').map(s => s.trim()).filter(s => s);
        }
        updateSkillsTags();
    }
}

function handleSkillsInput(event) {
    if (event.key === 'Enter') {
        event.preventDefault();
        const input = document.getElementById('skills_input');
        const skill = input.value.trim();

        if (skill && !skillsList.includes(skill)) {
            addSkill(skill);
            input.value = '';
        }
    }
}

function addSkill(skill) {
    skillsList.push(skill);
    updateSkillsTags();
    updateHiddenSkillsInput();
}

function removeSkill(skill) {
    skillsList = skillsList.filter(s => s !== skill);
    updateSkillsTags();
    updateHiddenSkillsInput();
}

function updateSkillsTags() {
    const container = document.getElementById('skills_tags');
    container.innerHTML = '';

    skillsList.forEach(skill => {
        const tag = document.createElement('span');
        tag.className = 'inline-flex items-center gap-2 px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm';

        const skillText = document.createElement('span');
        skillText.textContent = skill;

        const button = document.createElement('button');
        button.type = 'button';
        button.className = 'text-blue-600 hover:text-blue-800 focus:outline-none';
        button.innerHTML = `
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
        `;
        button.onclick = () => removeSkill(skill);

        tag.appendChild(skillText);
        tag.appendChild(button);
        container.appendChild(tag);
    });
}

function updateHiddenSkillsInput() {
    document.getElementById('technical_skills').value = skillsList.join(',');
}

// ===== JOB TYPE SELECTION =====
document.querySelectorAll('input[name="job_type"]').forEach(radio => {
    radio.addEventListener('change', function() {
        document.querySelectorAll('input[name="job_type"]').forEach(r => {
            const label = r.closest('label');
            if (r.checked) {
                label.classList.add('border-blue-500', 'bg-blue-50');
                label.classList.remove('border-gray-200');
                label.querySelector('.job-type-radio').classList.remove('hidden');
            } else {
                label.classList.remove('border-blue-500', 'bg-blue-50');
                label.classList.add('border-gray-200');
                label.querySelector('.job-type-radio').classList.add('hidden');
            }
        });

        toggleJobTypeFields();
    });
});

// ===== TOGGLE FIELDS BASED ON JOB TYPE =====
function toggleJobTypeFields() {
    const jobType = document.querySelector('input[name="job_type"]:checked')?.value;
    const unpaidField = document.getElementById('unpaid_internship_field');
    const internshipDuration = document.getElementById('internship_duration');
    const compensationTitle = document.getElementById('compensation_title');
    const salaryMinLabel = document.getElementById('salary_min_label');
    const salaryMaxLabel = document.getElementById('salary_max_label');

    if (jobType === 'internship') {
        unpaidField.classList.remove('hidden');
        internshipDuration.classList.remove('hidden');
        compensationTitle.textContent = 'Allowance/Compensation';
        salaryMinLabel.textContent = 'Minimum Allowance';
        salaryMaxLabel.textContent = 'Maximum Allowance';
        document.getElementById('salary_min').required = false;
        document.getElementById('salary_max').required = false;
    } else {
        unpaidField.classList.add('hidden');
        internshipDuration.classList.add('hidden');
        compensationTitle.textContent = 'Compensation';
        salaryMinLabel.textContent = 'Minimum Salary';
        salaryMaxLabel.textContent = 'Maximum Salary';
        document.getElementById('salary_min').required = true;
        document.getElementById('salary_max').required = true;
        document.getElementById('is_unpaid').checked = false;
        toggleAllowanceFields();
    }
}

// ===== TOGGLE ALLOWANCE FIELDS FOR UNPAID INTERNSHIPS =====
function toggleAllowanceFields() {
    const isUnpaid = document.getElementById('is_unpaid').checked;
    const compensationFields = document.getElementById('compensation_fields');

    if (isUnpaid) {
        compensationFields.classList.add('opacity-50', 'pointer-events-none');
        compensationFields.querySelectorAll('input, select').forEach(el => {
            el.disabled = true;
            el.required = false;
        });
    } else {
        compensationFields.classList.remove('opacity-50', 'pointer-events-none');
        compensationFields.querySelectorAll('input, select').forEach(el => {
            el.disabled = false;
            el.required = true;
        });
    }
}

// ===== TOGGLE LOCATION FIELD =====
function toggleLocationField() {
    const workSetup = document.getElementById('work_setup').value;
    const locationField = document.getElementById('location_field');
    const locationInput = document.getElementById('location');

    if (workSetup === 'remote') {
        locationField.classList.add('opacity-50', 'pointer-events-none');
        locationInput.disabled = true;
        locationInput.required = false;
        locationInput.value = 'Remote';
    } else {
        locationField.classList.remove('opacity-50', 'pointer-events-none');
        locationInput.disabled = false;
        locationInput.required = true;
        if (locationInput.value === 'Remote') {
            locationInput.value = '';
        }
    }
}

// ===== FORM SUBMISSION =====
document.getElementById('jobPostingForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const submitBtn = document.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.disabled = true;
    submitBtn.textContent = 'Updating...';

    clearAllErrors();

    const formData = new FormData(this);

    try {
        const response = await fetch(this.action, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            },
            body: formData
        });

        const data = await response.json();

        if (response.ok && data.success) {
            showToast('✅ ' + data.message, 'success');
            setTimeout(() => {
                window.location.href = data.redirect;
            }, 1500);
        } else {
            if (data.errors) {
                showValidationErrors(data.errors);
                showToast('❌ Please fix the validation errors', 'error');
            } else {
                showToast('❌ ' + (data.message || 'Failed to update job posting'), 'error');
            }
        }
    } catch (error) {
        console.error('Fetch Error:', error);
        showToast('❌ Network error: ' + error.message, 'error');
    } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
    }
});

// ===== ERROR HANDLING & DISPLAY =====
function showValidationErrors(errors) {
    const errorContainer = document.createElement('div');
    errorContainer.className = 'bg-red-50 border border-red-200 rounded-lg p-4 mb-6';
    errorContainer.id = 'validation-errors';

    let errorHTML = '<div class="flex gap-3"><div class="flex-shrink-0"><svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div><div class="flex-1"><h3 class="text-sm font-semibold text-red-800 mb-2">Validation Errors:</h3><ul class="text-sm text-red-700 space-y-1">';

    const errorFields = [];

    for (const [field, messages] of Object.entries(errors)) {
        errorFields.push(field);
        const fieldName = formatFieldName(field);
        errorHTML += `<li class="flex items-start"><span class="mr-2">•</span><span><strong>${fieldName}:</strong> ${messages[0]}</span></li>`;

        const input = document.querySelector(`[name="${field}"]`);
        if (input) {
            input.classList.add('border-red-500', 'bg-red-50');
            input.addEventListener('focus', function() {
                this.classList.remove('border-red-500', 'bg-red-50');
            }, { once: true });
        }
    }

    errorHTML += '</ul></div></div>';
    errorContainer.innerHTML = errorHTML;

    const form = document.getElementById('jobPostingForm');
    form.parentElement.insertBefore(errorContainer, form);

    if (errorFields.length > 0) {
        const firstErrorField = document.querySelector(`[name="${errorFields[0]}"]`);
        if (firstErrorField) {
            firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }
}

function formatFieldName(fieldName) {
    return fieldName
        .replace(/_/g, ' ')
        .replace(/([A-Z])/g, ' $1')
        .trim()
        .split(' ')
        .map(word => word.charAt(0).toUpperCase() + word.slice(1))
        .join(' ');
}

function clearAllErrors() {
    const errorContainer = document.getElementById('validation-errors');
    if (errorContainer) {
        errorContainer.remove();
    }

    document.querySelectorAll('.border-red-500').forEach(el => {
        el.classList.remove('border-red-500', 'bg-red-50');
    });
}

// ===== TOAST NOTIFICATIONS =====
function showToast(message, type = 'info') {
    const container = document.getElementById('toastContainer');
    const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';

    const toast = document.createElement('div');
    toast.className = `${bgColor} text-white px-6 py-4 rounded-lg shadow-xl flex items-center justify-between gap-4 mb-2`;
    toast.innerHTML = `
        <span class="flex-1">${message}</span>
        <button onclick="this.parentElement.remove()" class="text-white hover:text-gray-200 flex-shrink-0 focus:outline-none">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    `;

    container.appendChild(toast);
    setTimeout(() => toast.remove(), 5000);
}

// ===== PAGE INITIALIZATION =====
document.addEventListener('DOMContentLoaded', function() {
    initializeSkills();
    toggleJobTypeFields();
    toggleLocationField();
    toggleAllowanceFields();

    document.querySelectorAll('input[name="job_type"]').forEach(radio => {
        if (radio.checked) {
            const label = radio.closest('label');
            label.classList.add('border-blue-500', 'bg-blue-50');
            label.classList.remove('border-gray-200');
            label.querySelector('.job-type-radio').classList.remove('hidden');
        }
    });
});
</script>
@endsection