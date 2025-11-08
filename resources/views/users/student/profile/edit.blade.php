@extends('layouts.student')

<meta name="csrf-token" content="{{ csrf_token() }}">

@section('title', 'Edit My Profile - PCU-DASMA Connect')

@section('content')
    <div class="w-full bg-gray-50 min-h-screen py-4 sm:py-6 lg:py-8">
        <div class="w-full max-w-5xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">

            <!-- Header -->
            <div class="mb-6 sm:mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="min-w-0">
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 break-words">Edit My Profile</h1>
                        <p class="text-sm sm:text-base text-gray-600 mt-1 break-words">Build your academic and professional
                            profile</p>
                    </div>
                    <a href="{{ route('student.profile.show') }}"
                        class="px-3 sm:px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm sm:text-base whitespace-nowrap text-center">
                        View Profile
                    </a>
                </div>
            </div>

            <!-- Toast Container -->
            <div id="toastContainer" class="fixed bottom-4 right-4 z-50 max-w-xs mx-2 sm:max-w-sm"></div>

            <!-- Profile Form -->
            <form id="profileForm" class="space-y-4 sm:space-y-6 md:space-y-8">
                @csrf

                <!-- Personal Information -->
                <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                    <div class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 break-words">Personal Information</h3>
                        <p class="text-xs sm:text-sm text-gray-600 mt-1 break-words">Your basic profile information</p>
                    </div>
                    <div class="px-3 sm:px-4 md:px-6 py-4 sm:py-6 space-y-4 sm:space-y-6">

                        <!-- Profile Photo -->
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2 sm:mb-3">Profile
                                Photo</label>
                            <div class="flex flex-col sm:flex-row gap-4 sm:gap-6">
                                <div class="flex-shrink-0">
                                    @if ($profile && $profile->profile_photo)
                                        <img src="{{ asset('storage/' . $profile->profile_photo) }}" alt="Profile Photo"
                                            class="w-20 h-20 sm:w-24 sm:h-24 rounded-full object-cover">
                                    @else
                                        <div
                                            class="w-20 h-20 sm:w-24 sm:h-24 bg-gray-100 rounded-full flex items-center justify-center">
                                            <svg class="w-10 h-10 sm:w-12 sm:h-12 text-gray-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                </path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <label class="inline-block">
                                        <input type="file" name="profile_photo" id="profile_photo" class="hidden"
                                            accept="image/*" onchange="previewPhoto(event)">
                                        <span
                                            class="inline-block bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-xs sm:text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 cursor-pointer transition whitespace-nowrap">
                                            Change Photo
                                        </span>
                                    </label>
                                    <p class="text-xs text-gray-500 mt-2 break-words">PNG, JPG up to 2MB</p>
                                    <span id="profile_photoError"
                                        class="text-red-500 text-xs mt-1 block break-words hidden"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Headline & Contact (WITH ERRORS) -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                            <div class="min-w-0">
                                <label for="headline"
                                    class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2 break-words">
                                    Professional Headline
                                </label>
                                <input type="text" id="headline" name="headline" value="{{ $profile?->headline ?? '' }}"
                                    class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                    placeholder="e.g., BSIT Student | Full-Stack Developer">
                                <span id="headlineError" class="text-red-500 text-xs mt-1 block break-words hidden"></span>
                            </div>
                            <div class="min-w-0">
                                <label for="personal_email"
                                    class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2 break-words">
                                    Personal Email
                                </label>
                                <input type="email" id="personal_email" name="personal_email"
                                    value="{{ $profile?->personal_email ?? '' }}"
                                    class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                    placeholder="your.email@example.com">
                                <span id="personal_emailError"
                                    class="text-red-500 text-xs mt-1 block break-words hidden"></span>
                            </div>
                        </div>

                        <!-- Phone & DOB (WITH ERRORS) -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                            <div class="min-w-0">
                                <label for="phone"
                                    class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2 break-words">
                                    Phone Number
                                </label>
                                <input type="tel" id="phone" name="phone" value="{{ $profile?->phone ?? '' }}"
                                    class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                    placeholder="+63 9XX-XXXX-XXXX">
                                <span id="phoneError" class="text-red-500 text-xs mt-1 block break-words hidden"></span>
                            </div>
                            <div class="min-w-0">
                                <label for="date_of_birth"
                                    class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2 break-words">
                                    Date of Birth
                                </label>
                                <input type="date" id="date_of_birth" name="date_of_birth"
                                    value="{{ $profile?->date_of_birth?->format('Y-m-d') ?? '' }}"
                                    class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                <span id="date_of_birthError"
                                    class="text-red-500 text-xs mt-1 block break-words hidden"></span>
                            </div>
                        </div>

                        <!-- Gender & Emergency Contact (WITH ERRORS) -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                            <div class="min-w-0">
                                <label for="gender"
                                    class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2 break-words">
                                    Gender
                                </label>
                                <select id="gender" name="gender"
                                    class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                    <option value="">Select Gender</option>
                                    <option value="male" @selected($profile?->gender === 'male')>Male</option>
                                    <option value="female" @selected($profile?->gender === 'female')>Female</option>
                                    <option value="other" @selected($profile?->gender === 'other')>Other</option>
                                    <option value="prefer_not_to_say" @selected($profile?->gender === 'prefer_not_to_say')>Prefer Not to Say
                                    </option>
                                </select>
                                <span id="genderError" class="text-red-500 text-xs mt-1 block break-words hidden"></span>
                            </div>
                            <div class="min-w-0">
                                <label for="emergency_contact"
                                    class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2 break-words">
                                    Emergency Contact
                                </label>
                                <input type="text" id="emergency_contact" name="emergency_contact"
                                    value="{{ $profile?->emergency_contact ?? '' }}"
                                    class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                    placeholder="Parent/Guardian name">
                                <span id="emergency_contactError"
                                    class="text-red-500 text-xs mt-1 block break-words hidden"></span>
                            </div>
                        </div>

                        <!-- Address (WITH ERROR) -->
                        <div class="min-w-0">
                            <label for="address"
                                class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2 break-words">
                                Address
                            </label>
                            <textarea id="address" name="address" rows="3"
                                class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm resize-none"
                                placeholder="Your complete address...">{{ $profile?->address ?? '' }}</textarea>
                            <span id="addressError" class="text-red-500 text-xs mt-1 block break-words hidden"></span>
                        </div>


                    </div>
                </div>

                <!-- Social & Professional Links (WITH ERRORS) -->
                <div class="px-3 sm:px-4 md:px-6 py-4 sm:py-6 space-y-4 sm:space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                        <div class="min-w-0">
                            <label for="linkedin_url"
                                class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2 break-words">
                                LinkedIn Profile
                            </label>
                            <input type="url" id="linkedin_url" name="linkedin_url"
                                value="{{ $profile?->linkedin_url ?? '' }}"
                                class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                placeholder="https://linkedin.com/in/username">
                            <span id="linkedin_urlError"
                                class="text-red-500 text-xs mt-1 block break-words hidden"></span>
                        </div>
                        <div class="min-w-0">
                            <label for="github_url"
                                class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2 break-words">
                                GitHub Profile
                            </label>
                            <input type="url" id="github_url" name="github_url"
                                value="{{ $profile?->github_url ?? '' }}"
                                class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                placeholder="https://github.com/username">
                            <span id="github_urlError" class="text-red-500 text-xs mt-1 block break-words hidden"></span>
                        </div>
                    </div>
                    <div class="min-w-0">
                        <label for="portfolio_url"
                            class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2 break-words">
                            Portfolio/Website
                        </label>
                        <input type="url" id="portfolio_url" name="portfolio_url"
                            value="{{ $profile?->portfolio_url ?? '' }}"
                            class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                            placeholder="https://yourportfolio.com">
                        <span id="portfolio_urlError" class="text-red-500 text-xs mt-1 block break-words hidden"></span>
                    </div>
                </div>


                <!-- Skills & Certifications (WITH ERRORS) -->
                <div class="px-3 sm:px-4 md:px-6 py-4 sm:py-6 space-y-4 sm:space-y-6">
                    <div class="min-w-0">
                        <label for="technical_skills"
                            class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2 break-words">
                            Technical Skills
                        </label>
                        <textarea id="technical_skills" name="technical_skills" rows="3"
                            class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm resize-none"
                            placeholder="e.g., HTML, CSS, JavaScript, Python, Java...">{{ $profile?->technical_skills ?? '' }}</textarea>
                        <span id="technical_skillsError"
                            class="text-red-500 text-xs mt-1 block break-words hidden"></span>
                    </div>
                    <div class="min-w-0">
                        <label for="soft_skills"
                            class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2 break-words">
                            Soft Skills
                        </label>
                        <textarea id="soft_skills" name="soft_skills" rows="3"
                            class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm resize-none"
                            placeholder="e.g., Communication, Leadership, Problem Solving...">{{ $profile?->soft_skills ?? '' }}</textarea>
                        <span id="soft_skillsError" class="text-red-500 text-xs mt-1 block break-words hidden"></span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                        <div class="min-w-0">
                            <label for="certifications"
                                class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2 break-words">
                                Certifications
                            </label>
                            <textarea id="certifications" name="certifications" rows="3"
                                class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm resize-none"
                                placeholder="e.g., Google Cloud Certification...">{{ $profile?->certifications ?? '' }}</textarea>
                            <span id="certificationsError"
                                class="text-red-500 text-xs mt-1 block break-words hidden"></span>
                        </div>
                        <div class="min-w-0">
                            <label for="languages"
                                class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2 break-words">
                                Languages
                            </label>
                            <textarea id="languages" name="languages" rows="3"
                                class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm resize-none"
                                placeholder="e.g., English, Filipino, Mandarin...">{{ $profile?->languages ?? '' }}</textarea>
                            <span id="languagesError" class="text-red-500 text-xs mt-1 block break-words hidden"></span>
                        </div>
                    </div>
                </div>

                <!-- Experiences Section -->
                <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                    <div
                        class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 border-b border-gray-200 bg-gray-50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div class="min-w-0">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900 break-words">Experience</h3>
                            <p class="text-xs sm:text-sm text-gray-600 mt-1 break-words">Internships, part-time work,
                                volunteer work</p>
                        </div>
                        <button type="button" onclick="addExperience()"
                            class="px-3 sm:px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-xs sm:text-sm font-medium whitespace-nowrap">
                            + Add Experience
                        </button>
                    </div>
                    <div class="px-3 sm:px-4 md:px-6 py-4 sm:py-6">
                        <div id="experiencesList" class="space-y-4 sm:space-y-6">
                            @forelse ($experiences as $exp)
                                <div class="experience-item border border-gray-200 rounded-lg p-4 sm:p-6"
                                    data-id="{{ $exp->id }}">
                                    <input type="hidden" name="experience_id[]" value="{{ $exp->id }}">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                                        <div class="min-w-0">
                                            <label
                                                class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Role/Position</label>
                                            <input type="text" name="experience_role[]"
                                                value="{{ $exp->role_position }}" required
                                                class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm">
                                            <span class="experienceError text-red-500 text-xs mt-1 block hidden"></span>
                                        </div>
                                        <div class="min-w-0">
                                            <label
                                                class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Organization</label>
                                            <input type="text" name="experience_org[]"
                                                value="{{ $exp->organization }}" required
                                                class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm">
                                            <span class="experienceError text-red-500 text-xs mt-1 block hidden"></span>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6 mt-4">
                                        <div class="min-w-0">
                                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Start
                                                Date</label>
                                            <input type="date" name="experience_start[]"
                                                value="{{ $exp->start_date?->format('Y-m-d') }}" required
                                                class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm">
                                            <span class="experienceError text-red-500 text-xs mt-1 block hidden"></span>
                                        </div>
                                        <div class="min-w-0">
                                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">End
                                                Date</label>
                                            <input type="date" name="experience_end[]"
                                                value="{{ $exp->end_date?->format('Y-m-d') }}"
                                                class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm">
                                            <span class="experienceError text-red-500 text-xs mt-1 block hidden"></span>
                                        </div>
                                        <div class="min-w-0">
                                            <label
                                                class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Type</label>
                                            <select name="experience_type[]" required
                                                class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm">
                                                <option value="">Select Type</option>
                                                <option value="internship" @selected($exp->experience_type === 'internship')>Internship</option>
                                                <option value="part_time" @selected($exp->experience_type === 'part_time')>Part-time</option>
                                                <option value="volunteer" @selected($exp->experience_type === 'volunteer')>Volunteer</option>
                                                <option value="full_time" @selected($exp->experience_type === 'full_time')>Full-time</option>
                                                <option value="freelance" @selected($exp->experience_type === 'freelance')>Freelance</option>
                                            </select>
                                            <span class="experienceError text-red-500 text-xs mt-1 block hidden"></span>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <label
                                            class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Description</label>
                                        <textarea name="experience_desc[]" rows="3" required
                                            class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm resize-none">{{ $exp->description }}</textarea>
                                        <span class="experienceError text-red-500 text-xs mt-1 block hidden"></span>
                                    </div>
                                    <div class="mt-4 flex justify-end gap-2">
                                        <button type="button" onclick="submitExperience(this)"
                                            class="px-3 py-1 bg-green-600 text-white hover:bg-green-700 text-xs sm:text-sm font-medium rounded">
                                            Update
                                        </button>
                                        <button type="button" onclick="deleteExperience(this, {{ $exp->id }})"
                                            class="px-3 py-1 text-red-600 hover:text-red-800 text-xs sm:text-sm font-medium">
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">No experiences added yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>


                <!-- Projects Section -->
                <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                    <div
                        class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 border-b border-gray-200 bg-gray-50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div class="min-w-0">
                            <h3 class="text-base sm:text-lg font-semibold text-gray-900 break-words">Projects & Portfolio
                            </h3>
                            <p class="text-xs sm:text-sm text-gray-600 mt-1 break-words">Academic and personal projects</p>
                        </div>
                        <button type="button" onclick="addProject()"
                            class="px-3 sm:px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-xs sm:text-sm font-medium whitespace-nowrap">
                            + Add Project
                        </button>
                    </div>
                    <div class="px-3 sm:px-4 md:px-6 py-4 sm:py-6">
                        <div id="projectsList" class="space-y-4 sm:space-y-6">
                            @forelse ($projects as $proj)
                                <div class="project-item border border-gray-200 rounded-lg p-4 sm:p-6"
                                    data-id="{{ $proj->id }}">
                                    <input type="hidden" name="project_id[]" value="{{ $proj->id }}">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                                        <div class="min-w-0">
                                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Project
                                                Title</label>
                                            <input type="text" name="project_title[]" value="{{ $proj->title }}"
                                                required
                                                class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm">
                                            <span class="projectError text-red-500 text-xs mt-1 block hidden"></span>
                                        </div>
                                        <div class="min-w-0">
                                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Project
                                                URL</label>
                                            <input type="url" name="project_url[]" value="{{ $proj->url }}"
                                                class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm"
                                                placeholder="https://...">
                                            <span class="projectError text-red-500 text-xs mt-1 block hidden"></span>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mt-4">
                                        <div class="min-w-0">
                                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Start
                                                Date</label>
                                            <input type="date" name="project_start[]"
                                                value="{{ $proj->start_date?->format('Y-m-d') }}" required
                                                class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm">
                                            <span class="projectError text-red-500 text-xs mt-1 block hidden"></span>
                                        </div>
                                        <div class="min-w-0">
                                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">End
                                                Date</label>
                                            <input type="date" name="project_end[]"
                                                value="{{ $proj->end_date?->format('Y-m-d') }}"
                                                class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm">
                                            <span class="projectError text-red-500 text-xs mt-1 block hidden"></span>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <label
                                            class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Description</label>
                                        <textarea name="project_desc[]" rows="3" required
                                            class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm resize-none">{{ $proj->description }}</textarea>
                                        <span class="projectError text-red-500 text-xs mt-1 block hidden"></span>
                                    </div>
                                    <div class="mt-4 flex justify-end gap-2">
                                        <button type="button" onclick="submitProject(this)"
                                            class="px-3 py-1 bg-green-600 text-white hover:bg-green-700 text-xs sm:text-sm font-medium rounded">
                                            Update
                                        </button>
                                        <button type="button" onclick="deleteProject(this, {{ $proj->id }})"
                                            class="px-3 py-1 text-red-600 hover:text-red-800 text-xs sm:text-sm font-medium">
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">No projects added yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>


                <!-- Action Buttons -->
                <div
                    class="flex flex-col sm:flex-row gap-3 sm:justify-between sm:items-center bg-white shadow-sm rounded-lg p-3 sm:p-4 md:p-6">
                    <a href="{{ route('student.profile.show') }}"
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
    <script>
        function previewPhoto(event) {
            const file = event.target.files[0];
            if (!file) return;

            if (file.size > 2 * 1024 * 1024) {
                showError('profile_photoError', 'File must not exceed 2MB');
                event.target.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.querySelector('.w-20.h-20.sm\\:w-24.sm\\:h-24');
                if (preview && preview.querySelector('svg')) {
                    preview.innerHTML =
                        `<img src="${e.target.result}" alt="Preview" class="w-full h-full rounded-full object-cover">`;
                }
            };
            reader.readAsDataURL(file);
        }

        function addExperience() {
            const list = document.getElementById('experiencesList');
            const item = document.createElement('div');
            item.className = 'experience-item border border-gray-200 rounded-lg p-4 sm:p-6 new-item';
            item.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                <div class="min-w-0">
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Role/Position</label>
                    <input type="text" name="experience_role[]" required class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm">
                    <span class="experienceError text-red-500 text-xs mt-1 block hidden"></span>
                </div>
                <div class="min-w-0">
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Organization</label>
                    <input type="text" name="experience_org[]" required class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm">
                    <span class="experienceError text-red-500 text-xs mt-1 block hidden"></span>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6 mt-4">
                <div class="min-w-0">
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Start Date</label>
                    <input type="date" name="experience_start[]" required class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm">
                    <span class="experienceError text-red-500 text-xs mt-1 block hidden"></span>
                </div>
                <div class="min-w-0">
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">End Date</label>
                    <input type="date" name="experience_end[]" class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm">
                    <span class="experienceError text-red-500 text-xs mt-1 block hidden"></span>
                </div>
                <div class="min-w-0">
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Type</label>
                    <select name="experience_type[]" required class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm">
                        <option value="">Select Type</option>
                        <option value="internship">Internship</option>
                        <option value="part_time">Part-time</option>
                        <option value="volunteer">Volunteer</option>
                        <option value="full_time">Full-time</option>
                        <option value="freelance">Freelance</option>
                    </select>
                    <span class="experienceError text-red-500 text-xs mt-1 block hidden"></span>
                </div>
            </div>
            <div class="mt-4">
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="experience_desc[]" rows="3" required class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm resize-none"></textarea>
                <span class="experienceError text-red-500 text-xs mt-1 block hidden"></span>
            </div>
            <div class="mt-4 flex justify-end gap-2">
                <button type="button" onclick="submitExperience(this)" class="px-3 py-1 bg-green-600 text-white hover:bg-green-700 text-xs sm:text-sm font-medium rounded">
                    Save
                </button>
                <button type="button" onclick="this.closest('.experience-item').remove()" class="px-3 py-1 text-red-600 hover:text-red-800 text-xs sm:text-sm font-medium">
                    Delete
                </button>
            </div>
        `;
            list.appendChild(item);
        }

        function submitExperience(btn) {
            const item = btn.closest('.experience-item');
            const isNew = item.classList.contains('new-item');
            const id = item.dataset.id;

            // Clear previous errors
            item.querySelectorAll('.experienceError').forEach(e => {
                e.classList.add('hidden');
                e.textContent = '';
            });
            item.querySelectorAll('input, select, textarea').forEach(el => {
                el.classList.remove('border-red-500');
            });

            const data = {
                experience_role: item.querySelector('input[name="experience_role[]"]').value,
                experience_org: item.querySelector('input[name="experience_org[]"]').value,
                experience_type: item.querySelector('select[name="experience_type[]"]').value,
                experience_start: item.querySelector('input[name="experience_start[]"]').value,
                experience_end: item.querySelector('input[name="experience_end[]"]').value,
                experience_desc: item.querySelector('textarea[name="experience_desc[]"]').value,
            };

            let url;
            let method;

            if (isNew || !id) {
                url = '/student/experiences';
                method = 'POST';
            } else {
                url = `/student/experiences/${id}`;
                method = 'PUT';
            }

            console.log('Submit Experience:', {
                url,
                method,
                data
            });

            fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(data)
                })
                .then(r => r.json())
                .then(d => {
                    console.log('Response:', d);
                    if (d.success) {
                        showToast('✅ ' + d.message, 'success');
                        if (isNew) item.classList.remove('new-item');
                        item.dataset.id = d.experience?.id;
                    } else {
                        showToast('❌ ' + (d.message || 'Failed to save'), 'error');

                        if (d.errors) {
                            console.log('Experience Errors:', d.errors);
                            displayExperienceErrors(item, d.errors);
                        }
                    }
                })
                .catch(e => {
                    console.error('Fetch Error:', e);
                    showToast('❌ Error: ' + e.message, 'error');
                });
        }

        function displayExperienceErrors(item, errors) {
            const fieldMap = {
                'experience_role': 'input[name="experience_role[]"]',
                'experience_org': 'input[name="experience_org[]"]',
                'experience_type': 'select[name="experience_type[]"]',
                'experience_start': 'input[name="experience_start[]"]',
                'experience_end': 'input[name="experience_end[]"]',
                'experience_desc': 'textarea[name="experience_desc[]"]'
            };

            Object.keys(errors).forEach(field => {
                const fieldSelector = fieldMap[field];
                if (fieldSelector) {
                    const input = item.querySelector(fieldSelector);
                    if (input) {
                        const errorEl = input.parentElement.querySelector('.experienceError');
                        if (errorEl) {
                            errorEl.textContent = errors[field][0];
                            errorEl.classList.remove('hidden');
                        }
                        input.classList.add('border-red-500');

                        input.addEventListener('input', function() {
                            this.classList.remove('border-red-500');
                            const err = this.parentElement.querySelector('.experienceError');
                            if (err) err.classList.add('hidden');
                        }, {
                            once: true
                        });
                    }
                }
            });
        }

        function addProject() {
            const list = document.getElementById('projectsList');
            const item = document.createElement('div');
            item.className = 'project-item border border-gray-200 rounded-lg p-4 sm:p-6 new-item';
            item.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                <div class="min-w-0">
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Project Title</label>
                    <input type="text" name="project_title[]" required class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm">
                    <span class="projectError text-red-500 text-xs mt-1 block hidden"></span>
                </div>
                <div class="min-w-0">
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Project URL</label>
                    <input type="url" name="project_url[]" class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm" placeholder="https://...">
                    <span class="projectError text-red-500 text-xs mt-1 block hidden"></span>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mt-4">
                <div class="min-w-0">
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Start Date</label>
                    <input type="date" name="project_start[]" required class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm">
                    <span class="projectError text-red-500 text-xs mt-1 block hidden"></span>
                </div>
                <div class="min-w-0">
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">End Date</label>
                    <input type="date" name="project_end[]" class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm">
                    <span class="projectError text-red-500 text-xs mt-1 block hidden"></span>
                </div>
            </div>
            <div class="mt-4">
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea name="project_desc[]" rows="3" required class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm resize-none"></textarea>
                <span class="projectError text-red-500 text-xs mt-1 block hidden"></span>
            </div>
            <div class="mt-4 flex justify-end gap-2">
                <button type="button" onclick="submitProject(this)" class="px-3 py-1 bg-green-600 text-white hover:bg-green-700 text-xs sm:text-sm font-medium rounded">
                    Save
                </button>
                <button type="button" onclick="this.closest('.project-item').remove()" class="px-3 py-1 text-red-600 hover:text-red-800 text-xs sm:text-sm font-medium">
                    Delete
                </button>
            </div>
        `;
            list.appendChild(item);
        }

        function submitProject(btn) {
            const item = btn.closest('.project-item');
            const isNew = item.classList.contains('new-item');
            const id = item.dataset.id;

            // Clear previous errors
            item.querySelectorAll('.projectError').forEach(e => {
                e.classList.add('hidden');
                e.textContent = '';
            });
            item.querySelectorAll('input, select, textarea').forEach(el => {
                el.classList.remove('border-red-500');
            });

            const data = {
                project_title: item.querySelector('input[name="project_title[]"]').value,
                project_url: item.querySelector('input[name="project_url[]"]').value,
                project_start: item.querySelector('input[name="project_start[]"]').value,
                project_end: item.querySelector('input[name="project_end[]"]').value,
                project_desc: item.querySelector('textarea[name="project_desc[]"]').value,
            };

            let url;
            let method;

            if (isNew || !id) {
                url = '/student/projects';
                method = 'POST';
            } else {
                url = `/student/projects/${id}`;
                method = 'PUT';
            }

            console.log('Submit Project:', {
                url,
                method,
                data
            });

            fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(data)
                })
                .then(r => r.json())
                .then(d => {
                    console.log('Response:', d);
                    if (d.success) {
                        showToast('✅ ' + d.message, 'success');
                        if (isNew) item.classList.remove('new-item');
                        item.dataset.id = d.project?.id;
                    } else {
                        showToast('❌ ' + (d.message || 'Failed to save'), 'error');

                        if (d.errors) {
                            console.log('Project Errors:', d.errors);
                            displayProjectErrors(item, d.errors);
                        }
                    }
                })
                .catch(e => {
                    console.error('Fetch Error:', e);
                    showToast('❌ Error: ' + e.message, 'error');
                });
        }

        function displayProjectErrors(item, errors) {
            const fieldMap = {
                'project_title': 'input[name="project_title[]"]',
                'project_url': 'input[name="project_url[]"]',
                'project_start': 'input[name="project_start[]"]',
                'project_end': 'input[name="project_end[]"]',
                'project_desc': 'textarea[name="project_desc[]"]'
            };

            Object.keys(errors).forEach(field => {
                const fieldSelector = fieldMap[field];
                if (fieldSelector) {
                    const input = item.querySelector(fieldSelector);
                    if (input) {
                        const errorEl = input.parentElement.querySelector('.projectError');
                        if (errorEl) {
                            errorEl.textContent = errors[field][0];
                            errorEl.classList.remove('hidden');
                        }
                        input.classList.add('border-red-500');

                        input.addEventListener('input', function() {
                            this.classList.remove('border-red-500');
                            const err = this.parentElement.querySelector('.projectError');
                            if (err) err.classList.add('hidden');
                        }, {
                            once: true
                        });
                    }
                }
            });
        }

        function deleteExperience(btn, id) {
            if (!confirm('Delete this experience?')) return;

            fetch(`/student/experiences/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(r => r.json()).then(d => {
                if (d.success) {
                    btn.closest('.experience-item').remove();
                    showToast('✅ Experience deleted!', 'success');
                } else showToast('❌ ' + d.message, 'error');
            });
        }

        function deleteProject(btn, id) {
            if (!confirm('Delete this project?')) return;

            fetch(`/student/projects/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            }).then(r => r.json()).then(d => {
                if (d.success) {
                    btn.closest('.project-item').remove();
                    showToast('✅ Project deleted!', 'success');
                } else showToast('❌ ' + d.message, 'error');
            });
        }

        // Form Submission with Proper Error Handling
        document.getElementById('profileForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;

            clearAllErrors();

            const formData = new FormData(this);

            try {
                const response = await fetch('{{ route('student.profile.update') }}', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: formData
                });

                console.log('Response Status:', response.status);

                const responseText = await response.text();
                console.log('Response Text:', responseText);

                let data;
                try {
                    data = JSON.parse(responseText);
                } catch (e) {
                    console.error('JSON Parse Error:', e);
                    showErrorAlert('❌ Server returned invalid response: ' + responseText.substring(0, 200));
                    submitBtn.disabled = false;
                    return;
                }

                console.log('Parsed Data:', data);

                if (response.ok && data.success) {
                    showToast('✅ ' + data.message, 'success');
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1500);
                } else {
                    showErrorAlert(data.message || 'Failed to update profile');

                    if (data.errors) {
                        console.log('Validation Errors:', data.errors);
                        displayErrors(data.errors);
                    }
                }
            } catch (error) {
                console.error('Fetch Error:', error);
                showErrorAlert('❌ Network error: ' + error.message);
            } finally {
                submitBtn.disabled = false;
            }
        });

        function showErrorAlert(message) {
            const alertContainer = document.createElement('div');
            alertContainer.className = 'mb-4 sm:mb-6 bg-red-50 border-l-4 border-red-400 p-3 sm:p-4 rounded-r';
            alertContainer.id = 'errorAlert';
            alertContainer.innerHTML = `
            <div class="flex gap-3">
                <svg class="w-5 h-5 text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0-6a4 4 0 110 8 4 4 0 010-8zm0-2a6 6 0 11-1.5.15M12.5 3.5a.5.5 0 11-1 0 .5.5 0 011 0z"></path>
                </svg>
                <div class="flex-1 min-w-0">
                    <p class="text-xs sm:text-sm text-red-800 break-words"><strong>Error:</strong> ${message}</p>
                    <button onclick="document.getElementById('errorAlert').remove()" class="text-red-600 hover:text-red-800 text-xs mt-2 underline">Dismiss</button>
                </div>
            </div>
        `;

            document.getElementById('profileForm').parentElement.insertBefore(alertContainer, document.getElementById(
                'profileForm'));

            alertContainer.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }

        function clearAllErrors() {
            const errorAlert = document.getElementById('errorAlert');
            if (errorAlert) {
                errorAlert.remove();
            }

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

                    if (!document.getElementById('errorAlert')) {
                        errorEl.parentElement.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    }
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
    </script>


@endsection
