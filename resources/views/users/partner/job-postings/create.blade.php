@extends('layouts.partner')

@section('title', 'Create Job Posting - PCU-DASMA Connect')

@section('content')
<div class="max-w-6xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center mb-4">
            <a href="{{ route('partner.job-postings.index') }}" class="text-gray-400 hover:text-gray-600 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Create New Job Posting</h1>
        </div>
        <p class="text-gray-600">Post job opportunities and internship programs for PCU-DASMA students and alumni</p>
    </div>

    <!-- Toast Container -->
    <div id="toastContainer" class="fixed bottom-4 right-4 z-50 max-w-xs"></div>

    <!-- Form Container -->
    <div class="bg-white shadow rounded-lg">
        <form id="jobPostingForm" method="POST" action="{{ route('partner.job-postings.store') }}" class="divide-y divide-gray-200">
            @csrf

            <!-- Job Type Selection -->
            <div class="px-6 py-5">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Job Type</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Full-time Position -->
                    <div>
                        <label class="relative flex items-center p-4 border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50">
                            <input type="radio" name="job_type" value="fulltime" class="sr-only" onchange="toggleJobTypeFields()">
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
                            <input type="radio" name="job_type" value="parttime" class="sr-only" onchange="toggleJobTypeFields()">
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
                            <input type="radio" name="job_type" value="internship" class="sr-only" onchange="toggleJobTypeFields()">
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
                            <input type="radio" name="job_type" value="other" class="sr-only" onchange="toggleJobTypeFields()">
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
                        <input type="text" id="title" name="title" required class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="e.g., Senior Software Developer - Use specific keywords like 'PHP, Laravel'">
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
                                    <option value="{{ $department->id }}">{{ $department->title }} ({{ $department->formatted_code }})</option>
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
                                <option value="entry">Entry Level (0-2 years)</option>
                                <option value="mid">Mid Level (3-5 years)</option>
                                <option value="senior">Senior Level (6+ years)</option>
                                <option value="lead">Lead/Manager Level</option>
                                <option value="student">Student/Fresh Graduate</option>
                            </select>
                        </div>
                    </div>

                    <!-- Job Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">
                            <span id="description_label">Job Description</span> <span class="text-red-500">*</span>
                        </label>
                        <textarea id="description" name="description" rows="5" required class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="Describe the role and responsibilities"></textarea>
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
                            <option value="onsite">On-site</option>
                            <option value="remote">Remote</option>
                            <option value="hybrid">Hybrid</option>
                        </select>
                    </div>
                    <div id="location_field">
                        <label for="location" class="block text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">
                            Location <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="location" name="location" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="e.g., Makati City, Metro Manila">
                    </div>
                </div>
            </div>

            <!-- Compensation -->
            <div class="px-6 py-5 border-t border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 mb-4"><span id="compensation_title">Compensation</span></h2>

                <!-- Unpaid Internship Checkbox -->
                <div id="unpaid_internship_field" class="hidden mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" id="is_unpaid" name="is_unpaid" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" onchange="toggleAllowanceFields()">
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
                            <input type="number" id="salary_min" name="salary_min" min="0" required class="block w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="30000">
                        </div>
                    </div>
                    <div>
                        <label for="salary_max" class="block text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">
                            <span id="salary_max_label">Maximum Salary</span> <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-2 text-gray-500">₱</span>
                            <input type="number" id="salary_max" name="salary_max" min="0" required class="block w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="50000">
                        </div>
                    </div>
                    <div id="salary_period_field">
                        <label for="salary_period" class="block text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">
                            Pay Period <span class="text-red-500">*</span>
                        </label>
                        <select id="salary_period" name="salary_period" required class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="monthly">Monthly</option>
                            <option value="hourly">Hourly</option>
                            <option value="daily">Daily</option>
                            <option value="project">Per Project</option>
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
                                <option value="1">1 month</option>
                                <option value="2">2 months</option>
                                <option value="3">3 months</option>
                                <option value="4">4 months</option>
                                <option value="5">5 months</option>
                                <option value="6">6 months</option>
                                <option value="12">12 months</option>
                            </select>
                        </div>
                        <div>
                            <label for="preferred_start_date" class="block text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">
                                Preferred Start Date
                            </label>
                            <input type="date" id="preferred_start_date" name="preferred_start_date" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
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
                        <textarea id="education_requirements" name="education_requirements" rows="3" required class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="One requirement per line..."></textarea>
                    </div>

                    <!-- Technical Skills -->
                    <div>
                        <label class="block text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">
                            Technical Skills <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" id="skills_input" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="e.g., JavaScript, React, Node.js (press Enter)" onkeydown="handleSkillsInput(event)">
                            <input type="hidden" id="technical_skills" name="technical_skills">
                        </div>
                        <div id="skills_tags" class="flex flex-wrap gap-2 mt-2"></div>
                        <p class="mt-1 text-sm text-gray-500">Type a skill and press Enter to add.</p>
                    </div>

                    <!-- Experience Requirements -->
                    <div>
                        <label for="experience_requirements" class="block text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">
                            Additional Experience Requirements
                        </label>
                        <textarea id="experience_requirements" name="experience_requirements" rows="3" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="One requirement per line..."></textarea>
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
                        <input type="date" id="application_deadline" name="application_deadline" required class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="positions_available" class="block text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">
                            Number of Positions <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="positions_available" name="positions_available" min="1" required class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="1">
                    </div>
                </div>
                <div>
                    <label for="application_process" class="block text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">
                        Application Instructions
                    </label>
                    <textarea id="application_process" name="application_process" rows="3" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="One step per line..."></textarea>
                </div>
            </div>

            <!-- Benefits -->
            <div class="px-6 py-5 border-t border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Benefits & Perks</h2>
                <textarea id="benefits" name="benefits" rows="3" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" placeholder="One benefit per line..."></textarea>
            </div>

            <!-- Action Buttons -->
            <div class="px-6 py-5 bg-gray-50 border-t border-gray-200">
                <div class="flex justify-between">
                    <div class="flex space-x-3">
                        <button type="button" onclick="previewJob()" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 font-medium">
                            Preview
                        </button>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-medium">
                            Submit for Review
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Preview Modal -->
<div id="previewModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <div class="relative bg-white rounded-lg max-w-6xl w-full shadow-xl">
            <div class="px-6 py-5 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-xl font-semibold text-gray-900">Job Posting Preview</h3>
                <button onclick="closePreviewModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-6 max-h-[80vh] overflow-y-auto">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2 space-y-8">
                        <div class="bg-white shadow rounded-lg p-6">
                            <div id="previewDescription"></div>
                            <div id="previewRequirements" class="mt-6"></div>
                            <div id="previewBenefits" class="mt-6"></div>
                            <div id="previewApplicationProcess" class="mt-6"></div>
                        </div>
                    </div>
                    <div class="space-y-6">
                        <div class="bg-white shadow rounded-lg p-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Job Details</h2>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Job Type</p>
                                    <p id="previewJobType" class="text-sm text-gray-600"></p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Department</p>
                                    <p id="previewDepartment" class="text-sm text-gray-600"></p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Experience</p>
                                    <p id="previewExperienceLevel" class="text-sm text-gray-600"></p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Work Setup</p>
                                    <p id="previewWorkSetup" class="text-sm text-gray-600"></p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Location</p>
                                    <p id="previewLocation" class="text-sm text-gray-600"></p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Compensation</p>
                                    <p id="previewCompensation" class="text-sm text-gray-600"></p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Positions</p>
                                    <p id="previewPositions" class="text-sm text-gray-600"></p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Deadline</p>
                                    <p id="previewDeadline" class="text-sm text-gray-600"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                <button onclick="closePreviewModal()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
                    Edit
                </button>
                <button onclick="submitForm()" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Submit for Review
                </button>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/partner/job-postings-create.js') }}"></script>
@endsection