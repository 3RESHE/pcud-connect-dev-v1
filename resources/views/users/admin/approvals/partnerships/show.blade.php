@extends('layouts.admin')

@section('title', 'Partnership Review - ' . $partnership->activity_title . ' - PCU-DASMA Connect')
@section('page-title', 'Partnership Review')

@section('content')
<div class="flex min-h-screen bg-gray-50">
    <!-- Main Content -->
    <main class="flex-1 w-full">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center mb-4">
                    <a href="{{ route('admin.approvals.partnerships.index') }}"
                        class="text-gray-400 hover:text-gray-600 mr-4 transition-colors flex-shrink-0"
                        aria-label="Back to Partnership Approvals">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                            <div class="min-w-0">
                                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 line-clamp-2">{{ $partnership->activity_title }}</h1>
                                <p class="text-gray-600 mt-1 text-sm sm:text-base">Review partnership proposal for approval or discussion</p>
                            </div>
                            <div class="flex flex-wrap items-center gap-2 flex-shrink-0">
                                <span class="@if ($partnership->status === 'submitted') bg-yellow-100 text-yellow-800
                                    @elseif($partnership->status === 'under_review') bg-blue-100 text-blue-800
                                    @elseif($partnership->status === 'approved') bg-green-100 text-green-800
                                    @elseif($partnership->status === 'rejected') bg-red-100 text-red-800
                                    @elseif($partnership->status === 'completed') bg-blue-100 text-blue-800 @endif px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-medium whitespace-nowrap">
                                    @if ($partnership->status === 'submitted')
                                        Pending Review
                                    @elseif($partnership->status === 'under_review')
                                        Under Discussion
                                    @else
                                        {{ ucfirst(str_replace('_', ' ', $partnership->status)) }}
                                    @endif
                                </span>
                                <span class="bg-purple-100 text-purple-800 px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-medium whitespace-nowrap">
                                    {{ $partnership->getActivityTypeDisplay() }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Alert -->
            @if ($partnership->status === 'submitted')
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-8">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-yellow-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="min-w-0">
                            <h4 class="font-semibold text-yellow-800">Awaiting Admin Review</h4>
                            <p class="text-sm text-yellow-700 mt-1">This partnership proposal was submitted on {{ $partnership->created_at->format('F j, Y \a\t g:i A') }} and is pending administrative review. Please review the content and take appropriate action.</p>
                        </div>
                    </div>
                </div>
            @elseif($partnership->status === 'under_review')
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <div class="min-w-0">
                            <h4 class="font-semibold text-blue-800">Under Discussion</h4>
                            <p class="text-sm text-blue-700 mt-1">An email has been sent to the partner regarding this proposal. You can now approve or reject it.</p>
                        </div>
                    </div>
                </div>
            @elseif($partnership->status === 'approved')
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-8">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-green-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="min-w-0">
                            <h4 class="font-semibold text-green-800">Approved Partnership</h4>
                            <p class="text-sm text-green-700 mt-1">This partnership proposal has been approved. Mark as complete once the activity is finished.</p>
                        </div>
                    </div>
                </div>
            @elseif($partnership->status === 'rejected')
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-8">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-red-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <div class="min-w-0">
                            <h4 class="font-semibold text-red-800">Proposal Rejected</h4>
                            <p class="text-sm text-red-700 mt-1">This proposal has been rejected. View is read-only.</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Two-column Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6 lg:space-y-8">
                    <!-- Partnership Content -->
                    <div class="bg-white shadow rounded-lg p-4 sm:p-6">
                        <h2 class="text-lg sm:text-xl font-semibold text-gray-900 mb-6">Proposal Details</h2>

                        <div class="space-y-6">
                            <!-- Organization Background -->
                            <div>
                                <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-3">Organization Background</h3>
                                <p class="text-gray-700 leading-relaxed text-sm sm:text-base overflow-hidden text-ellipsis">{{ $partnership->organization_background }}</p>
                            </div>

                            <!-- Activity Description -->
                            <div>
                                <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-3">Activity Description</h3>
                                <p class="text-gray-700 leading-relaxed text-sm sm:text-base overflow-hidden text-ellipsis">{{ $partnership->activity_description }}</p>
                            </div>

                            <!-- Activity Objectives -->
                            <div>
                                <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-3">Activity Objectives</h3>
                                <div class="text-gray-700 leading-relaxed text-sm sm:text-base whitespace-pre-wrap overflow-hidden">{{ $partnership->activity_objectives }}</div>
                            </div>

                            <!-- Expected Community Impact -->
                            <div>
                                <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-3">Expected Community Impact</h3>
                                <p class="text-gray-700 leading-relaxed text-sm sm:text-base overflow-hidden text-ellipsis">{{ $partnership->expected_impact }}</p>
                            </div>

                            <!-- Schedule & Location -->
                            <div>
                                <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Schedule & Location</h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                                    <div>
                                        <p class="text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">Date</p>
                                        <p class="text-sm sm:text-base text-gray-900 truncate">{{ $partnership->activity_date?->format('F j, Y') ?? 'TBA' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">Time</p>
                                        <p class="text-sm sm:text-base text-gray-900 truncate">{{ $partnership->activity_time?->format('g:i A') ?? 'TBA' }}</p>
                                    </div>
                                    <div class="sm:col-span-2">
                                        <p class="text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">Venue</p>
                                        <p class="text-sm sm:text-base text-gray-900 overflow-hidden text-ellipsis">{{ $partnership->venue_address }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <div>
                                <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-4">Contact Information</h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                                    <div>
                                        <p class="text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">Contact Person</p>
                                        <p class="text-sm sm:text-base text-gray-900 truncate">{{ $partnership->contact_name }}</p>
                                        <p class="text-xs sm:text-sm text-gray-600 truncate">{{ $partnership->contact_position }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">Email</p>
                                        <a href="mailto:{{ $partnership->contact_email }}" class="text-sm sm:text-base text-primary hover:underline truncate">{{ $partnership->contact_email }}</a>
                                    </div>
                                    <div>
                                        <p class="text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">Phone</p>
                                        <p class="text-sm sm:text-base text-gray-900 truncate">{{ $partnership->contact_phone }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Previous Experience -->
                            @if ($partnership->previous_experience)
                                <div>
                                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-3">Previous Community Service Experience</h3>
                                    <p class="text-gray-700 leading-relaxed text-sm sm:text-base overflow-hidden text-ellipsis">{{ $partnership->previous_experience }}</p>
                                </div>
                            @endif

                            <!-- Additional Notes -->
                            @if ($partnership->additional_notes)
                                <div>
                                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 mb-3">Additional Notes</h3>
                                    <p class="text-gray-700 leading-relaxed text-sm sm:text-base overflow-hidden text-ellipsis">{{ $partnership->additional_notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Review Timeline -->
                    <div class="bg-white shadow rounded-lg p-4 sm:p-6">
                        <h2 class="text-lg sm:text-lg font-semibold text-gray-900 mb-4">Review Timeline</h2>
                        <div class="space-y-4">
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0 w-3 h-3 bg-green-400 rounded-full mt-1.5"></div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-gray-900">Proposal Submitted</p>
                                    <p class="text-xs text-gray-500 truncate">{{ $partnership->created_at->format('F j, Y - g:i A') }}</p>
                                </div>
                            </div>

                            @if (in_array($partnership->status, ['under_review', 'approved', 'rejected', 'completed']))
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0 w-3 h-3 @if (in_array($partnership->status, ['approved', 'completed'])) bg-green-400 @elseif($partnership->status === 'rejected') bg-red-400 @else bg-blue-400 @endif rounded-full mt-1.5"></div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-medium text-gray-900">
                                            @if ($partnership->status === 'under_review')
                                                Under Discussion
                                            @elseif($partnership->status === 'approved')
                                                Approved
                                            @elseif($partnership->status === 'rejected')
                                                Rejected
                                            @elseif($partnership->status === 'completed')
                                                Completed
                                            @endif
                                        </p>
                                        <p class="text-xs text-gray-500 truncate">{{ $partnership->updated_at->format('F j, Y - g:i A') }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar - Admin Actions -->
                <div class="space-y-6">
                    <!-- Review Actions -->
                    <div class="bg-white shadow rounded-lg p-4 sm:p-6 top-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Review Actions</h2>
                        <div class="space-y-3">
                            <!-- PENDING STATUS: Show Discuss & Reject (NO Approve) -->
                            @if ($partnership->status === 'submitted')
                                <button onclick="openDiscussModal()"
                                    class="w-full px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 transition-colors">
                                    Send Discussion
                                </button>
                                <button onclick="openRejectModal()"
                                    class="w-full px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 transition-colors">
                                    Reject Proposal
                                </button>
                            @endif

                            <!-- DISCUSSED STATUS: Show Approve + Reject (NO Continue Discussion) -->
                            @if ($partnership->status === 'under_review')
                                <button onclick="openApproveModal()"
                                    class="w-full px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition-colors">
                                    Approve Partnership
                                </button>
                                <button onclick="openRejectModal()"
                                    class="w-full px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-md hover:bg-red-700 transition-colors">
                                    Reject Proposal
                                </button>
                            @endif

                            <!-- APPROVED STATUS: Show Mark Complete -->
                            @if ($partnership->status === 'approved')
                                <button onclick="openCompleteModal()"
                                    class="w-full px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 transition-colors">
                                    Mark as Complete
                                </button>
                            @endif

                            <!-- REJECTED/COMPLETED: Read-only -->
                            @if (in_array($partnership->status, ['rejected', 'completed']))
                                <div class="px-4 py-3 bg-gray-100 text-gray-700 text-sm rounded-md text-center font-medium">
                                    No actions available
                                </div>
                            @endif

                            <a href="{{ route('admin.approvals.partnerships.index') }}"
                                class="block w-full px-4 py-2 bg-gray-200 text-gray-700 text-center text-sm font-medium rounded-md hover:bg-gray-300 transition-colors">
                                Back to List
                            </a>
                        </div>
                    </div>

                    <!-- Organization Information -->
                    <div class="bg-white shadow rounded-lg p-4 sm:p-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Organization Details</h2>
                        <div class="space-y-4">
                            <div>
                                <p class="text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">Organization Name</p>
                                <p class="text-sm text-gray-900 truncate">{{ $partnership->organization_name }}</p>
                            </div>
                            @if ($partnership->organization_website)
                                <div>
                                    <p class="text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">Website</p>
                                    <a href="{{ $partnership->organization_website }}" target="_blank" rel="noopener"
                                        class="text-sm text-primary hover:underline truncate">{{ $partnership->organization_website }}</a>
                                </div>
                            @endif
                            <div>
                                <p class="text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">Phone</p>
                                <p class="text-sm text-gray-900 truncate">{{ $partnership->organization_phone }}</p>
                            </div>
                            <div>
                                <p class="text-xs sm:text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">Contact Person</p>
                                <p class="text-sm text-gray-900 truncate">{{ $partnership->contact_name }}</p>
                                <p class="text-xs text-gray-600 truncate">{{ $partnership->contact_position }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Admin Notes (if exists) -->
                    @if ($partnership->admin_notes)
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <h3 class="text-sm font-semibold text-yellow-900 mb-2">Admin Notes</h3>
                            <p class="text-sm text-yellow-700 leading-relaxed whitespace-pre-wrap break-words">{{ $partnership->admin_notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- MOVE TO DISCUSSION MODAL -->
            <div id="discussModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
                <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                    <div class="relative inline-block align-bottom bg-white rounded-lg shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full w-full mx-auto">
                        <form id="discussForm" method="POST" action="{{ route('admin.approvals.partnerships.move-to-discussion', $partnership->id) }}">
                            @csrf
                            <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900 truncate">Send Discussion to Partner</h3>
                                <p class="text-sm text-gray-500 mt-2">Your message will be sent to the partner via email.</p>
                            </div>
                            <div class="px-4 sm:px-6 py-4 space-y-4 max-h-96 overflow-y-auto">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Send To</label>
                                    <input type="email" value="{{ $partnership->contact_email }}" readonly
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 cursor-not-allowed text-sm truncate" />
                                </div>
                                <div>
                                    <label for="discussMessage" class="block text-sm font-medium text-gray-700 mb-1">
                                        Your Message <span class="text-red-600">*</span>
                                    </label>
                                    <textarea name="admin_notes" id="discussMessage" rows="6" required minlength="10"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm resize-none"
                                        placeholder="Write your discussion message to the partner..."></textarea>
                                    <p class="text-xs text-gray-500 mt-1">Minimum 10 characters required</p>
                                </div>
                            </div>
                            <div class="px-4 sm:px-6 py-4 border-t border-gray-200 flex flex-col-reverse sm:flex-row sm:justify-end gap-2 sm:gap-3">
                                <button type="button" onclick="closeDiscussModal()"
                                    class="w-full sm:w-auto px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 font-medium text-sm">
                                    Cancel
                                </button>
                                <button type="submit" id="discussSubmitBtn"
                                    class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-medium text-sm flex items-center justify-center gap-2 transition-all">
                                    <span id="discussBtnText">Send Message</span>
                                    <svg id="discussSpinner" class="hidden animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- APPROVE MODAL -->
            <div id="approveModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
                <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                    <div class="relative inline-block align-bottom bg-white rounded-lg shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full w-full mx-auto">
                        <form method="POST" action="{{ route('admin.approvals.partnerships.approve', $partnership->id) }}">
                            @csrf
                            <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                                <div class="flex items-center gap-3">
                                    <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <h3 class="text-lg font-medium text-gray-900">Approve Partnership?</h3>
                                        <p class="text-sm text-gray-500 mt-1">This will notify the partner of approval.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="px-4 sm:px-6 py-4 max-h-96 overflow-y-auto">
                                <label for="approveNotes" class="block text-sm font-medium text-gray-700 mb-2">Optional Notes</label>
                                <textarea name="admin_notes" id="approveNotes" rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 text-sm resize-none"
                                    placeholder="Add any notes for the partner..."></textarea>
                            </div>
                            <div class="px-4 sm:px-6 py-4 border-t border-gray-200 flex flex-col-reverse sm:flex-row sm:justify-end gap-2 sm:gap-3">
                                <button type="button" onclick="closeApproveModal()"
                                    class="w-full sm:w-auto px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 font-medium text-sm">
                                    Cancel
                                </button>
                                <button type="submit"
                                    class="w-full sm:w-auto px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 font-medium text-sm">
                                    Approve Partnership
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- REJECT MODAL -->
            <div id="rejectModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
                <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                    <div class="relative inline-block align-bottom bg-white rounded-lg shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full w-full mx-auto">
                        <form method="POST" action="{{ route('admin.approvals.partnerships.reject', $partnership->id) }}">
                            @csrf
                            <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                                <div class="flex items-center gap-3">
                                    <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <h3 class="text-lg font-medium text-gray-900">Reject Partnership?</h3>
                                        <p class="text-sm text-gray-500 mt-1">Provide feedback for the partner.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="px-4 sm:px-6 py-4 max-h-96 overflow-y-auto">
                                <label for="rejectNotes" class="block text-sm font-medium text-gray-700 mb-2">Reason for Rejection (Required)</label>
                                <textarea name="admin_notes" id="rejectNotes" rows="4" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500 text-sm resize-none"
                                    placeholder="Provide clear feedback and reasons..."></textarea>
                            </div>
                            <div class="px-4 sm:px-6 py-4 border-t border-gray-200 flex flex-col-reverse sm:flex-row sm:justify-end gap-2 sm:gap-3">
                                <button type="button" onclick="closeRejectModal()"
                                    class="w-full sm:w-auto px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 font-medium text-sm">
                                    Cancel
                                </button>
                                <button type="submit"
                                    class="w-full sm:w-auto px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 font-medium text-sm">
                                    Reject Partnership
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- MARK COMPLETE MODAL -->
            <div id="completeModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
                <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                    <div class="relative inline-block align-bottom bg-white rounded-lg shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full w-full mx-auto">
                        <form method="POST" action="{{ route('admin.approvals.partnerships.mark-complete', $partnership->id) }}">
                            @csrf
                            <div class="px-4 sm:px-6 py-4 border-b border-gray-200">
                                <div class="flex items-center gap-3">
                                    <div class="flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100">
                                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <h3 class="text-lg font-medium text-gray-900">Mark as Complete?</h3>
                                        <p class="text-sm text-gray-500 mt-1">This finalizes the partnership activity.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="px-4 sm:px-6 py-4 max-h-96 overflow-y-auto">
                                <label for="completeNotes" class="block text-sm font-medium text-gray-700 mb-2">Optional Notes</label>
                                <textarea name="admin_notes" id="completeNotes" rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm resize-none"
                                    placeholder="Add completion notes..."></textarea>
                            </div>
                            <div class="px-4 sm:px-6 py-4 border-t border-gray-200 flex flex-col-reverse sm:flex-row sm:justify-end gap-2 sm:gap-3">
                                <button type="button" onclick="closeCompleteModal()"
                                    class="w-full sm:w-auto px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 font-medium text-sm">
                                    Cancel
                                </button>
                                <button type="submit"
                                    class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-medium text-sm">
                                    Mark Complete
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
    function openDiscussModal() {
        document.getElementById('discussModal').classList.remove('hidden');
    }

    function closeDiscussModal() {
        document.getElementById('discussModal').classList.add('hidden');
    }

    function openApproveModal() {
        document.getElementById('approveModal').classList.remove('hidden');
    }

    function closeApproveModal() {
        document.getElementById('approveModal').classList.add('hidden');
    }

    function openRejectModal() {
        document.getElementById('rejectModal').classList.remove('hidden');
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
    }

    function openCompleteModal() {
        document.getElementById('completeModal').classList.remove('hidden');
    }

    function closeCompleteModal() {
        document.getElementById('completeModal').classList.add('hidden');
    }

    // Add loading state to discuss form submission
    document.getElementById('discussForm').addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('discussSubmitBtn');
        const btnText = document.getElementById('discussBtnText');
        const spinner = document.getElementById('discussSpinner');

        submitBtn.disabled = true;
        btnText.textContent = 'Sending...';
        spinner.classList.remove('hidden');
    });

    // Close on escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDiscussModal();
            closeApproveModal();
            closeRejectModal();
            closeCompleteModal();
        }
    });

    // Close on outside click
    document.addEventListener('click', function(e) {
        if (e.target.id === 'discussModal') closeDiscussModal();
        if (e.target.id === 'approveModal') closeApproveModal();
        if (e.target.id === 'rejectModal') closeRejectModal();
        if (e.target.id === 'completeModal') closeCompleteModal();
    });
</script>
@endsection
