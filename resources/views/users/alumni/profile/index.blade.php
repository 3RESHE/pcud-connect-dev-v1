@extends('layouts.alumni')

@section('title', 'My Profile - PCU-DASMA Connect')

@section('content')
<!-- Main Content -->
<div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">My Profile</h1>
        <p class="text-gray-600">Keep your professional profile updated to attract opportunities</p>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <!-- Profile Form -->
    <form method="POST" action="{{ route('alumni.profile.update') }}" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')

        <!-- Personal Information -->
        <div class="bg-white shadow-sm rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Personal Information</h3>
                <p class="text-sm text-gray-600">Basic information about yourself</p>
            </div>
            <div class="px-6 py-6 space-y-6">
                <!-- Profile Photo -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Profile Photo</label>
                    <div class="flex items-center space-x-6">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center overflow-hidden">
                            @if(auth()->user()->profile_photo)
                                <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" alt="Profile" class="w-full h-full object-cover" />
                            @else
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            @endif
                        </div>
                        <div>
                            <input type="file" name="profile_photo" accept="image/*" class="block text-sm text-gray-600 mb-1">
                            <p class="text-xs text-gray-500">PNG, JPG up to 2MB</p>
                            @error('profile_photo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Professional Headline -->
                <div>
                    <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">
                        Professional Headline <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="bio"
                        name="bio"
                        value="{{ auth()->user()->bio }}"
                        required
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                        placeholder="e.g., Recent BSIT Graduate | Aspiring Full-Stack Developer | Open to Entry-Level Tech Roles"
                    />
                    @error('bio') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Full Name (First, Middle, Last, Suffix) -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">
                            First Name <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="first_name"
                            name="first_name"
                            value="{{ auth()->user()->first_name }}"
                            required
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                        />
                        @error('first_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="middle_name" class="block text-sm font-medium text-gray-700 mb-1">
                            Middle Name <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="middle_name"
                            name="middle_name"
                            value="{{ auth()->user()->middle_name }}"
                            required
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                        />
                        @error('middle_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">
                            Last Name <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="last_name"
                            name="last_name"
                            value="{{ auth()->user()->last_name }}"
                            required
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                        />
                        @error('last_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="suffix" class="block text-sm font-medium text-gray-700 mb-1">
                            Name Suffix
                        </label>
                        <input
                            type="text"
                            id="suffix"
                            name="suffix"
                            value="{{ auth()->user()->suffix }}"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                            placeholder="e.g., Jr., Sr., II"
                        />
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ auth()->user()->email }}"
                            required
                            class="block w-full px-3 py-2 border border-gray-300 bg-gray-50 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                        />
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">
                            Phone Number
                        </label>
                        <input
                            type="tel"
                            id="phone_number"
                            name="phone_number"
                            value="{{ auth()->user()->phone_number }}"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                        />
                    </div>
                </div>

                <!-- Location -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-1">
                            Current Location
                        </label>
                        <input
                            type="text"
                            id="location"
                            name="location"
                            value="{{ auth()->user()->location }}"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                            placeholder="City, Province/State"
                        />
                    </div>
                    <div>
                        <label for="willing_to_relocate" class="block text-sm font-medium text-gray-700 mb-1">
                            Willing to Relocate?
                        </label>
                        <select
                            id="willing_to_relocate"
                            name="willing_to_relocate"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                        >
                            <option value="yes" {{ auth()->user()->willing_to_relocate == 'yes' ? 'selected' : '' }}>Yes</option>
                            <option value="no" {{ auth()->user()->willing_to_relocate == 'no' ? 'selected' : '' }}>No</option>
                            <option value="maybe" {{ auth()->user()->willing_to_relocate == 'maybe' ? 'selected' : '' }}>Open to discussion</option>
                        </select>
                    </div>
                </div>

                <!-- Social & Professional Links -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Social & Professional Links (Optional)</label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="linkedin_url" class="block text-xs text-gray-600 mb-1">LinkedIn</label>
                            <input
                                type="url"
                                id="linkedin_url"
                                name="linkedin_url"
                                value="{{ auth()->user()->linkedin_url }}"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                                placeholder="https://linkedin.com/in/username"
                            />
                        </div>
                        <div>
                            <label for="github_url" class="block text-xs text-gray-600 mb-1">GitHub</label>
                            <input
                                type="url"
                                id="github_url"
                                name="github_url"
                                value="{{ auth()->user()->github_url }}"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                                placeholder="https://github.com/username"
                            />
                        </div>
                        <div>
                            <label for="portfolio_url" class="block text-xs text-gray-600 mb-1">Portfolio/Website</label>
                            <input
                                type="url"
                                id="portfolio_url"
                                name="portfolio_url"
                                value="{{ auth()->user()->portfolio_url }}"
                                class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                                placeholder="https://yourwebsite.com"
                            />
                        </div>
                    </div>
                </div>

                <!-- Resume Upload -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Resume/CV</label>
                    <div class="flex items-center space-x-6">
                        <button
                            type="button"
                            class="bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary"
                        >
                            Upload Resume
                        </button>
                        <p class="text-xs text-gray-500">
                            PDF up to 5MB
                        </p>
                    </div>
                </div>

                <!-- Professional Summary -->
                <div>
                    <label for="bio_long" class="block text-sm font-medium text-gray-700 mb-1">
                        Professional Summary
                    </label>
                    <textarea
                        id="bio_long"
                        name="bio_long"
                        rows="4"
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                        placeholder="Write a brief summary about your professional background, skills, and career objectives..."
                    >{{ auth()->user()->bio_long }}</textarea>
                </div>
            </div>
        </div>

        <!-- Academic Background -->
        <div class="bg-white shadow-sm rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Academic Background</h3>
                <p class="text-sm text-gray-600">Your educational history at PCU-DASMA</p>
            </div>
            <div class="px-6 py-6 space-y-6">
                <!-- Degree Information -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="degree_program" class="block text-sm font-medium text-gray-700 mb-1">
                            Degree Program <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="degree_program"
                            name="degree_program"
                            required
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                        >
                            <option value="">Select Program</option>
                            <option value="bsit" {{ auth()->user()->degree_program == 'bsit' ? 'selected' : '' }}>Bachelor of Science in Information Technology</option>
                            <option value="bscs" {{ auth()->user()->degree_program == 'bscs' ? 'selected' : '' }}>Bachelor of Science in Computer Science</option>
                            <option value="bsba" {{ auth()->user()->degree_program == 'bsba' ? 'selected' : '' }}>Bachelor of Science in Business Administration</option>
                            <option value="bsed" {{ auth()->user()->degree_program == 'bsed' ? 'selected' : '' }}>Bachelor of Science in Education</option>
                            <option value="bsn" {{ auth()->user()->degree_program == 'bsn' ? 'selected' : '' }}>Bachelor of Science in Nursing</option>
                            <option value="bsce" {{ auth()->user()->degree_program == 'bsce' ? 'selected' : '' }}>Bachelor of Science in Civil Engineering</option>
                            <option value="bsee" {{ auth()->user()->degree_program == 'bsee' ? 'selected' : '' }}>Bachelor of Science in Electrical Engineering</option>
                            <option value="bsme" {{ auth()->user()->degree_program == 'bsme' ? 'selected' : '' }}>Bachelor of Science in Mechanical Engineering</option>
                            <option value="bscrim" {{ auth()->user()->degree_program == 'bscrim' ? 'selected' : '' }}>Bachelor of Science in Criminology</option>
                            <option value="bshm" {{ auth()->user()->degree_program == 'bshm' ? 'selected' : '' }}>Bachelor of Science in Hotel Management</option>
                            <option value="bssw" {{ auth()->user()->degree_program == 'bssw' ? 'selected' : '' }}>Bachelor of Science in Social Work</option>
                        </select>
                        @error('degree_program') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="graduation_year" class="block text-sm font-medium text-gray-700 mb-1">
                            Graduation Year <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="graduation_year"
                            name="graduation_year"
                            required
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                        >
                            <option value="">Select Year</option>
                            @for ($year = 2025; $year >= 1980; $year--)
                                <option value="{{ $year }}" {{ auth()->user()->graduation_year == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endfor
                        </select>
                        @error('graduation_year') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                <!-- GPA and Honors -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="gpa" class="block text-sm font-medium text-gray-700 mb-1">
                            General Weighted Average (GWA)
                        </label>
                        <input
                            type="number"
                            id="gpa"
                            name="gpa"
                            value="{{ auth()->user()->gpa }}"
                            min="1.00"
                            max="5.00"
                            step="0.01"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                            placeholder="e.g., 1.25"
                        />
                    </div>
                    <div>
                        <label for="honors" class="block text-sm font-medium text-gray-700 mb-1">
                            Academic Honors
                        </label>
                        <select
                            id="honors"
                            name="honors"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                        >
                            <option value="">None</option>
                            <option value="summa" {{ auth()->user()->honors == 'summa' ? 'selected' : '' }}>Summa Cum Laude</option>
                            <option value="magna" {{ auth()->user()->honors == 'magna' ? 'selected' : '' }}>Magna Cum Laude</option>
                            <option value="cum" {{ auth()->user()->honors == 'cum' ? 'selected' : '' }}>Cum Laude</option>
                            <option value="dean" {{ auth()->user()->honors == 'dean' ? 'selected' : '' }}>Dean's List</option>
                        </select>
                    </div>
                </div>

                <!-- Thesis/Capstone -->
                <div>
                    <label for="thesis_title" class="block text-sm font-medium text-gray-700 mb-1">
                        Thesis/Capstone Project Title (Optional)
                    </label>
                    <input
                        type="text"
                        id="thesis_title"
                        name="thesis_title"
                        value="{{ auth()->user()->thesis_title }}"
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                        placeholder="Title of your thesis or capstone project"
                    />
                </div>

                <!-- Organizations -->
                <div>
                    <label for="organizations" class="block text-sm font-medium text-gray-700 mb-1">
                        Student Organizations & Activities
                    </label>
                    <textarea
                        id="organizations"
                        name="organizations"
                        rows="3"
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                        placeholder="List student organizations, leadership roles, and extracurricular activities..."
                    >{{ auth()->user()->organizations }}</textarea>
                </div>
            </div>
        </div>

        <!-- Professional Experience -->
        <div class="bg-white shadow-sm rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Professional Experience</h3>
                    <p class="text-sm text-gray-600">Your work history and internships</p>
                </div>
                <button
                    type="button"
                    onclick="addExperience()"
                    class="text-primary hover:text-blue-700 text-sm font-medium"
                >
                    + Add Experience
                </button>
            </div>
            <div class="px-6 py-6">
                <div id="experienceList" class="space-y-6">
                    <!-- Experience items will be added here -->
                </div>
            </div>
        </div>

        <!-- Projects & Portfolio -->
        <div class="bg-white shadow-sm rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Projects & Portfolio (Optional)</h3>
                    <p class="text-sm text-gray-600">Showcase your key projects and work samples if applicable</p>
                </div>
                <button
                    type="button"
                    onclick="addProject()"
                    class="text-primary hover:text-blue-700 text-sm font-medium"
                >
                    + Add Project
                </button>
            </div>
            <div class="px-6 py-6">
                <div id="projectList" class="space-y-6">
                    <!-- Project items will be added here -->
                </div>
            </div>
        </div>

        <!-- Skills & Competencies -->
        <div class="bg-white shadow-sm rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Skills & Competencies</h3>
                <p class="text-sm text-gray-600">Showcase your technical and soft skills</p>
            </div>
            <div class="px-6 py-6 space-y-6">
                <!-- Technical Skills -->
                <div>
                    <label for="technical_skills" class="block text-sm font-medium text-gray-700 mb-1">
                        Technical/Professional Skills
                    </label>
                    <textarea
                        id="technical_skills"
                        name="technical_skills"
                        rows="3"
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                        placeholder="List your technical or professional skills relevant to your field..."
                    >{{ auth()->user()->technical_skills }}</textarea>
                </div>

                <!-- Soft Skills -->
                <div>
                    <label for="soft_skills" class="block text-sm font-medium text-gray-700 mb-1">
                        Soft Skills
                    </label>
                    <textarea
                        id="soft_skills"
                        name="soft_skills"
                        rows="3"
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                        placeholder="List your soft skills and personal attributes..."
                    >{{ auth()->user()->soft_skills }}</textarea>
                </div>

                <!-- Certifications -->
                <div>
                    <label for="certifications" class="block text-sm font-medium text-gray-700 mb-1">
                        Certifications & Licenses (Optional)
                    </label>
                    <textarea
                        id="certifications"
                        name="certifications"
                        rows="3"
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                        placeholder="List your professional certifications, licenses, and training certificates..."
                    >{{ auth()->user()->certifications }}</textarea>
                </div>

                <!-- Languages -->
                <div>
                    <label for="languages" class="block text-sm font-medium text-gray-700 mb-1">
                        Languages
                    </label>
                    <textarea
                        id="languages"
                        name="languages"
                        rows="2"
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                        placeholder="List languages and proficiency levels..."
                    >{{ auth()->user()->languages }}</textarea>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end gap-4">
            <button
                type="reset"
                class="px-6 py-2 bg-gray-200 text-gray-900 rounded-md hover:bg-gray-300 font-medium"
            >
                Cancel
            </button>
            <button
                type="submit"
                class="px-6 py-2 bg-primary text-white rounded-md hover:bg-blue-700 font-medium"
            >
                Save Changes
            </button>
        </div>
    </form>
</div>

<script>
    function addExperience() {
        const experienceList = document.getElementById('experienceList');
        const newExperience = document.createElement('div');
        newExperience.className = 'experience-item border border-gray-200 rounded-lg p-4';
        newExperience.innerHTML = `
            <div class="grid grid-cols-1 gap-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Job Title</label>
                        <input type="text" name="experience_job_title[]" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Company</label>
                        <input type="text" name="experience_company[]" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" required>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                        <input type="date" name="experience_start[]" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                        <input type="date" name="experience_end[]" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                        <div class="mt-1">
                            <label class="flex items-center">
                                <input type="checkbox" name="experience_current[]" class="rounded border-gray-300 text-primary focus:ring-primary">
                                <span class="ml-2 text-xs text-gray-600">Current position</span>
                            </label>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                        <input type="text" name="experience_location[]" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Job Description</label>
                    <textarea name="experience_description[]" rows="3" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" placeholder="Describe your responsibilities and achievements..." required></textarea>
                </div>
            </div>
            <div class="mt-4 flex justify-end">
                <button type="button" onclick="this.closest('.experience-item').remove()" class="text-red-600 hover:text-red-800 text-sm">Remove</button>
            </div>
        `;
        experienceList.appendChild(newExperience);
    }

    function addProject() {
        const projectList = document.getElementById('projectList');
        const newProject = document.createElement('div');
        newProject.className = 'project-item border border-gray-200 rounded-lg p-4';
        newProject.innerHTML = `
            <div class="grid grid-cols-1 gap-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Project Title</label>
                        <input type="text" name="project_title[]" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Link/URL (Optional)</label>
                        <input type="url" name="project_url[]" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                        <input type="date" name="project_start[]" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                        <input type="date" name="project_end[]" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="project_description[]" rows="3" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" placeholder="Describe the project, technologies used, and outcomes..." required></textarea>
                </div>
            </div>
            <div class="mt-4 flex justify-end">
                <button type="button" onclick="this.closest('.project-item').remove()" class="text-red-600 hover:text-red-800 text-sm">Remove</button>
            </div>
        `;
        projectList.appendChild(newProject);
    }

    document.addEventListener('click', function (event) {
        const userMenu = document.getElementById('userMenu');
        const button = event.target.closest('button[onclick="toggleUserMenu()"]');

        if (!button && !userMenu?.contains(event.target)) {
            userMenu?.classList.add('hidden');
        }
    });
</script>
@endsection
