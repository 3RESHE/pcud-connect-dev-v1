@extends('layouts.alumni')

@section('title', 'Edit My Profile - PCU-DASMA Connect')

@section('content')
<!-- Store routes as data attributes for JavaScript -->
<script>
    window.routes = {
        profileUpdate: '{{ route("alumni.profile.update") }}',
        experiencesStore: '{{ route("alumni.experiences.store") }}',
        projectsStore: '{{ route("alumni.projects.store") }}',
        deleteResume: '{{ route("alumni.profile.delete-resume") }}',
        deleteCertification: '{{ route("alumni.profile.delete-certification") }}',
    };
</script>

<div class="w-full bg-gray-50 min-h-screen py-4 sm:py-6 lg:py-8">
    <div class="w-full max-w-5xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-6 sm:mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="min-w-0">
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 break-words">Edit My Profile</h1>
                    <p class="text-sm sm:text-base text-gray-600 mt-1 break-words">
                        @if ($profile->is_fresh_grad)
                            <span class="text-green-600 font-medium">🎓 Fresh Graduate Profile</span>
                        @else
                            <span class="text-blue-600 font-medium">💼 Experienced Professional Profile</span>
                        @endif
                    </p>
                </div>
                <a href="{{ route('alumni.profile.show') }}"
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
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2 sm:mb-3">Profile Photo</label>
                        <div class="flex flex-col sm:flex-row gap-4 sm:gap-6">
                            <div class="flex-shrink-0">
                                @if ($profile && $profile->profile_photo)
                                    <img src="{{ asset('storage/' . $profile->profile_photo) }}" alt="Profile Photo"
                                        class="w-20 h-20 sm:w-24 sm:h-24 rounded-full object-cover">
                                @else
                                    <div class="w-20 h-20 sm:w-24 sm:h-24 bg-gray-100 rounded-full flex items-center justify-center">
                                        <svg class="w-10 h-10 sm:w-12 sm:h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <label class="inline-block">
                                    <input type="file" name="profile_photo" id="profile_photo" class="hidden" accept="image/*" onchange="previewPhoto(event)">
                                    <span class="inline-block bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-xs sm:text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 cursor-pointer transition whitespace-nowrap">
                                        Change Photo
                                    </span>
                                </label>
                                <p class="text-xs text-gray-500 mt-2 break-words">PNG, JPG up to 2MB</p>
                                <span id="profile_photoError" class="text-red-500 text-xs mt-1 block break-words hidden"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Personal Email -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                        <div class="min-w-0">
                            <label for="personal_email" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2 break-words">
                                Personal Email *
                            </label>
                            <input type="email" id="personal_email" name="personal_email" value="{{ $profile?->personal_email ?? '' }}" required
                                class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                placeholder="your.email@example.com">
                            <span id="personal_emailError" class="text-red-500 text-xs mt-1 block break-words hidden"></span>
                        </div>
                        <div class="min-w-0">
                            <label for="phone" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2 break-words">
                                Phone Number *
                            </label>
                            <input type="tel" id="phone" name="phone" value="{{ $profile?->phone ?? '' }}" required
                                class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                placeholder="+63 9XX-XXXX-XXXX">
                            <span id="phoneError" class="text-red-500 text-xs mt-1 block break-words hidden"></span>
                        </div>
                    </div>

                    <!-- Current Location -->
                    <div class="min-w-0">
                        <label for="current_location" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2 break-words">
                            Current Location *
                        </label>
                        <input type="text" id="current_location" name="current_location" value="{{ $profile?->current_location ?? '' }}" required
                            class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                            placeholder="City, Country">
                        <span id="current_locationError" class="text-red-500 text-xs mt-1 block break-words hidden"></span>
                    </div>
                </div>
            </div>

            <!-- Academic Information -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 break-words">Academic Information</h3>
                    <p class="text-xs sm:text-sm text-gray-600 mt-1 break-words">Your educational background</p>
                </div>
                <div class="px-3 sm:px-4 md:px-6 py-4 sm:py-6 space-y-4 sm:space-y-6">

                    <!-- Degree Program & Graduation Year -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                        <div class="min-w-0">
                            <label for="degree_program" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2 break-words">
                                Degree Program *
                            </label>
                            <input type="text" id="degree_program" name="degree_program" value="{{ $profile?->degree_program ?? '' }}" required
                                class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                placeholder="e.g., Bachelor of Science in Computer Science">
                            <span id="degree_programError" class="text-red-500 text-xs mt-1 block break-words hidden"></span>
                        </div>
                        <div class="min-w-0">
                            <label for="graduation_year" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2 break-words">
                                Graduation Year *
                            </label>
                            <input type="number" id="graduation_year" name="graduation_year" value="{{ $profile?->graduation_year ?? '' }}" required min="1990" max="2099"
                                class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                placeholder="2023">
                            <span id="graduation_yearError" class="text-red-500 text-xs mt-1 block break-words hidden"></span>
                        </div>
                    </div>

                    <!-- GWA & Honors -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                        <div class="min-w-0">
                            <label for="gwa" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2 break-words">
                                GWA (GPA Equivalent)
                            </label>
                            <input type="number" id="gwa" name="gwa" value="{{ $profile?->gwa ?? '' }}" step="0.01" min="0" max="4.00"
                                class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                placeholder="3.75">
                            <span id="gwaError" class="text-red-500 text-xs mt-1 block break-words hidden"></span>
                        </div>
                        <div class="min-w-0">
                            <label for="honors" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2 break-words">
                                Honors
                            </label>
                            <select id="honors" name="honors"
                                class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                <option value="">Select Honors</option>
                                <option value="Cum Laude" @selected($profile?->honors === 'Cum Laude')>Cum Laude</option>
                                <option value="Magna Cum Laude" @selected($profile?->honors === 'Magna Cum Laude')>Magna Cum Laude</option>
                                <option value="Summa Cum Laude" @selected($profile?->honors === 'Summa Cum Laude')>Summa Cum Laude</option>
                            </select>
                            <span id="honorsError" class="text-red-500 text-xs mt-1 block break-words hidden"></span>
                        </div>
                    </div>

                    <!-- Thesis Title -->
                    <div class="min-w-0">
                        <label for="thesis_title" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2 break-words">
                            Thesis / Capstone Project Title
                        </label>
                        <textarea id="thesis_title" name="thesis_title" rows="3"
                            class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm resize-none"
                            placeholder="Your thesis or capstone project title...">{{ $profile?->thesis_title ?? '' }}</textarea>
                        <span id="thesis_titleError" class="text-red-500 text-xs mt-1 block break-words hidden"></span>
                    </div>
                </div>
            </div>

            <!-- Professional Information (HIDDEN FOR FRESH GRADS) -->
            @if (!$profile->is_fresh_grad)
            <div id="professionalSection" class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 break-words">Professional Information</h3>
                    <p class="text-xs sm:text-sm text-gray-600 mt-1 break-words">Your career and employment details</p>
                </div>
                <div class="px-3 sm:px-4 md:px-6 py-4 sm:py-6 space-y-4 sm:space-y-6">

                    <!-- Professional Headline -->
                    <div class="min-w-0" id="headlineSection">
                        <label for="headline" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2 break-words">
                            Professional Headline *
                        </label>
                        <input type="text" id="headline" name="headline" value="{{ $profile?->headline ?? '' }}"
                            class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                            placeholder="e.g., Senior Software Engineer at Google">
                        <span id="headlineError" class="text-red-500 text-xs mt-1 block break-words hidden"></span>
                    </div>

                    <!-- Current Organization & Position -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                        <div class="min-w-0">
                            <label for="current_organization" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2 break-words">
                                Current Organization *
                            </label>
                            <input type="text" id="current_organization" name="current_organization" value="{{ $profile?->current_organization ?? '' }}"
                                class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                placeholder="Company Name">
                            <span id="current_organizationError" class="text-red-500 text-xs mt-1 block break-words hidden"></span>
                        </div>
                        <div class="min-w-0">
                            <label for="current_position" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2 break-words">
                                Current Position *
                            </label>
                            <input type="text" id="current_position" name="current_position" value="{{ $profile?->current_position ?? '' }}"
                                class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                placeholder="Job Title">
                            <span id="current_positionError" class="text-red-500 text-xs mt-1 block break-words hidden"></span>
                        </div>
                    </div>

                    <!-- Industry & Relocation -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                        <div class="min-w-0">
                            <label for="current_industry" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2 break-words">
                                Industry
                            </label>
                            <input type="text" id="current_industry" name="current_industry" value="{{ $profile?->current_industry ?? '' }}"
                                class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                placeholder="e.g., Technology, Finance, Healthcare">
                            <span id="current_industryError" class="text-red-500 text-xs mt-1 block break-words hidden"></span>
                        </div>
                        <div class="min-w-0">
                            <label for="willing_to_relocate" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2 break-words">
                                Willing to Relocate?
                            </label>
                            <select id="willing_to_relocate" name="willing_to_relocate"
                                class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                <option value="">Select Option</option>
                                <option value="1" @selected($profile?->willing_to_relocate === 1)>Yes</option>
                                <option value="0" @selected($profile?->willing_to_relocate === 0)>No</option>
                            </select>
                            <span id="willing_to_relocateError" class="text-red-500 text-xs mt-1 block break-words hidden"></span>
                        </div>
                    </div>

                    <!-- Professional Summary -->
                    <div class="min-w-0">
                        <label for="professional_summary" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2 break-words">
                            Professional Summary *
                        </label>
                        <textarea id="professional_summary" name="professional_summary" rows="5"
                            class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm resize-none"
                            placeholder="Tell us about your career journey, achievements, and professional goals...">{{ $profile?->professional_summary ?? '' }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Min. 20 characters</p>
                        <span id="professional_summaryError" class="text-red-500 text-xs mt-1 block break-words hidden"></span>
                    </div>
                </div>
            </div>
            @endif

            <!-- Resumes Section -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 break-words">📄 Resumes</h3>
                    <p class="text-xs sm:text-sm text-gray-600 mt-1 break-words">Upload your resume(s) - PDF, DOC, DOCX (Max 5MB each)</p>
                </div>
                <div class="px-3 sm:px-4 md:px-6 py-4 sm:py-6 space-y-4">

                    <!-- Drag & Drop Area -->
                    <div id="resumeDropZone" class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:border-blue-500 hover:bg-blue-50 transition">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-12l-3.172-3.172a4 4 0 00-5.656 0L28 12M9 20l3.172-3.172a4 4 0 015.656 0L28 28" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                        <p class="mt-2 text-sm font-medium text-gray-900">Drop your resume here or click to browse</p>
                        <p class="text-xs text-gray-500 mt-1">PDF, DOC, DOCX up to 5MB</p>
                        <input type="file" id="resumeInput" name="resumes[]" class="hidden" accept=".pdf,.doc,.docx" multiple onchange="handleResumeUpload(event)">
                    </div>

                    <!-- Uploaded Resumes List -->
                    <div id="resumesList" class="space-y-2">
                        @if($profile->resumes && is_array($profile->resumes))
                            @foreach($profile->resumes as $index => $resume)
                                <div class="flex items-center justify-between bg-gray-50 p-3 rounded-lg" data-index="{{ $index }}">
                                    <div class="flex items-center gap-2 min-w-0 flex-1">
                                        <svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M8 16.5a1 1 0 11-2 0 1 1 0 012 0zM15 7H4V5h11v2zM15 11H4V9h11v2z"></path>
                                        </svg>
                                        <a href="{{ asset('storage/' . $resume) }}" target="_blank" class="text-xs sm:text-sm text-blue-600 hover:text-blue-800 break-all">
                                            {{ basename($resume) }}
                                        </a>
                                    </div>
                                    <button type="button" onclick="deleteExistingResume('{{ $resume }}', this)" class="text-red-600 hover:text-red-800 text-sm font-medium flex-shrink-0 ml-2">
                                        Delete
                                    </button>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <!-- New Resumes Preview -->
                    <div id="newResumesList" class="space-y-2"></div>
                    <span id="resumesError" class="text-red-500 text-xs mt-1 block break-words hidden"></span>
                </div>
            </div>

            <!-- Certifications Section -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 break-words">🏆 Certifications</h3>
                    <p class="text-xs sm:text-sm text-gray-600 mt-1 break-words">Upload your certification(s) - PDF, JPG, PNG (Max 5MB each)</p>
                </div>
                <div class="px-3 sm:px-4 md:px-6 py-4 sm:py-6 space-y-4">

                    <!-- Drag & Drop Area -->
                    <div id="certificationDropZone" class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center cursor-pointer hover:border-green-500 hover:bg-green-50 transition">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-12l-3.172-3.172a4 4 0 00-5.656 0L28 12M9 20l3.172-3.172a4 4 0 015.656 0L28 28" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                        <p class="mt-2 text-sm font-medium text-gray-900">Drop your certification here or click to browse</p>
                        <p class="text-xs text-gray-500 mt-1">PDF, JPG, PNG up to 5MB</p>
                        <input type="file" id="certificationInput" name="certifications[]" class="hidden" accept=".pdf,.jpg,.jpeg,.png" multiple onchange="handleCertificationUpload(event)">
                    </div>

                    <!-- Uploaded Certifications List -->
                    <div id="certificationsList" class="space-y-2">
                        @if($profile->certifications && is_array($profile->certifications))
                            @foreach($profile->certifications as $index => $cert)
                                <div class="flex items-center justify-between bg-gray-50 p-3 rounded-lg" data-index="{{ $index }}">
                                    <div class="flex items-center gap-2 min-w-0 flex-1">
                                        <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v1h8v-1zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                                        </svg>
                                        <a href="{{ asset('storage/' . $cert) }}" target="_blank" class="text-xs sm:text-sm text-green-600 hover:text-green-800 break-all">
                                            {{ basename($cert) }}
                                        </a>
                                    </div>
                                    <button type="button" onclick="deleteExistingCertification('{{ $cert }}', this)" class="text-red-600 hover:text-red-800 text-sm font-medium flex-shrink-0 ml-2">
                                        Delete
                                    </button>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <!-- New Certifications Preview -->
                    <div id="newCertificationsList" class="space-y-2"></div>
                    <span id="certificationsError" class="text-red-500 text-xs mt-1 block break-words hidden"></span>
                </div>
            </div>

            <!-- Skills & Competencies -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 break-words">Skills & Competencies</h3>
                    <p class="text-xs sm:text-sm text-gray-600 mt-1 break-words">Separate with commas (e.g., Python, Java, Leadership)</p>
                </div>
                <div class="px-3 sm:px-4 md:px-6 py-4 sm:py-6 space-y-4 sm:space-y-6">

                    <!-- Technical Skills -->
                    <div class="min-w-0">
                        <label for="technical_skills" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2 break-words">
                            Technical Skills
                        </label>
                        <textarea id="technical_skills" name="technical_skills" rows="3"
                            class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm resize-none"
                            placeholder="e.g., Python, JavaScript, React, Node.js, MongoDB...">{{ $profile?->technical_skills ?? '' }}</textarea>
                        <span id="technical_skillsError" class="text-red-500 text-xs mt-1 block break-words hidden"></span>
                    </div>

                    <!-- Soft Skills -->
                    <div class="min-w-0">
                        <label for="soft_skills" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2 break-words">
                            Soft Skills
                        </label>
                        <textarea id="soft_skills" name="soft_skills" rows="3"
                            class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm resize-none"
                            placeholder="e.g., Communication, Leadership, Problem Solving, Teamwork...">{{ $profile?->soft_skills ?? '' }}</textarea>
                        <span id="soft_skillsError" class="text-red-500 text-xs mt-1 block break-words hidden"></span>
                    </div>

                    <!-- Languages -->
                    <div class="min-w-0">
                        <label for="languages" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2 break-words">
                            Languages
                        </label>
                        <textarea id="languages" name="languages" rows="3"
                            class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm resize-none"
                            placeholder="e.g., English, Filipino, Mandarin, Spanish...">{{ $profile?->languages ?? '' }}</textarea>
                        <span id="languagesError" class="text-red-500 text-xs mt-1 block break-words hidden"></span>
                    </div>
                </div>
            </div>

            <!-- Social & Professional Links -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 break-words">Social & Professional Links</h3>
                </div>
                <div class="px-3 sm:px-4 md:px-6 py-4 sm:py-6 space-y-4 sm:space-y-6">

                    <!-- LinkedIn URL -->
                    <div class="min-w-0">
                        <label for="linkedin_url" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2 break-words">
                            LinkedIn Profile
                        </label>
                        <input type="url" id="linkedin_url" name="linkedin_url" value="{{ $profile?->linkedin_url ?? '' }}"
                            class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                            placeholder="https://linkedin.com/in/username">
                        <span id="linkedin_urlError" class="text-red-500 text-xs mt-1 block break-words hidden"></span>
                    </div>

                    <!-- GitHub URL -->
                    <div class="min-w-0">
                        <label for="github_url" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2 break-words">
                            GitHub Profile
                        </label>
                        <input type="url" id="github_url" name="github_url" value="{{ $profile?->github_url ?? '' }}"
                            class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                            placeholder="https://github.com/username">
                        <span id="github_urlError" class="text-red-500 text-xs mt-1 block break-words hidden"></span>
                    </div>

                    <!-- Portfolio URL -->
                    <div class="min-w-0">
                        <label for="portfolio_url" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2 break-words">
                            Portfolio / Website
                        </label>
                        <input type="url" id="portfolio_url" name="portfolio_url" value="{{ $profile?->portfolio_url ?? '' }}"
                            class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                            placeholder="https://yourportfolio.com">
                        <span id="portfolio_urlError" class="text-red-500 text-xs mt-1 block break-words hidden"></span>
                    </div>
                </div>
            </div>

            <!-- Experiences Section -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 border-b border-gray-200 bg-gray-50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="min-w-0">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 break-words">Work Experience</h3>
                        <p class="text-xs sm:text-sm text-gray-600 mt-1 break-words">Your career history</p>
                    </div>
                    <button type="button" onclick="addExperience()"
                        class="px-3 sm:px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-xs sm:text-sm font-medium whitespace-nowrap">
                        + Add Experience
                    </button>
                </div>
                <div class="px-3 sm:px-4 md:px-6 py-4 sm:py-6">
                    <div id="experiencesList" class="space-y-4 sm:space-y-6">
                        @forelse ($experiences as $exp)
                            <div class="experience-item border border-gray-200 rounded-lg p-4 sm:p-6" data-id="{{ $exp->id }}">
                                <input type="hidden" name="experience_id[]" value="{{ $exp->id }}">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                                    <div class="min-w-0">
                                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Position</label>
                                        <input type="text" name="experience_role[]" value="{{ $exp->role_position }}" required
                                            class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm">
                                        <span class="experienceError text-red-500 text-xs mt-1 block hidden"></span>
                                    </div>
                                    <div class="min-w-0">
                                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Organization</label>
                                        <input type="text" name="experience_org[]" value="{{ $exp->organization }}" required
                                            class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm">
                                        <span class="experienceError text-red-500 text-xs mt-1 block hidden"></span>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6 mt-4">
                                    <div class="min-w-0">
                                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Start Date</label>
                                        <input type="date" name="experience_start[]" value="{{ $exp->start_date?->format('Y-m-d') }}" required
                                            class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm">
                                        <span class="experienceError text-red-500 text-xs mt-1 block hidden"></span>
                                    </div>
                                    <div class="min-w-0">
                                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">End Date</label>
                                        <input type="date" name="experience_end[]" value="{{ $exp->end_date?->format('Y-m-d') }}"
                                            class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm">
                                        <span class="experienceError text-red-500 text-xs mt-1 block hidden"></span>
                                    </div>
                                    <div class="min-w-0">
                                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Location</label>
                                        <input type="text" name="experience_location[]" value="{{ $exp->location }}"
                                            class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm"
                                            placeholder="City, State/Country">
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mt-4">
                                    <div class="min-w-0">
                                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Type</label>
                                        <select name="experience_type[]" required
                                            class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm">
                                            <option value="full_time" @selected($exp->experience_type === 'full_time')>Full-time</option>
                                            <option value="part_time" @selected($exp->experience_type === 'part_time')>Part-time</option>
                                            <option value="internship" @selected($exp->experience_type === 'internship')>Internship</option>
                                            <option value="freelance" @selected($exp->experience_type === 'freelance')>Freelance</option>
                                            <option value="volunteer" @selected($exp->experience_type === 'volunteer')>Volunteer</option>
                                        </select>
                                        <span class="experienceError text-red-500 text-xs mt-1 block hidden"></span>
                                    </div>
                                    <div class="min-w-0">
                                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                                            <input type="checkbox" name="experience_current[]" value="{{ $exp->id }}" @checked($exp->is_current) class="mr-2">
                                            Currently Working Here?
                                        </label>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Description</label>
                                    <textarea name="experience_desc[]" rows="3" required
                                        class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm resize-none">{{ $exp->description }}</textarea>
                                    <span class="experienceError text-red-500 text-xs mt-1 block hidden"></span>
                                </div>
                                <div class="mt-4 flex justify-end gap-2">
                                    <button type="button" onclick="submitExperience(this)" class="px-3 py-1 bg-green-600 text-white hover:bg-green-700 text-xs sm:text-sm font-medium rounded">
                                        Update
                                    </button>
                                    <button type="button" onclick="deleteExperience(this, {{ $exp->id }})" class="px-3 py-1 text-red-600 hover:text-red-800 text-xs sm:text-sm font-medium">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No work experiences added yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Projects Section -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 border-b border-gray-200 bg-gray-50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div class="min-w-0">
                        <h3 class="text-base sm:text-lg font-semibold text-gray-900 break-words">Projects & Portfolio</h3>
                        <p class="text-xs sm:text-sm text-gray-600 mt-1 break-words">Showcase your work</p>
                    </div>
                    <button type="button" onclick="addProject()"
                        class="px-3 sm:px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-xs sm:text-sm font-medium whitespace-nowrap">
                        + Add Project
                    </button>
                </div>
                <div class="px-3 sm:px-4 md:px-6 py-4 sm:py-6">
                    <div id="projectsList" class="space-y-4 sm:space-y-6">
                        @forelse ($projects as $proj)
                            <div class="project-item border border-gray-200 rounded-lg p-4 sm:p-6" data-id="{{ $proj->id }}">
                                <input type="hidden" name="project_id[]" value="{{ $proj->id }}">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                                    <div class="min-w-0">
                                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Project Title</label>
                                        <input type="text" name="project_title[]" value="{{ $proj->title }}" required
                                            class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm">
                                        <span class="projectError text-red-500 text-xs mt-1 block hidden"></span>
                                    </div>
                                    <div class="min-w-0">
                                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Project URL</label>
                                        <input type="url" name="project_url[]" value="{{ $proj->url }}"
                                            class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm"
                                            placeholder="https://...">
                                        <span class="projectError text-red-500 text-xs mt-1 block hidden"></span>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 mt-4">
                                    <div class="min-w-0">
                                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Start Date</label>
                                        <input type="date" name="project_start[]" value="{{ $proj->start_date?->format('Y-m-d') }}" required
                                            class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm">
                                        <span class="projectError text-red-500 text-xs mt-1 block hidden"></span>
                                    </div>
                                    <div class="min-w-0">
                                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">End Date</label>
                                        <input type="date" name="project_end[]" value="{{ $proj->end_date?->format('Y-m-d') }}"
                                            class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm">
                                        <span class="projectError text-red-500 text-xs mt-1 block hidden"></span>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Description</label>
                                    <textarea name="project_desc[]" rows="3" required
                                        class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg text-sm resize-none">{{ $proj->description }}</textarea>
                                    <span class="projectError text-red-500 text-xs mt-1 block hidden"></span>
                                </div>
                                <div class="mt-4 flex justify-end gap-2">
                                    <button type="button" onclick="submitProject(this)" class="px-3 py-1 bg-green-600 text-white hover:bg-green-700 text-xs sm:text-sm font-medium rounded">
                                        Update
                                    </button>
                                    <button type="button" onclick="deleteProject(this, {{ $proj->id }})" class="px-3 py-1 text-red-600 hover:text-red-800 text-xs sm:text-sm font-medium">
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

            <!-- Form Actions -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="px-3 sm:px-4 md:px-6 py-4 sm:py-6 flex flex-col sm:flex-row gap-3 justify-end">
                    <a href="{{ route('alumni.profile.show') }}"
                        class="px-4 sm:px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition text-sm sm:text-base whitespace-nowrap text-center">
                        Cancel
                    </a>
                    <button type="submit" id="submitBtn"
                        class="px-4 sm:px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm sm:text-base whitespace-nowrap font-medium">
                        Save Changes
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>

<script src="{{ asset('js/alumni-profile.js') }}"></script>
@endsection
