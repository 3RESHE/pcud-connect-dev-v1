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
                    data-status="{{ $application->status }}">

                    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between mb-4 gap-4">
                        <!-- Applicant Info -->
                        <div class="flex items-center flex-1">
                            @if ($application->alumni && $application->alumni->avatar)
                                <img src="{{ asset($application->alumni->avatar) }}"
                                    alt="{{ $application->alumni->full_name }}"
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
                                        {{ substr($application->alumni->full_name ?? 'U', 0, 1) }}{{ substr(explode(' ', $application->alumni->full_name ?? 'U')[1] ?? '', 0, 1) }}
                                    </span>
                                </div>
                            @endif
                            <div class="min-w-0 flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 break-words">
                                    {{ $application->alumni->full_name ?? 'Unknown Applicant' }}
                                </h3>
                                <p class="text-sm text-gray-600">Applied {{ $application->created_at->format('F d, Y') }}
                                </p>
                                <div class="flex items-center space-x-3 mt-1">
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">PCU-DASMA
                                        Alumni</span>
                                </div>
                            </div>
                        </div>

                        <!-- Status Dropdown -->
                        <div class="flex flex-col items-start lg:items-end">
                            <div class="text-sm text-gray-500 mb-2">Status</div>
                            <form action="{{ route('partner.applications.update-status', $application->id) }}"
                                method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <select name="status" onchange="this.form.submit()"
                                    class="text-sm border border-gray-300 rounded px-2 py-1 focus:outline-none focus:ring-2 focus:ring-primary">
                                    <option value="pending" @if ($application->status === 'pending') selected @endif>Pending
                                        Review</option>
                                    <option value="reviewed" @if ($application->status === 'reviewed') selected @endif>Reviewed
                                    </option>
                                    <option value="approved" @if ($application->status === 'approved') selected @endif>Approved
                                    </option>
                                    <option value="rejected" @if ($application->status === 'rejected') selected @endif>Rejected
                                    </option>
                                </select>
                            </form>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Contact Information</h4>
                            <p class="text-sm text-gray-600">📧 {{ $application->alumni->email ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-600">📱 {{ $application->alumni->phone ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-600">📍 {{ $application->alumni->address ?? 'N/A' }}</p>
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
                                View Full Profile
                            </button>

                            @if ($application->status !== 'approved')
                                <form action="{{ route('partner.applications.approve', $application->id) }}"
                                    method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                        class="px-4 py-2 border border-green-300 text-green-700 text-sm rounded-md hover:bg-green-50 transition-colors">
                                        Approve
                                    </button>
                                </form>
                            @endif

                            @if ($application->status !== 'rejected')
                                <button
                                    onclick="openRejectModal({{ $application->id }}, '{{ $application->alumni->full_name ?? 'Applicant' }}')"
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

    <!-- View Full Profile Modal (AJAX-loaded content) -->
    <div id="profileModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeProfileModal()"></div>
            <div class="relative bg-white rounded-lg max-w-3xl w-full shadow-xl max-h-screen overflow-y-auto">
                <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900 break-words" id="modalProfileTitle">Applicant Profile
                    </h3>
                    <button onclick="closeProfileModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="px-6 py-6" id="modalProfileContent">
                    <!-- AJAX loaded -->
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
                        <h3 class="ml-4 text-lg font-medium text-gray-900" id="rejectApplicantName">Reject Application
                        </h3>
                    </div>
                    <form id="rejectForm" action="" method="POST">
                        @csrf
                        <input type="hidden" id="rejectReason" name="rejection_reason">
                        <textarea id="rejection_reason" name="rejection_reason" rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm"
                            placeholder="Tell the applicant why their application was rejected..."></textarea>
                        <div class="flex justify-end mt-4 gap-2">
                            <button type="button" onclick="closeRejectModal()"
                                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 text-sm font-medium">Cancel</button>
                            <button type="submit"
                                class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 text-sm font-medium">Reject
                                Application</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        /* AJAX view profile modal */
        function viewFullProfile(appId) {
            fetch(`/partner/applications/${appId}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    document.getElementById('modalProfileContent').innerHTML = data.html ||
                        '<div class="text-red-600">Failed to load profile</div>';
                    document.getElementById('profileModal').classList.remove('hidden');
                })
                .catch(() => {
                    document.getElementById('modalProfileContent').innerHTML =
                        '<div class="text-red-600">Failed to load profile</div>';
                    document.getElementById('profileModal').classList.remove('hidden');
                });
        }

        function closeProfileModal() {
            document.getElementById('profileModal').classList.add('hidden');
            document.getElementById('modalProfileContent').innerHTML = "";
        }

        /* Reject modal logic */
        function openRejectModal(appId, name) {
            document.getElementById('rejectApplicantName').textContent = `Reject Application for ${name}`;
            document.getElementById('rejectForm').action = `/partner/applications/${appId}/reject`;
            document.getElementById('rejection_reason').value = "";
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            document.getElementById('rejection_reason').value = "";
        }
        document.getElementById('rejectForm').addEventListener('submit', function(e) {
            e.preventDefault();
            // Optionally, add AJAX submission here for better UX
            this.submit();
        });

        /* FILTER & SEARCH (client-side) */
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
