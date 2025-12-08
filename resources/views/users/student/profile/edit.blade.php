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
            <form id="profileForm" class="space-y-4 sm:space-y-6 md:space-y-8" enctype="multipart/form-data">
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
                                            accept="image/*">
                                        <span
                                            class="inline-block bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-xs sm:text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 cursor-pointer transition whitespace-nowrap">
                                            Change Photo
                                        </span>
                                    </label>
                                    <p class="text-xs text-gray-500 mt-2 break-words">PNG, JPG up to 2MB</p>
                                </div>
                            </div>
                        </div>

                        <!-- Headline & Contact -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                            <div class="min-w-0">
                                <label for="headline"
                                    class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2 break-words">
                                    Brief Summary
                                </label>
                                <input type="text" id="headline" name="headline" value="{{ $profile?->headline ?? '' }}"
                                    class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                    placeholder="e.g., BSIT Student | Full-Stack Developer">
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
                            </div>
                        </div>

                        <!-- Phone & DOB -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                            <div class="min-w-0">
                                <label for="phone"
                                    class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2 break-words">
                                    Phone Number
                                </label>
                                <input type="tel" id="phone" name="phone" value="{{ $profile?->phone ?? '' }}"
                                    class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                    placeholder="+63 9XX-XXXX-XXXX">
                            </div>
                            <div class="min-w-0">
                                <label for="date_of_birth"
                                    class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2 break-words">
                                    Date of Birth
                                </label>
                                <input type="date" id="date_of_birth" name="date_of_birth"
                                    value="{{ $profile?->date_of_birth?->format('Y-m-d') ?? '' }}"
                                    class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                            </div>
                        </div>

                        <!-- Gender & Emergency Contact -->
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
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="min-w-0">
                            <label for="address"
                                class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2 break-words">
                                Address
                            </label>
                            <textarea id="address" name="address" rows="3"
                                class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm resize-none"
                                placeholder="Your complete address...">{{ $profile?->address ?? '' }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Social & Professional Links -->
                <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                    <div class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 break-words">Social & Professional
                            Links</h3>
                    </div>
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
                        </div>
                    </div>
                </div>

                <!-- Skills & Competencies -->
                <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                    <div class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 break-words">Skills & Competencies</h3>
                    </div>
                    <div class="px-3 sm:px-4 md:px-6 py-4 sm:py-6 space-y-4 sm:space-y-6">
                        <div class="min-w-0">
                            <label for="technical_skills"
                                class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2 break-words">
                                Technical Skills
                            </label>
                            <textarea id="technical_skills" name="technical_skills" rows="3"
                                class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm resize-none"
                                placeholder="e.g., HTML, CSS, JavaScript, Python, Java...">{{ $profile?->technical_skills ?? '' }}</textarea>
                        </div>
                        <div class="min-w-0">
                            <label for="soft_skills"
                                class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2 break-words">
                                Soft Skills
                            </label>
                            <textarea id="soft_skills" name="soft_skills" rows="3"
                                class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm resize-none"
                                placeholder="e.g., Communication, Leadership, Problem Solving...">{{ $profile?->soft_skills ?? '' }}</textarea>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                            <div class="min-w-0">
                                <label for="languages"
                                    class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2 break-words">
                                    Languages
                                </label>
                                <textarea id="languages" name="languages" rows="3"
                                    class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm resize-none"
                                    placeholder="e.g., English, Filipino, Mandarin...">{{ $profile?->languages ?? '' }}</textarea>
                            </div>
                            <div class="min-w-0">
                                <label for="hobbies"
                                    class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2 break-words">
                                    Hobbies & Interests
                                </label>
                                <textarea id="hobbies" name="hobbies" rows="3"
                                    class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm resize-none"
                                    placeholder="e.g., Gaming, Reading, Photography...">{{ $profile?->hobbies ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Resumes Upload Section -->
                <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                    <div class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 break-words">📄 Resumes</h3>
                        <p class="text-xs sm:text-sm text-gray-600 mt-1 break-words">Upload multiple resume versions</p>
                    </div>
                    <div class="px-3 sm:px-4 md:px-6 py-4 sm:py-6 space-y-4">
                        <div>
                            <label for="resumes" class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Upload
                                Resumes</label>
                            <input type="file" id="resumes" name="resumes[]" multiple accept=".pdf,.doc,.docx"
                                class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm">
                            <p class="text-xs text-gray-500 mt-2">PDF, DOC, DOCX files only. Max 5MB each.</p>
                        </div>
                        @if ($profile && $profile->resumes && is_array($profile->resumes) && count($profile->resumes) > 0)
                            <div class="mt-4">
                                <p class="text-xs sm:text-sm font-medium text-gray-700 mb-3">Uploaded Resumes:</p>
                                <div class="space-y-2">
                                    @foreach ($profile->resumes as $resume)
                                        <div
                                            class="flex items-center gap-3 p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition group">
                                            <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path d="M8 16.5a1 1 0 11-2 0 1 1 0 012 0zM15 7H4V5h11v2zM15 11H4V9h11v2z">
                                                </path>
                                            </svg>
                                            <span
                                                class="text-xs sm:text-sm text-blue-600 font-medium break-all flex-1">{{ basename($resume) }}</span>
                                            <button type="button" onclick="deleteResume('{{ $resume }}')"
                                                class="text-red-500 hover:text-red-700 opacity-0 group-hover:opacity-100 transition">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Certifications Upload Section -->
                <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                    <div class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 break-words">🏆 Certifications</h3>
                        <p class="text-xs sm:text-sm text-gray-600 mt-1 break-words">Upload certification documents or
                            images</p>
                    </div>
                    <div class="px-3 sm:px-4 md:px-6 py-4 sm:py-6 space-y-4">
                        <div>
                            <label for="certifications"
                                class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Upload
                                Certifications</label>
                            <input type="file" id="certifications" name="certifications[]" multiple
                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.gif"
                                class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm">
                            <p class="text-xs text-gray-500 mt-2">PDF, DOC, DOCX, JPG, PNG, GIF files only. Max 5MB each.
                            </p>
                        </div>
                        @if ($profile && $profile->certifications && is_array($profile->certifications) && count($profile->certifications) > 0)
                            <div class="mt-4">
                                <p class="text-xs sm:text-sm font-medium text-gray-700 mb-3">Uploaded Certifications:</p>
                                <div class="space-y-2">
                                    @foreach ($profile->certifications as $cert)
                                        <div
                                            class="flex items-center gap-3 p-3 bg-green-50 rounded-lg hover:bg-green-100 transition group">
                                            <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="currentColor"
                                                viewBox="0 0 20 20">
                                                <path
                                                    d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v1h8v-1zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z">
                                                </path>
                                            </svg>
                                            <span
                                                class="text-xs sm:text-sm text-green-600 font-medium break-all flex-1">{{ basename($cert) }}</span>
                                            <button type="button" onclick="deleteCertification('{{ $cert }}')"
                                                class="text-red-500 hover:text-red-700 opacity-0 group-hover:opacity-100 transition">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
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
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                                        <div class="min-w-0">
                                            <label
                                                class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Role/Position</label>
                                            <input type="text"
                                                class="experience_role w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm"
                                                value="{{ $exp->role_position }}" required>
                                        </div>
                                        <div class="min-w-0">
                                            <label
                                                class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Organization</label>
                                            <input type="text"
                                                class="experience_org w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm"
                                                value="{{ $exp->organization }}" required>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6 mt-4">
                                        <div class="min-w-0">
                                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Start
                                                Date</label>
                                            <input type="date"
                                                class="experience_start w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm"
                                                value="{{ $exp->start_date?->format('Y-m-d') }}" required>
                                        </div>
                                        <div class="min-w-0">
                                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">End
                                                Date</label>
                                            <input type="date"
                                                class="experience_end w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm"
                                                value="{{ $exp->end_date?->format('Y-m-d') }}">
                                        </div>
                                        <div class="min-w-0">
                                            <label
                                                class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Type</label>
                                            <select
                                                class="experience_type w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm"
                                                required>
                                                <option value="">Select Type</option>
                                                <option value="internship" @selected($exp->experience_type === 'internship')>Internship</option>
                                                <option value="part_time" @selected($exp->experience_type === 'part_time')>Part-time</option>
                                                <option value="volunteer" @selected($exp->experience_type === 'volunteer')>Volunteer</option>
                                                <option value="full_time" @selected($exp->experience_type === 'full_time')>Full-time</option>
                                                <option value="freelance" @selected($exp->experience_type === 'freelance')>Freelance</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <label
                                            class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Description</label>
                                        <textarea class="experience_desc w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm resize-none"
                                            rows="3" required>{{ $exp->description }}</textarea>
                                    </div>
                                    <div class="mt-4 flex justify-end gap-2">
                                        <button type="button" onclick="submitExperience(this)"
                                            class="px-3 py-1 bg-green-600 text-white hover:bg-green-700 text-xs sm:text-sm font-medium rounded">Update</button>
                                        <button type="button" onclick="deleteExperience(this, {{ $exp->id }})"
                                            class="px-3 py-1 text-red-600 hover:text-red-800 text-xs sm:text-sm font-medium">Delete</button>
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
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                                        <div class="min-w-0">
                                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Project
                                                Title</label>
                                            <input type="text"
                                                class="project_title w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm"
                                                value="{{ $proj->title }}" required>
                                        </div>
                                        <div class="min-w-0">
                                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Project
                                                URL</label>
                                            <input type="url"
                                                class="project_url w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm"
                                                value="{{ $proj->url }}" placeholder="https://github.com/...">
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mt-4">
                                        <div class="min-w-0">
                                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Start
                                                Date</label>
                                            <input type="date"
                                                class="project_start w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm"
                                                value="{{ $proj->start_date?->format('Y-m-d') }}" required>
                                        </div>
                                        <div class="min-w-0">
                                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">End
                                                Date</label>
                                            <input type="date"
                                                class="project_end w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm"
                                                value="{{ $proj->end_date?->format('Y-m-d') }}">
                                        </div>
                                    </div>
                                    <div class="mt-4">
                                        <label
                                            class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Description</label>
                                        <textarea class="project_desc w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm resize-none"
                                            rows="3" required>{{ $proj->description }}</textarea>
                                    </div>
                                    <div class="mt-4 flex justify-end gap-2">
                                        <button type="button" onclick="submitProject(this)"
                                            class="px-3 py-1 bg-green-600 text-white hover:bg-green-700 text-xs sm:text-sm font-medium rounded">Update</button>
                                        <button type="button" onclick="deleteProject(this, {{ $proj->id }})"
                                            class="px-3 py-1 text-red-600 hover:text-red-800 text-xs sm:text-sm font-medium">Delete</button>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">No projects added yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex gap-3 justify-end">
                    <a href="{{ route('student.profile.show') }}"
                        class="px-4 sm:px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm sm:text-base font-medium">
                        Cancel
                    </a>
                    <button type="submit" id="submitBtn"
                        class="px-4 sm:px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm sm:text-base font-medium">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // ===== PROFILE FORM SUBMISSION =====
        document.getElementById('profileForm')?.addEventListener('submit', async (e) => {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;

            try {
                const response = await fetch('{{ route('student.profile.update') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
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
                    showToast('❌ ' + (data.message || 'Failed'), 'error');
                }
            } catch (error) {
                showToast('❌ Error: ' + error.message, 'error');
            } finally {
                submitBtn.disabled = false;
            }
        });

        // ===== TOAST NOTIFICATION =====
        function showToast(message, type = 'info') {
            const container = document.getElementById('toastContainer');
            const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
            const toast = document.createElement('div');
            toast.className = `${bgColor} text-white px-4 py-3 rounded-lg shadow-lg mb-2 text-sm`;
            toast.textContent = message;
            if (container) {
                container.appendChild(toast);
                setTimeout(() => toast.remove(), 4000);
            }
        }

        // ===== DELETE RESUME =====
        function deleteResume(filePath) {
            if (!confirm('Delete this resume?')) return;
            fetch('{{ route('student.profile.delete-resume') }}', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    file: filePath
                })
            }).then(r => r.json()).then(d => {
                if (d.success) {
                    showToast('✅ Resume deleted!', 'success');
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showToast('❌ ' + d.message, 'error');
                }
            }).catch(e => showToast('❌ Error: ' + e.message, 'error'));
        }

        // ===== DELETE CERTIFICATION =====
        function deleteCertification(filePath) {
            if (!confirm('Delete this certification?')) return;
            fetch('{{ route('student.profile.delete-certification') }}', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    file: filePath
                })
            }).then(r => r.json()).then(d => {
                if (d.success) {
                    showToast('✅ Certification deleted!', 'success');
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showToast('❌ ' + d.message, 'error');
                }
            }).catch(e => showToast('❌ Error: ' + e.message, 'error'));
        }

        // ===== ADD EXPERIENCE =====
        function addExperience() {
            const list = document.getElementById('experiencesList');
            const template = `
                <div class="experience-item border border-gray-200 rounded-lg p-4 sm:p-6" data-id="new">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                        <div class="min-w-0">
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Role/Position</label>
                            <input type="text" class="experience_role w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm" required>
                        </div>
                        <div class="min-w-0">
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Organization</label>
                            <input type="text" class="experience_org w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm" required>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6 mt-4">
                        <div class="min-w-0">
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Start Date</label>
                            <input type="date" class="experience_start w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm" required>
                        </div>
                        <div class="min-w-0">
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">End Date</label>
                            <input type="date" class="experience_end w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                        <div class="min-w-0">
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Type</label>
                            <select class="experience_type w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm" required>
                                <option value="">Select Type</option>
                                <option value="internship">Internship</option>
                                <option value="part_time">Part-time</option>
                                <option value="volunteer">Volunteer</option>
                                <option value="full_time">Full-time</option>
                                <option value="freelance">Freelance</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea class="experience_desc w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm resize-none" rows="3" required></textarea>
                    </div>
                    <div class="mt-4 flex justify-end gap-2">
                        <button type="button" onclick="submitExperience(this)" class="px-3 py-1 bg-green-600 text-white hover:bg-green-700 text-xs sm:text-sm font-medium rounded">Save</button>
                        <button type="button" onclick="this.closest('.experience-item').remove()" class="px-3 py-1 text-red-600 hover:text-red-800 text-xs sm:text-sm font-medium">Cancel</button>
                    </div>
                </div>
            `;
            list.insertAdjacentHTML('beforeend', template);
        }

        // ===== SUBMIT EXPERIENCE =====
        function submitExperience(btn) {
            const item = btn.closest('.experience-item');
            const id = item.dataset.id;
            const role = item.querySelector('.experience_role').value;
            const org = item.querySelector('.experience_org').value;
            const start = item.querySelector('.experience_start').value;
            const end = item.querySelector('.experience_end').value;
            const type = item.querySelector('.experience_type').value;
            const desc = item.querySelector('.experience_desc').value;

            if (!role || !org || !start || !type || !desc) {
                showToast('Please fill all required fields', 'error');
                return;
            }

            const url = id === 'new' ? '{{ route('student.experiences.store') }}' :
                `{{ route('student.experiences.update', ':id') }}`.replace(':id', id);
            const method = id === 'new' ? 'POST' : 'PUT';

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    experience_role: role,
                    experience_org: org,
                    experience_start: start,
                    experience_end: end || null,
                    experience_type: type,
                    experience_desc: desc
                })
            }).then(r => r.json()).then(d => {
                if (d.success) {
                    showToast('✅ Experience saved!', 'success');
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showToast('❌ Error: ' + (d.message || 'Failed'), 'error');
                    if (d.errors) Object.keys(d.errors).forEach(key => showToast('  ' + d.errors[key][0], 'error'));
                }
            }).catch(e => showToast('❌ Error: ' + e.message, 'error'));
        }

        // ===== DELETE EXPERIENCE =====
        function deleteExperience(btn, id) {
            if (!confirm('Delete this experience?')) return;
            fetch(`{{ route('student.experiences.destroy', ':id') }}`.replace(':id', id), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            }).then(r => r.json()).then(d => {
                if (d.success) {
                    showToast('✅ Experience deleted!', 'success');
                    btn.closest('.experience-item').remove();
                } else {
                    showToast('❌ Error deleting', 'error');
                }
            }).catch(e => showToast('❌ Error: ' + e.message, 'error'));
        }

        // ===== ADD PROJECT =====
        function addProject() {
            const list = document.getElementById('projectsList');
            const template = `
                <div class="project-item border border-gray-200 rounded-lg p-4 sm:p-6" data-id="new">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                        <div class="min-w-0">
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Project Title</label>
                            <input type="text" class="project_title w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm" required>
                        </div>
                        <div class="min-w-0">
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Project URL</label>
                            <input type="url" class="project_url w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm" placeholder="https://github.com/...">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mt-4">
                        <div class="min-w-0">
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Start Date</label>
                            <input type="date" class="project_start w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm" required>
                        </div>
                        <div class="min-w-0">
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">End Date</label>
                            <input type="date" class="project_end w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm">
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea class="project_desc w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm resize-none" rows="3" required></textarea>
                    </div>
                    <div class="mt-4 flex justify-end gap-2">
                        <button type="button" onclick="submitProject(this)" class="px-3 py-1 bg-green-600 text-white hover:bg-green-700 text-xs sm:text-sm font-medium rounded">Save</button>
                        <button type="button" onclick="this.closest('.project-item').remove()" class="px-3 py-1 text-red-600 hover:text-red-800 text-xs sm:text-sm font-medium">Cancel</button>
                    </div>
                </div>
            `;
            list.insertAdjacentHTML('beforeend', template);
        }

        // ===== SUBMIT PROJECT =====
        function submitProject(btn) {
            const item = btn.closest('.project-item');
            const id = item.dataset.id;
            const title = item.querySelector('.project_title').value;
            const url = item.querySelector('.project_url').value;
            const start = item.querySelector('.project_start').value;
            const end = item.querySelector('.project_end').value;
            const desc = item.querySelector('.project_desc').value;

            if (!title || !start || !desc) {
                showToast('Please fill all required fields', 'error');
                return;
            }

            const endpoint = id === 'new' ? '{{ route('student.projects.store') }}' :
                `{{ route('student.projects.update', ':id') }}`.replace(':id', id);
            const method = id === 'new' ? 'POST' : 'PUT';

            fetch(endpoint, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    project_title: title,
                    project_url: url || null,
                    project_start: start,
                    project_end: end || null,
                    project_desc: desc
                })
            }).then(r => r.json()).then(d => {
                if (d.success) {
                    showToast('✅ Project saved!', 'success');
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showToast('❌ Error: ' + (d.message || 'Failed'), 'error');
                    if (d.errors) Object.keys(d.errors).forEach(key => showToast('  ' + d.errors[key][0], 'error'));
                }
            }).catch(e => showToast('❌ Error: ' + e.message, 'error'));
        }

        // ===== DELETE PROJECT =====
        function deleteProject(btn, id) {
            if (!confirm('Delete this project?')) return;
            fetch(`{{ route('student.projects.destroy', ':id') }}`.replace(':id', id), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            }).then(r => r.json()).then(d => {
                if (d.success) {
                    showToast('✅ Project deleted!', 'success');
                    btn.closest('.project-item').remove();
                } else {
                    showToast('❌ Error deleting', 'error');
                }
            }).catch(e => showToast('❌ Error: ' + e.message, 'error'));
        }
    </script>
@endsection
