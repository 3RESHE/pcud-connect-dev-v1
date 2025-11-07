@extends('layouts.student')

@section('title', 'My Profile - PCU-DASMA Connect')

@section('content')
<!-- Main Content -->
<div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">My Profile</h1>
        <p class="text-gray-600">Build your academic and professional profile for future opportunities</p>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4 text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <!-- Profile Form -->
    <form method="POST" action="{{ route('student.profile.update') }}" enctype="multipart/form-data" class="space-y-8">
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
                            @error('profile_photo')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
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
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary @error('bio') border-red-500 @enderror"
                        placeholder="e.g., BSIT Student | Aspiring Full-Stack Developer"
                    />
                    @error('bio') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Full Name -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary @error('first_name') border-red-500 @enderror"
                        />
                        @error('first_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
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
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary @error('last_name') border-red-500 @enderror"
                        />
                        @error('last_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
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
                            class="block w-full px-3 py-2 border border-gray-300 bg-gray-50 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary @error('email') border-red-500 @enderror"
                        />
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">
                            Phone Number <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="tel"
                            id="phone_number"
                            name="phone_number"
                            value="{{ auth()->user()->phone_number }}"
                            required
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary @error('phone_number') border-red-500 @enderror"
                        />
                        @error('phone_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
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
            </div>
        </div>

        <!-- Skills & Competencies -->
        <div class="bg-white shadow-sm rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Skills & Competencies</h3>
                <p class="text-sm text-gray-600">Showcase your abilities and areas of interest</p>
            </div>
            <div class="px-6 py-6 space-y-6">
                <!-- Skills -->
                <div>
                    <label for="skills" class="block text-sm font-medium text-gray-700 mb-1">Technical & Professional Skills</label>
                    <textarea
                        id="skills"
                        name="skills"
                        rows="3"
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                        placeholder="e.g., HTML, CSS, JavaScript, Python, Java, Database Management..."
                    >{{ auth()->user()->skills }}</textarea>
                </div>
            </div>
        </div>

        <!-- Experience & Activities -->
        <div class="bg-white shadow-sm rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Experience & Activities</h3>
                    <p class="text-sm text-gray-600">Internships, part-time work, and extracurricular activities</p>
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
                    <p class="text-sm text-gray-600">Showcase your academic projects and work samples</p>
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
                        <label class="block text-sm font-medium text-gray-700 mb-1">Role/Position</label>
                        <input type="text" name="experience_role[]" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Organization</label>
                        <input type="text" name="experience_organization[]" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" required>
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
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                        <select name="experience_type[]" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" required>
                            <option value="part_time">Part-time Work</option>
                            <option value="internship">Internship</option>
                            <option value="volunteer">Volunteer Work</option>
                            <option value="organization">Student Organization</option>
                            <option value="competition">Competition</option>
                            <option value="project">Project</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description & Achievements</label>
                    <textarea name="experience_description[]" rows="3" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" placeholder="Describe your role, responsibilities, and key achievements..." required></textarea>
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

    // Close user menu when clicking outside
    document.addEventListener('click', function (event) {
        const userMenu = document.getElementById('userMenu');
        const button = event.target.closest('button[onclick="toggleUserMenu()"]');

        if (!button && !userMenu?.contains(event.target)) {
            userMenu?.classList.add('hidden');
        }
    });
</script>
@endsection
