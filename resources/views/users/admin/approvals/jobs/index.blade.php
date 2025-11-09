@extends('layouts.admin')

@section('title', 'Job Posting Approvals - PCU-DASMA Connect')

@section('content')
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Job Posting Approvals</h1>
            <p class="text-gray-600 mt-2">Review and approve pending job postings from partner companies</p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Pending -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Pending Review</p>
                        <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $stats['pending'] }}</p>
                    </div>
                    <div class="text-yellow-500 text-4xl opacity-20">
                        <svg fill="currentColor" viewBox="0 0 20 20" class="w-12 h-12">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 102 0V6z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Active -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Active Posts</p>
                        <p class="text-3xl font-bold text-green-600 mt-2">{{ $stats['active'] }}</p>
                    </div>
                    <div class="text-green-500 text-4xl opacity-20">
                        <svg fill="currentColor" viewBox="0 0 20 20" class="w-12 h-12">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Approved Today -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Approved Today</p>
                        <p class="text-3xl font-bold text-blue-600 mt-2">{{ $stats['approved_today'] }}</p>
                    </div>
                    <div class="text-blue-500 text-4xl opacity-20">
                        <svg fill="currentColor" viewBox="0 0 20 20" class="w-12 h-12">
                            <path
                                d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Rejected -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Rejected</p>
                        <p class="text-3xl font-bold text-red-600 mt-2">{{ $stats['rejected'] }}</p>
                    </div>
                    <div class="text-red-500 text-4xl opacity-20">
                        <svg fill="currentColor" viewBox="0 0 20 20" class="w-12 h-12">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                <h3 class="text-red-800 font-semibold mb-2">Errors:</h3>
                <ul class="text-red-700 text-sm list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                <p class="text-green-700">{{ session('success') }}</p>
            </div>
        @endif

        @if (session('warning'))
            <div class="mb-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <p class="text-yellow-700">{{ session('warning') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                <p class="text-red-700">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Filters and Search -->
        <div class="bg-white rounded-lg shadow mb-8 p-6">
            <form method="GET" class="flex flex-col md:flex-row gap-4">
                <!-- Search -->
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search Job</label>
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-sm"
                        placeholder="Search by job title, department, or company...">
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" onchange="this.form.submit()"
                        class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent text-sm">
                        <option value="all">All Statuses</option>
                        <option value="pending" @if (request('status') === 'pending') selected @endif>Pending</option>
                        <option value="approved" @if (request('status') === 'approved') selected @endif>Approved</option>
                        <option value="rejected" @if (request('status') === 'rejected') selected @endif>Rejected</option>
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="flex items-end">
                    <button type="submit"
                        class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                        Search
                    </button>
                </div>
            </form>
        </div>

        <!-- Job Postings List -->
        <div class="space-y-6">
            @forelse($jobPostings as $job)
                <div
                    class="bg-white rounded-lg shadow-md overflow-hidden border-l-4
                @if ($job->status === 'pending') border-yellow-500
                @elseif($job->status === 'approved') border-green-500
                @elseif($job->status === 'rejected') border-red-500 @endif">

                    <div class="p-6">
                        <!-- Header -->
                        <div class="flex flex-col lg:flex-row lg:justify-between lg:items-start mb-4 gap-4">
                            <div class="flex-1 min-w-0">
                                <!-- Job Title -->
                                <div class="flex items-center gap-3 mb-2 flex-wrap">
                                    <h3 class="text-xl font-bold text-gray-900 break-words">{{ $job->title }}</h3>
                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-semibold
                                    @if ($job->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($job->status === 'approved') bg-green-100 text-green-800
                                    @elseif($job->status === 'rejected') bg-red-100 text-red-800 @endif">
                                        {{ ucfirst($job->status) }}
                                    </span>
                                </div>

                                <!-- Company & Details -->
                                <p class="text-gray-700 font-semibold mb-2">{{ $job->partner->company_name ?? 'N/A' }}</p>
                                <p class="text-gray-600 text-sm mb-3">
                                    {{ $job->department ?? 'N/A' }} • {{ $job->getJobTypeDisplay() }} •
                                    {{ $job->getExperienceLevelDisplay() }} • {{ $job->getWorkSetupDisplay() }}
                                </p>

                                <!-- Salary -->
                                <p class="text-lg font-semibold text-green-600 mb-3">{{ $job->getSalaryRangeDisplay() }}
                                </p>
                            </div>

                            <!-- Status Badge and Date -->
                            <div class="text-left lg:text-right">
                                <p class="text-sm text-gray-500">Submitted</p>
                                <p class="font-semibold text-gray-900">{{ $job->created_at->format('M d, Y') }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $job->created_at->diffForHumans() }}</p>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="bg-gray-50 p-4 rounded-lg mb-4">
                            <h4 class="text-sm font-semibold text-gray-900 mb-2">Job Description</h4>
                            <p class="text-sm text-gray-700 line-clamp-3 break-words">
                                {{ Str::limit($job->description, 300) }}</p>
                        </div>

                        <!-- Job Details Grid -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                            <div class="bg-gray-50 p-3 rounded">
                                <p class="text-xs text-gray-600 uppercase tracking-wide">Positions</p>
                                <p class="text-lg font-bold text-gray-900">{{ $job->positions_available }}</p>
                            </div>
                            <div class="bg-gray-50 p-3 rounded">
                                <p class="text-xs text-gray-600 uppercase tracking-wide">Experience</p>
                                <p class="text-sm font-bold text-gray-900 break-words">
                                    {{ $job->getExperienceLevelDisplay() }}</p>
                            </div>
                            <div class="bg-gray-50 p-3 rounded">
                                <p class="text-xs text-gray-600 uppercase tracking-wide">Location</p>
                                <p class="text-sm font-bold text-gray-900">{{ $job->location ?? 'Remote' }}</p>
                            </div>
                            <div class="bg-gray-50 p-3 rounded">
                                <p class="text-xs text-gray-600 uppercase tracking-wide">Deadline</p>
                                <p class="text-sm font-bold text-gray-900">
                                    {{ $job->application_deadline->format('M d, Y') }}</p>
                            </div>
                        </div>

                        <!-- Partner Information -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                            <h4 class="text-sm font-semibold text-blue-900 mb-2">Partner Company Details</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p class="text-blue-700 text-xs font-medium">Company</p>
                                    <p class="text-blue-900 font-semibold">{{ $job->partner->company_name ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-blue-700 text-xs font-medium">Contact Person</p>
                                    <p class="text-blue-900 font-semibold">{{ $job->partner->full_name ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-blue-700 text-xs font-medium">Email</p>
                                    <p class="text-blue-900 break-words">{{ $job->partner->email ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-blue-700 text-xs font-medium">Phone</p>
                                    <p class="text-blue-900">{{ $job->partner->phone ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Rejection Reason (if rejected) -->
                        @if ($job->status === 'rejected' && $job->rejection_reason)
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
                                <h4 class="text-sm font-semibold text-red-900 mb-2">Rejection Reason</h4>
                                <p class="text-sm text-red-800 break-words">{{ $job->rejection_reason }}</p>
                            </div>
                        @endif

                        <!-- Actions -->
                        @if ($job->status === 'pending')
                            <div class="flex flex-col sm:flex-row gap-3">
                                <a href="{{ route('admin.approvals.jobs.show', $job->id) }}"
                                    class="px-6 py-2 bg-gray-100 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-200 transition-colors font-medium text-sm text-center">
                                    View Job Details
                                </a>
                                <button type="button"
                                    onclick="openRejectModal({{ $job->id }}, '{{ addslashes($job->title) }}')"
                                    class="px-6 py-2 border border-red-300 text-red-700 rounded-lg hover:bg-red-50 transition-colors font-medium text-sm">
                                    Reject
                                </button>
                                <button type="button"
                                    onclick="openApproveModal({{ $job->id }}, '{{ addslashes($job->title) }}')"
                                    class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium text-sm">
                                    Approve & Publish
                                </button>
                            </div>
                        @elseif($job->status === 'approved')
                            <div class="flex flex-col sm:flex-row gap-3 items-start sm:items-center">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-green-700 font-semibold">Approved and Published</span>
                                </div>
                                <a href="{{ route('admin.approvals.jobs.show', $job->id) }}"
                                    class="px-6 py-2 bg-gray-100 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-200 transition-colors font-medium text-sm text-center">
                                    View Job Details
                                </a>
                            </div>
                        @elseif($job->status === 'rejected')
                            <div class="flex flex-col sm:flex-row gap-3 items-start sm:items-center">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-red-700 font-semibold">Rejected</span>
                                </div>
                                <a href="{{ route('admin.approvals.jobs.show', $job->id) }}"
                                    class="px-6 py-2 bg-gray-100 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-200 transition-colors font-medium text-sm text-center">
                                    View Job Details
                                </a>
                            </div>
                        @endif
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
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No job postings found</h3>
                    <p class="text-gray-600">There are no job postings to review at the moment.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($jobPostings->hasPages())
            <div class="mt-8">
                {{ $jobPostings->links('pagination::tailwind') }}
            </div>
        @endif

        <!-- Approve Confirmation Modal -->
        <div id="approveModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeApproveModal()">
                </div>
                <div class="relative bg-white rounded-lg max-w-md w-full shadow-xl mx-auto">
                    <form id="approveForm" action="" method="POST">
                        @csrf
                        <div class="px-6 py-8">
                            <div class="flex items-center justify-center mb-4">
                                <div
                                    class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2" id="approveJobTitle">Approve Job Posting
                            </h3>
                            <p class="text-sm text-gray-600 mb-4">
                                Are you sure you want to approve and publish this job posting? It will become visible to all
                                alumni immediately.
                            </p>
                        </div>

                        <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-3">
                            <button type="button" onclick="closeApproveModal()"
                                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm font-medium">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium">
                                Approve & Publish
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Reject Modal -->
        <div id="rejectModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeRejectModal()">
                </div>
                <div class="relative bg-white rounded-lg max-w-md w-full shadow-xl mx-auto">
                    <form id="rejectForm" action="" method="POST">
                        @csrf
                        <div class="px-6 py-8">
                            <div class="flex items-center justify-center mb-4">
                                <div
                                    class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4" id="rejectJobTitle">Reject Job Posting</h3>

                            <div class="text-left">
                                <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">
                                    Rejection Reason <span class="text-red-500">*</span>
                                </label>
                                <textarea id="rejection_reason" name="rejection_reason" rows="4" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm"
                                    placeholder="Explain why this job posting is being rejected..."></textarea>
                                @error('rejection_reason')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-3">
                            <button type="button" onclick="closeRejectModal()"
                                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors text-sm font-medium">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium">
                                Reject
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Approve Modal
        function openApproveModal(jobId, jobTitle) {
            document.getElementById('approveJobTitle').textContent = `Approve: ${jobTitle}`;
            document.getElementById('approveForm').action = `/admin/approvals/jobs/${jobId}/approve`;
            document.getElementById('approveModal').classList.remove('hidden');
        }

        function closeApproveModal() {
            document.getElementById('approveModal').classList.add('hidden');
        }

        // Reject Modal
        function openRejectModal(jobId, jobTitle) {
            document.getElementById('rejectJobTitle').textContent = `Reject: ${jobTitle}`;
            document.getElementById('rejectForm').action = `/admin/approvals/jobs/${jobId}/reject`;
            document.getElementById('rejection_reason').value = '';
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
        }

        // ESC key closes modals
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeApproveModal();
                closeRejectModal();
            }
        });
    </script>
@endsection
