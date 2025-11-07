@extends('layouts.alumni')

@section('title', 'My Profile - PCU-DASMA Connect')

@section('content')
    <!-- Main Content -->
    <div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">My Profile</h1>
            <p class="text-gray-600">
                Keep your professional profile updated to attract opportunities
            </p>
        </div>

        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Profile Form -->
        <form method="POST" action="{{ route('alumni.profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="space-y-8">
                <!-- Personal Information -->
                <div class="bg-white shadow-sm rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">
                            Personal Information
                        </h3>
                        <p class="text-sm text-gray-600">
                            Basic information about yourself
                        </p>
                    </div>
                    <div class="px-6 py-6">
                        <div class="grid grid-cols-1 gap-6">
                            <!-- Profile Photo -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Profile Photo</label>
                                <div class="flex items-center space-x-6">
                                    <div
                                        class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center overflow-hidden">
                                        @if ($user->profile_photo)
                                            <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Profile"
                                                class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                </path>
                                            </svg>
                                        @endif
                                    </div>
                                    <div>
                                        <input type="file" name="profile_photo" id="profile_photo" class="hidden"
                                            accept="image/*">
                                        <button type="button" onclick="document.getElementById('profile_photo').click()"
                                            class="bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary">
                                            Change Photo
                                        </button>
                                        <p class="text-xs text-gray-500 mt-1">PNG, JPG up to 2MB</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Headline -->
                            <div>
                                <label for="headline" class="block text-sm font-medium text-gray-700 mb-1">
                                    Professional Headline <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="headline" name="headline"
                                    value="{{ old('headline', $user->headline) }}" required
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                                    placeholder="e.g., Recent Graduate Seeking Software Development Opportunities" />
                            </div>

                            <!-- Full Name -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">
                                        First Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="first_name" name="first_name"
                                        value="{{ old('first_name', $user->first_name) }}" required
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" />
                                </div>

                                <div>
                                    <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">
                                        Last Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="last_name" name="last_name"
                                        value="{{ old('last_name', $user->last_name) }}" required
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" />
                                </div>

                                <div>
                                    <label for="middle_name" class="block text-sm font-medium text-gray-700 mb-1">
                                        Middle Name
                                    </label>
                                    <input type="text" id="middle_name" name="middle_name"
                                        value="{{ old('middle_name', $user->middle_name) }}"
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" />
                                </div>

                                <div>
                                    <label for="suffix" class="block text-sm font-medium text-gray-700 mb-1">
                                        Name Suffix
                                    </label>
                                    <input type="text" id="suffix" name="suffix"
                                        value="{{ old('suffix', $user->suffix) }}"
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                                        placeholder="e.g., Jr., Sr., III" />
                                </div>
                            </div>
                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                    Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                                    required
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" />
                            </div>

                            <!-- Phone Number -->
                            <div>
                                <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-1">
                                    Phone Number
                                </label>
                                <input type="tel" id="phone_number" name="phone_number"
                                    value="{{ old('phone_number', $user->phone_number) }}"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary" />
                            </div>

                            <!-- Bio/About -->
                            <div>
                                <label for="bio" class="block text-sm font-medium text-gray-700 mb-1">
                                    About You
                                </label>
                                <textarea id="bio" name="bio" rows="4"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                                    placeholder="Tell us about yourself...">{{ old('bio', $user->bio) }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">Maximum 500 characters</p>
                            </div>

                            <!-- Location -->
                            <div>
                                <label for="location" class="block text-sm font-medium text-gray-700 mb-1">
                                    Location
                                </label>
                                <input type="text" id="location" name="location"
                                    value="{{ old('location', $user->location) }}"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                                    placeholder="e.g., Manila, Philippines" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Education Information -->
                <div class="bg-white shadow-sm rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">
                            Education Information
                        </h3>
                        <p class="text-sm text-gray-600">
                            Your academic background
                        </p>
                    </div>
                    <div class="px-6 py-6">
                        <div class="grid grid-cols-1 gap-6">
                            <!-- Degree -->
                            <div>
                                <label for="degree" class="block text-sm font-medium text-gray-700 mb-1">
                                    Degree <span class="text-red-500">*</span>
                                </label>
                                <select id="degree" name="degree" required
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                                    <option value="">Select a degree</option>
                                    <option value="bachelor"
                                        {{ old('degree', $user->degree) == 'bachelor' ? 'selected' : '' }}>Bachelor's
                                        Degree</option>
                                    <option value="master"
                                        {{ old('degree', $user->degree) == 'master' ? 'selected' : '' }}>Master's Degree
                                    </option>
                                    <option value="associate"
                                        {{ old('degree', $user->degree) == 'associate' ? 'selected' : '' }}>Associate's
                                        Degree</option>
                                    <option value="certificate"
                                        {{ old('degree', $user->degree) == 'certificate' ? 'selected' : '' }}>Certificate
                                    </option>
                                    <option value="other"
                                        {{ old('degree', $user->degree) == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>

                            <!-- Field of Study -->
                            <div>
                                <label for="field_of_study" class="block text-sm font-medium text-gray-700 mb-1">
                                    Field of Study <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="field_of_study" name="field_of_study"
                                    value="{{ old('field_of_study', $user->field_of_study) }}" required
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                                    placeholder="e.g., Information Technology" />
                            </div>

                            <!-- Graduation Year -->
                            <div>
                                <label for="graduation_year" class="block text-sm font-medium text-gray-700 mb-1">
                                    Graduation Year <span class="text-red-500">*</span>
                                </label>
                                <select id="graduation_year" name="graduation_year" required
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                                    <option value="">Select year</option>
                                    @for ($year = now()->year; $year >= now()->year - 60; $year--)
                                        <option value="{{ $year }}"
                                            {{ old('graduation_year', $user->graduation_year) == $year ? 'selected' : '' }}>
                                            {{ $year }}</option>
                                    @endfor
                                </select>
                            </div>

                            <!-- Honors -->
                            <div>
                                <label for="honors" class="block text-sm font-medium text-gray-700 mb-1">
                                    Honors & Awards
                                </label>
                                <textarea id="honors" name="honors" rows="3"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                                    placeholder="e.g., Dean's List, Scholarship recipient, etc.">{{ old('honors', $user->honors) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Employment Information -->
                <div class="bg-white shadow-sm rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">
                            Employment Information
                        </h3>
                        <p class="text-sm text-gray-600">
                            Your professional background
                        </p>
                    </div>
                    <div class="px-6 py-6">
                        <div class="grid grid-cols-1 gap-6">
                            <!-- Current Position -->
                            <div>
                                <label for="current_position" class="block text-sm font-medium text-gray-700 mb-1">
                                    Current Position
                                </label>
                                <input type="text" id="current_position" name="current_position"
                                    value="{{ old('current_position', $user->current_position) }}"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                                    placeholder="e.g., Senior Software Engineer" />
                            </div>

                            <!-- Company -->
                            <div>
                                <label for="company" class="block text-sm font-medium text-gray-700 mb-1">
                                    Company
                                </label>
                                <input type="text" id="company" name="company"
                                    value="{{ old('company', $user->company) }}"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                                    placeholder="e.g., Tech Innovation Inc." />
                            </div>

                            <!-- Industry -->
                            <div>
                                <label for="industry" class="block text-sm font-medium text-gray-700 mb-1">
                                    Industry
                                </label>
                                <select id="industry" name="industry"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                                    <option value="">Select industry</option>
                                    <option value="technology"
                                        {{ old('industry', $user->industry) == 'technology' ? 'selected' : '' }}>Technology
                                    </option>
                                    <option value="finance"
                                        {{ old('industry', $user->industry) == 'finance' ? 'selected' : '' }}>Finance
                                    </option>
                                    <option value="healthcare"
                                        {{ old('industry', $user->industry) == 'healthcare' ? 'selected' : '' }}>Healthcare
                                    </option>
                                    <option value="education"
                                        {{ old('industry', $user->industry) == 'education' ? 'selected' : '' }}>Education
                                    </option>
                                    <option value="retail"
                                        {{ old('industry', $user->industry) == 'retail' ? 'selected' : '' }}>Retail
                                    </option>
                                    <option value="manufacturing"
                                        {{ old('industry', $user->industry) == 'manufacturing' ? 'selected' : '' }}>
                                        Manufacturing</option>
                                    <option value="other"
                                        {{ old('industry', $user->industry) == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>

                            <!-- Skills -->
                            <div>
                                <label for="skills" class="block text-sm font-medium text-gray-700 mb-1">
                                    Skills
                                </label>
                                <textarea id="skills" name="skills" rows="3"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                                    placeholder="e.g., PHP, Laravel, MySQL, JavaScript (comma-separated)">{{ old('skills', $user->skills) }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">Separate skills with commas</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Social Links -->
                <div class="bg-white shadow-sm rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">
                            Social Links
                        </h3>
                        <p class="text-sm text-gray-600">
                            Connect your professional social profiles
                        </p>
                    </div>
                    <div class="px-6 py-6">
                        <div class="grid grid-cols-1 gap-6">
                            <!-- LinkedIn -->
                            <div>
                                <label for="linkedin_url" class="block text-sm font-medium text-gray-700 mb-1">
                                    LinkedIn Profile
                                </label>
                                <input type="url" id="linkedin_url" name="linkedin_url"
                                    value="{{ old('linkedin_url', $user->linkedin_url) }}"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                                    placeholder="https://linkedin.com/in/yourprofile" />
                            </div>

                            <!-- GitHub -->
                            <div>
                                <label for="github_url" class="block text-sm font-medium text-gray-700 mb-1">
                                    GitHub Profile
                                </label>
                                <input type="url" id="github_url" name="github_url"
                                    value="{{ old('github_url', $user->github_url) }}"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                                    placeholder="https://github.com/yourprofile" />
                            </div>

                            <!-- Portfolio -->
                            <div>
                                <label for="portfolio_url" class="block text-sm font-medium text-gray-700 mb-1">
                                    Portfolio Website
                                </label>
                                <input type="url" id="portfolio_url" name="portfolio_url"
                                    value="{{ old('portfolio_url', $user->portfolio_url) }}"
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary focus:border-primary"
                                    placeholder="https://yourportfolio.com" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-between items-center bg-white shadow-sm rounded-lg p-6">
                    <a href="{{ route('alumni.dashboard') }}" class="text-gray-700 hover:text-gray-900 font-medium">
                        Cancel
                    </a>
                    <button type="submit"
                        class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-primary font-medium">
                        Save Profile
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
