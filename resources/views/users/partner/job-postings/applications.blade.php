@extends('layouts.partner')

@section('title', 'Applications - ' . $jobPosting->title . ' - PCU-DASMA Connect')

@section('content')
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Back Button & Header -->
        <div class="mb-6">
            <a href="{{ route('partner.job-postings.index') }}"
                class="flex items-center text-primary hover:text-blue-700 mb-4">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Job Postings
            </a>

            <!-- Job Posting Summary Card -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-4">
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            @if ($jobPosting->status === 'approved' && $jobPosting->sub_status === 'active')
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">Active</span>
                            @else
                                <span
                                    class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs">{{ ucfirst($jobPosting->status) }}</span>
                            @endif
                            <span
                                class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">{{ $jobPosting->getJobTypeDisplay() }}</span>
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2 break-words">{{ $jobPosting->title }}
                        </h1>
                        <p class="text-gray-600 mb-2">
                            {{ $jobPosting->department ?? 'N/A' }} • {{ $jobPosting->location }} •
                            {{ $jobPosting->getWorkSetupDisplay() }}
                        </p>
                        <p class="text-lg font-semibold text-green-600">{{ $jobPosting->getSalaryRangeDisplay() }}</p>
                    </div>
                    <div class="text-left md:text-right">
                        <p class="text-sm text-gray-500">Posted {{ $jobPosting->created_at->format('F d, Y') }}</p>
                        <p class="text-sm text-gray-500">Expires {{ $jobPosting->application_deadline->format('F d, Y') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Application Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white p-4 rounded-lg shadow-sm">
                <div class="text-2xl font-bold text-blue-600">{{ $stats['total'] }}</div>
                <div class="text-sm text-gray-600">Total Applications</div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm">
                <div class="text-2xl font-bold text-yellow-600">{{ $stats['pending'] }}</div>
                <div class="text-sm text-gray-600">Pending</div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm">
                <div class="text-2xl font-bold text-blue-600">{{ $stats['reviewed'] }}</div>
                <div class="text-sm text-gray-600">Reviewed</div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow-sm">
                <div class="text-2xl font-bold text-green-600">{{ $stats['approved'] }}</div>
                <div class="text-sm text-gray-600">Approved</div>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0 gap-4">
                <div class="relative flex-1">
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" id="searchApplicants" placeholder="Search applicants..."
                        class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent w-full md:w-64 text-sm">
                </div>
                <select id="statusFilter"
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary text-sm">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending Review</option>
                    <option value="reviewed">Reviewed</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                </select>
            </div>
        </div>

        <!-- Applicants List -->
        <div class="space-y-4" id="applicantsList">
            @forelse($applications as $application)
                <div class="applicant-card bg-white rounded-lg shadow-sm p-6 border-l-4
                @if ($application->status === 'pending') border-yellow-500
                @elseif($application->status === 'reviewed') border-blue-500
                @elseif($application->status === 'approved') border-green-500
                @elseif($application->status === 'rejected') border-red-500
                @else border-gray-500 @endif"
                    data-status="{{ $application->status }}"
                    data-app-id="{{ $application->id }}"
                    data-app-name="{{ $application->applicant->first_name }} {{ $application->applicant->last_name }}">

                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between mb-4 gap-4">
                        <!-- Applicant Info -->
                        <div class="flex items-center flex-1">
                            @php
                                $applicantName = $application->applicant->first_name . ' ' . $application->applicant->last_name;
                                $profilePhoto = null;

                                if ($application->applicant_type === 'alumni' && $application->alumni) {
                                    $profilePhoto = $application->alumni->profile_photo;
                                } elseif ($application->applicant_type === 'student' && $application->student) {
                                    $profilePhoto = $application->student->profile_photo;
                                }
                            @endphp

                            @if ($profilePhoto)
                                <img src="{{ asset($profilePhoto) }}"
                                    alt="{{ $applicantName }}"
                                    class="w-12 h-12 rounded-full object-cover mr-4 flex-shrink-0">
                            @else
                                <div
                                    class="w-12 h-12 rounded-full flex items-center justify-center mr-4 flex-shrink-0
                                @if ($application->status === 'approved') bg-green-100
                                @elseif($application->status === 'reviewed') bg-blue-100
                                @elseif($application->status === 'rejected') bg-red-100
                                @else bg-purple-100 @endif">
                                    <span
                                        class="font-semibold text-lg
                                    @if ($application->status === 'approved') text-green-600
                                    @elseif($application->status === 'reviewed') text-blue-600
                                    @elseif($application->status === 'rejected') text-red-600
                                    @else text-purple-600 @endif">
                                        {{ substr($applicantName, 0, 1) }}{{ substr(explode(' ', $applicantName)[1] ?? '', 0, 1) }}
                                    </span>
                                </div>
                            @endif

                            <div class="min-w-0 flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 break-words">
                                    {{ $applicantName }}
                                </h3>
                                <p class="text-sm text-gray-600">Applied {{ $application->created_at->format('F d, Y') }}
                                </p>
                                <div class="flex items-center space-x-3 mt-1">
                                    @if ($application->applicant_type === 'alumni')
                                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">PCU-DASMA Alumni</span>
                                    @else
                                        <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded-full text-xs">PCU-DASMA Student</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Status Dropdown -->
                        <div class="flex flex-col items-start lg:items-end">
                            <div class="text-sm text-gray-500 mb-2">Status</div>
                            <form action=""
                                method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <select name="status" onchange="this.form.submit()"
                                    class="text-sm border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-primary">
                                    <option value="pending" @if ($application->status === 'pending') selected @endif>Pending Review</option>
                                    <option value="reviewed" @if ($application->status === 'reviewed') selected @endif>Reviewed</option>
                                    <option value="approved" @if ($application->status === 'approved') selected @endif>Approved</option>
                                    <option value="rejected" @if ($application->status === 'rejected') selected @endif>Rejected</option>
                                </select>
                            </form>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Contact Information</h4>
                            <p class="text-sm text-gray-600">📧 {{ $application->applicant->email ?? 'N/A' }}</p>
                            @if ($application->applicant_type === 'alumni' && $application->alumni)
                                <p class="text-sm text-gray-600">📱 {{ $application->alumni->phone ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-600">📍 {{ $application->alumni->current_location ?? 'N/A' }}</p>
                            @elseif ($application->applicant_type === 'student' && $application->student)
                                <p class="text-sm text-gray-600">📱 {{ $application->student->phone ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-600">📍 {{ $application->student->address ?? 'N/A' }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Cover Letter Excerpt -->
                    @if ($application->cover_letter)
                        <div class="bg-gray-50 p-3 rounded-lg mb-4">
                            <h4 class="font-medium text-gray-900 mb-2">Cover Letter Excerpt</h4>
                            <p class="text-sm text-gray-700 line-clamp-2 break-words">
                                {{ Str::limit($application->cover_letter, 200) }}</p>
                        </div>
                    @endif

                    <!-- Footer Actions -->
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div class="flex items-center space-x-4 text-sm text-gray-500">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Applied {{ $application->created_at->diffForHumans() }}
                            </div>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <button onclick="viewFullProfile({{ $application->id }})"
                                class="px-4 py-2 bg-primary text-white text-sm rounded-md hover:bg-blue-700 transition-colors">
                                View Profile & Resume
                            </button>

                            @if ($application->status !== 'approved')
                                <form action=""
                                    method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit"
                                        class="px-4 py-2 border border-green-300 text-green-700 text-sm rounded-md hover:bg-green-50 transition-colors">
                                        Approve
                                    </button>
                                </form>
                            @endif

                            @if ($application->status !== 'rejected')
                                <button
                                    onclick="openRejectModal({{ $application->id }}, '{{ $applicantName }}')"
                                    class="px-4 py-2 border border-red-300 text-red-700 text-sm rounded-md hover:bg-red-50 transition-colors">
                                    Reject
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-lg shadow p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                        </path>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No applications found</h3>
                    <p class="text-gray-600">There are no applications for this job posting yet.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($applications->hasPages())
            <div class="mt-8">
                {{ $applications->links('pagination::tailwind') }}
            </div>
        @endif
    </div>

    <!-- View Full Profile Modal -->
    <div id="profileModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeProfileModal()"></div>
            <div class="relative bg-white rounded-lg max-w-4xl w-full shadow-xl max-h-screen overflow-y-auto">
                <!-- Header -->
                <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900 break-words" id="modalProfileTitle">Applicant Profile</h3>
                    <button onclick="closeProfileModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Content -->
                <div class="px-6 py-6 space-y-6" id="modalProfileContent">
                    <!-- Loading state -->
                    <div class="text-center py-12">
                        <div class="inline-block">
                            <svg class="animate-spin h-8 w-8 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                        <p class="text-gray-600 mt-4">Loading profile...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeRejectModal()"></div>
            <div class="relative bg-white rounded-lg max-w-md w-full shadow-xl mx-auto">
                <div class="px-6 py-8">
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 rounded-full bg-red-100">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                        <h3 class="ml-4 text-lg font-medium text-gray-900" id="rejectApplicantName">Reject Application</h3>
                    </div>
                    <form id="rejectForm" action="" method="POST">
                        @csrf
                        @method('PATCH')
                        <textarea id="rejection_reason" name="rejection_reason" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm"
                            placeholder="Tell the applicant why their application was rejected..."></textarea>
                        <div class="flex justify-end mt-4 gap-2">
                            <button type="button" onclick="closeRejectModal()"
                                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 text-sm font-medium">Cancel</button>
                            <button type="submit"
                                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 text-sm font-medium">Reject Application</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        /* View Full Profile with Resume */
        function viewFullProfile(appId) {
            fetch(`/partner/applications/${appId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const application = data.application;
                        const profile = data.profile;

                        let profileHTML = buildProfileHTML(application, profile);

                        document.getElementById('modalProfileContent').innerHTML = profileHTML;
                        document.getElementById('modalProfileTitle').textContent = `${application.applicant.first_name} ${application.applicant.last_name}`;
                        document.getElementById('profileModal').classList.remove('hidden');
                    } else {
                        document.getElementById('modalProfileContent').innerHTML =
                            '<div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">Failed to load profile</div>';
                        document.getElementById('profileModal').classList.remove('hidden');
                    }
                })
                .catch(err => {
                    console.error(err);
                    document.getElementById('modalProfileContent').innerHTML =
                        '<div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded">Error loading profile</div>';
                    document.getElementById('profileModal').classList.remove('hidden');
                });
        }

        function buildProfileHTML(application, profile) {
            const applicantName = `${application.applicant.first_name} ${application.applicant.last_name}`;
            const applicantType = application.applicant_type === 'alumni' ? 'Alumni' : 'Student';
            const profilePhoto = profile?.profile_photo ? `<img src="${profile.profile_photo}" alt="${applicantName}" class="w-24 h-24 rounded-full object-cover">` :
                `<div class="w-24 h-24 rounded-full bg-purple-100 flex items-center justify-center"><span class="text-2xl font-bold text-purple-600">${applicantName.charAt(0)}</span></div>`;

            let resumeHTML = '';
            if (application.resume_path) {
                const resumeExt = application.resume_path.split('.').pop().toLowerCase();
                resumeHTML = `
                    <div class="border-t border-gray-200 pt-4 mt-4">
                        <h4 class="font-semibold text-gray-900 mb-2">📄 Resume</h4>
                        <div class="flex items-center justify-between bg-gray-50 p-3 rounded-lg">
                            <div class="flex items-center gap-3">
                                <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 3a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V3zm2 4a1 1 0 100 2h4a1 1 0 100-2H6zm0 4a1 1 0 100 2h4a1 1 0 100-2H6z"/>
                                </svg>
                                <div>
                                    <p class="font-medium text-gray-900">Resume</p>
                                    <p class="text-xs text-gray-500">${resumeExt.toUpperCase()}</p>
                                </div>
                            </div>
                            <a href="${application.resume_path}" target="_blank" class="px-3 py-1 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                                View
                            </a>
                        </div>
                    </div>
                `;
            }

            let coverLetterHTML = '';
            if (application.cover_letter) {
                coverLetterHTML = `
                    <div class="border-t border-gray-200 pt-4 mt-4">
                        <h4 class="font-semibold text-gray-900 mb-2">📝 Cover Letter</h4>
                        <div class="bg-gray-50 p-4 rounded-lg max-h-48 overflow-y-auto">
                            <p class="text-sm text-gray-700 whitespace-pre-wrap">${application.cover_letter}</p>
                        </div>
                    </div>
                `;
            }

            let profileDetailsHTML = '';
            if (application.applicant_type === 'alumni' && profile) {
                profileDetailsHTML = `
                    <div class="border-t border-gray-200 pt-4 mt-4 grid grid-cols-2 gap-4">
                        ${profile.current_position ? `<div><p class="text-xs text-gray-500">Position</p><p class="font-medium">${profile.current_position}</p></div>` : ''}
                        ${profile.current_organization ? `<div><p class="text-xs text-gray-500">Organization</p><p class="font-medium">${profile.current_organization}</p></div>` : ''}
                        ${profile.current_industry ? `<div><p class="text-xs text-gray-500">Industry</p><p class="font-medium">${profile.current_industry}</p></div>` : ''}
                        ${profile.current_location ? `<div><p class="text-xs text-gray-500">Location</p><p class="font-medium">${profile.current_location}</p></div>` : ''}
                        ${profile.graduation_year ? `<div><p class="text-xs text-gray-500">Graduation</p><p class="font-medium">Class of ${profile.graduation_year}</p></div>` : ''}
                        ${profile.phone ? `<div><p class="text-xs text-gray-500">Phone</p><p class="font-medium">${profile.phone}</p></div>` : ''}
                    </div>
                `;
            } else if (application.applicant_type === 'student' && profile) {
                profileDetailsHTML = `
                    <div class="border-t border-gray-200 pt-4 mt-4 grid grid-cols-2 gap-4">
                        ${profile.phone ? `<div><p class="text-xs text-gray-500">Phone</p><p class="font-medium">${profile.phone}</p></div>` : ''}
                        ${profile.address ? `<div><p class="text-xs text-gray-500">Address</p><p class="font-medium">${profile.address}</p></div>` : ''}
                        ${profile.year_level ? `<div><p class="text-xs text-gray-500">Year Level</p><p class="font-medium">${profile.year_level}</p></div>` : ''}
                        ${profile.course ? `<div><p class="text-xs text-gray-500">Course</p><p class="font-medium">${profile.course}</p></div>` : ''}
                    </div>
                `;
            }

            return `
                <div class="space-y-4">
                    <!-- Profile Header -->
                    <div class="flex items-center gap-4">
                        ${profilePhoto}
                        <div class="flex-1">
                            <h3 class="text-2xl font-bold text-gray-900">${applicantName}</h3>
                            <p class="text-gray-600">${application.applicant.email}</p>
                            <span class="inline-block mt-2 px-3 py-1 rounded-full text-xs font-medium ${application.applicant_type === 'alumni' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800'}">
                                ${applicantType}
                            </span>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="border-t border-gray-200 pt-4">
                        <p class="text-sm text-gray-500 mb-1">Application Status</p>
                        <div class="inline-block px-3 py-1 rounded-full text-sm font-medium ${
                            application.status === 'approved' ? 'bg-green-100 text-green-800' :
                            application.status === 'rejected' ? 'bg-red-100 text-red-800' :
                            application.status === 'reviewed' ? 'bg-blue-100 text-blue-800' :
                            'bg-yellow-100 text-yellow-800'
                        }">
                            ${application.status.charAt(0).toUpperCase() + application.status.slice(1)}
                        </div>
                    </div>

                    <!-- Profile Details -->
                    ${profileDetailsHTML}

                    <!-- Resume -->
                    ${resumeHTML}

                    <!-- Cover Letter -->
                    ${coverLetterHTML}

                    <!-- Application Date -->
                    <div class="border-t border-gray-200 pt-4">
                        <p class="text-xs text-gray-500">Applied on: <span class="font-medium text-gray-900">${new Date(application.created_at).toLocaleDateString()}</span></p>
                    </div>
                </div>
            `;
        }

        function closeProfileModal() {
            document.getElementById('profileModal').classList.add('hidden');
            document.getElementById('modalProfileContent').innerHTML = "";
        }

        /* Reject Modal Logic */
        function openRejectModal(appId, name) {
            document.getElementById('rejectApplicantName').textContent = `Reject Application for ${name}`;
            document.getElementById('rejectForm').action = `/partner/jobs/{{ $jobPosting->id }}/applications/${appId}/reject`;
            document.getElementById('rejection_reason').value = "";
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            document.getElementById('rejection_reason').value = "";
        }

        document.getElementById('rejectForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            this.submit();
        });

        /* Filter & Search */
        document.getElementById('statusFilter').addEventListener('change', filterApplicants);
        document.getElementById('searchApplicants').addEventListener('input', filterApplicants);

        function filterApplicants() {
            let filter = document.getElementById('statusFilter').value.trim().toLowerCase();
            let search = document.getElementById('searchApplicants').value.trim().toLowerCase();
            document.querySelectorAll('.applicant-card').forEach(function(card) {
                let matchesStatus = !filter || card.getAttribute('data-status').toLowerCase() === filter;
                let matchesSearch = !search || card.textContent.toLowerCase().indexOf(search) !== -1;
                card.style.display = matchesStatus && matchesSearch ? '' : 'none';
            });
        }
    </script>
@endpush
