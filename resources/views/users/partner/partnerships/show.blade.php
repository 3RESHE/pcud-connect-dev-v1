@extends('layouts.partner')

@section('title', 'View Partnership - ' . $partnership->activity_title . ' - PCU-DASMA Connect')

@section('content')
<!-- Main Content -->
<div class="max-w-6xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-start mb-4">
            <a href="{{ route('partner.partnerships.index') }}"
                class="text-gray-400 hover:text-gray-600 mr-4 transition-colors duration-200"
                aria-label="Back to Partnerships">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div class="flex-1">
                <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between">
                    <div class="mb-4 lg:mb-0">
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">
                            {{ $partnership->activity_title }}
                        </h1>
                        <p class="text-gray-600">
                            @if($partnership->status === 'submitted')
                                Manage your pending partnership proposal
                            @elseif($partnership->status === 'under_review')
                                Partnership proposal under discussion
                            @elseif($partnership->status === 'approved')
                                Approved partnership proposal
                            @elseif($partnership->status === 'rejected')
                                Rejected partnership proposal
                            @elseif($partnership->status === 'completed')
                                Completed partnership activity
                            @endif
                        </p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="@if($partnership->status === 'submitted') bg-yellow-100 text-yellow-800
                            @elseif($partnership->status === 'under_review') bg-blue-100 text-blue-800
                            @elseif($partnership->status === 'approved') bg-green-100 text-green-800
                            @elseif($partnership->status === 'rejected') bg-red-100 text-red-800
                            @elseif($partnership->status === 'completed') bg-blue-100 text-blue-800
                            @endif px-3 py-1 rounded-full text-sm font-medium whitespace-nowrap">
                            @if($partnership->status === 'submitted')
                                Pending
                            @elseif($partnership->status === 'under_review')
                                Under Discussion
                            @else
                                {{ ucfirst($partnership->status) }}
                            @endif
                        </span>
                        <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm whitespace-nowrap">
                            {{ $partnership->getActivityTypeDisplay() }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Alert -->
    @if($partnership->status === 'submitted')
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-8">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-yellow-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h4 class="font-semibold text-yellow-800">Partnership Proposal Pending</h4>
                    <p class="text-sm text-yellow-700">
                        This partnership proposal was submitted on {{ $partnership->created_at->format('F j, Y, g:i A') }} and is currently under review by the PCU-DASMA Connect team. You can edit or withdraw the proposal while it is pending.
                    </p>
                </div>
            </div>
        </div>
    @elseif($partnership->status === 'under_review')
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                <div>
                    <h4 class="font-semibold text-blue-800">Under Discussion</h4>
                    <p class="text-sm text-blue-700">
                        PCU-DASMA administrators are currently reviewing your partnership proposal and may have questions or clarifications before approval.
                    </p>
                </div>
            </div>
        </div>
    @elseif($partnership->status === 'approved')
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-8">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-green-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h4 class="font-semibold text-green-800">Partnership Approved!</h4>
                    <p class="text-sm text-green-700">
                        Your partnership proposal has been approved by PCU-DASMA administrators. Please check your email for coordination details and next steps.
                    </p>
                </div>
            </div>
        </div>
    @elseif($partnership->status === 'rejected')
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-8">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-red-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                <div>
                    <h4 class="font-semibold text-red-800">Partnership Not Approved</h4>
                    <p class="text-sm text-red-700">
                        Your partnership proposal was reviewed but not approved. Please review the feedback below for suggestions on resubmission.
                    </p>
                    @if($partnership->admin_notes)
                        <div class="mt-3 p-3 bg-white rounded border border-red-200">
                            <p class="text-sm font-medium text-red-800">Admin Feedback:</p>
                            <p class="text-sm text-red-700 mt-1">{{ $partnership->admin_notes }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @elseif($partnership->status === 'completed')
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-8">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-blue-600 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h4 class="font-semibold text-blue-800">Partnership Completed</h4>
                    <p class="text-sm text-blue-700">
                        This community activity was successfully completed on {{ $partnership->completed_at?->format('F j, Y') ?? 'N/A' }}. Thank you for partnering with PCU-DASMA!
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Two-column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Partnership Content -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Proposal Details</h2>

                <div class="space-y-6">
                    <!-- Organization Name -->
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">Organization Name</p>
                        <p class="text-base text-gray-900">{{ $partnership->organization_name }}</p>
                    </div>

                    <!-- Date Submitted -->
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">Date Submitted</p>
                        <p class="text-base text-gray-900">{{ $partnership->created_at->format('F j, Y - g:i A') }}</p>
                    </div>

                    <!-- Organization Background -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Organization Background</h3>
                        <p class="text-gray-700 leading-relaxed">{{ $partnership->organization_background }}</p>
                    </div>

                    <!-- Activity Description -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Activity Description</h3>
                        <p class="text-gray-700 leading-relaxed">{{ $partnership->activity_description }}</p>
                    </div>

                    <!-- Activity Objectives -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Activity Objectives</h3>
                        <div class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $partnership->activity_objectives }}</div>
                    </div>

                    <!-- Expected Community Impact -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Expected Community Impact</h3>
                        <p class="text-gray-700 leading-relaxed">{{ $partnership->expected_impact }}</p>
                    </div>

                    <!-- Schedule & Location -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Schedule & Location</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">Date</p>
                                <p class="text-base text-gray-900">{{ $partnership->activity_date?->format('F j, Y') ?? 'TBA' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">Time</p>
                                <p class="text-base text-gray-900">{{ $partnership->activity_time?->format('g:i A') ?? 'TBA' }}</p>
                            </div>
                            <div class="md:col-span-2">
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">Venue</p>
                                <p class="text-base text-gray-900">{{ $partnership->venue_address }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Contact Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">Contact Person</p>
                                <p class="text-base text-gray-900">{{ $partnership->contact_name }}</p>
                                <p class="text-sm text-gray-600">{{ $partnership->contact_position }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">Email</p>
                                <p class="text-base text-primary">{{ $partnership->contact_email }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">Phone</p>
                                <p class="text-base text-gray-900">{{ $partnership->contact_phone }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Previous Experience -->
                    @if($partnership->previous_experience)
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Previous Community Service Experience</h3>
                            <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $partnership->previous_experience }}</p>
                        </div>
                    @endif

                    <!-- Additional Notes -->
                    @if($partnership->additional_notes)
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Additional Notes</h3>
                            <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $partnership->additional_notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Review Timeline -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Review Timeline</h2>
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-3 h-3 bg-green-400 rounded-full mt-1.5"></div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-900">Proposal Submitted</p>
                            <p class="text-xs text-gray-500">{{ $partnership->created_at->format('F j, Y - g:i A') }}</p>
                        </div>
                    </div>

                    @if($partnership->status === 'under_review')
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-3 h-3 bg-blue-400 rounded-full mt-1.5"></div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">Under Review</p>
                                <p class="text-xs text-gray-500">Currently being reviewed by admins</p>
                            </div>
                        </div>
                    @endif

                    @if($partnership->status === 'approved')
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-3 h-3 bg-green-400 rounded-full mt-1.5"></div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">Proposal Approved</p>
                                <p class="text-xs text-gray-500">{{ $partnership->updated_at->format('F j, Y - g:i A') }}</p>
                            </div>
                        </div>
                    @endif

                    @if($partnership->status === 'rejected')
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-3 h-3 bg-red-400 rounded-full mt-1.5"></div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">Proposal Rejected</p>
                                <p class="text-xs text-gray-500">{{ $partnership->updated_at->format('F j, Y - g:i A') }}</p>
                            </div>
                        </div>
                    @endif

                    @if($partnership->status === 'completed')
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-3 h-3 bg-blue-400 rounded-full mt-1.5"></div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">Activity Completed</p>
                                <p class="text-xs text-gray-500">{{ $partnership->completed_at?->format('F j, Y') ?? 'N/A' }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Actions -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Actions</h2>
                <div class="space-y-3">
                    @if(in_array($partnership->status, ['submitted', 'under_review', 'rejected']))
                        <a href="{{ route('partner.partnerships.edit', $partnership->id) }}"
                            class="block w-full px-4 py-2 bg-primary text-white text-center text-sm rounded-md hover:bg-blue-700 transition-colors duration-200 font-medium">
                            Edit Proposal
                        </a>
                    @endif

                    @if($partnership->status === 'approved')
                        <form method="POST" action="{{ route('partner.partnerships.complete', $partnership->id) }}" class="w-full">
                            @csrf
                            <button type="submit"
                                onclick="return confirm('Are you sure you want to mark this partnership as complete?')"
                                class="w-full px-4 py-2 bg-green-600 text-white text-sm rounded-md hover:bg-green-700 transition-colors duration-200 font-medium">
                                Mark as Complete
                            </button>
                        </form>
                    @endif

                    @if(in_array($partnership->status, ['submitted', 'rejected']))
                        <button onclick="openDeleteModal()"
                            class="w-full px-4 py-2 bg-red-600 text-white text-sm rounded-md hover:bg-red-700 transition-colors duration-200 font-medium">
                            Delete Proposal
                        </button>
                    @endif

                    <a href="{{ route('partner.partnerships.index') }}"
                        class="block w-full px-4 py-2 bg-gray-200 text-gray-700 text-center text-sm rounded-md hover:bg-gray-300 transition-colors duration-200 font-medium">
                        Back to Partnerships
                    </a>
                </div>
            </div>

            <!-- Organization Details -->
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Organization Details</h2>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">Organization Name</p>
                        <p class="text-sm text-gray-900">{{ $partnership->organization_name }}</p>
                    </div>
                    @if($partnership->organization_website)
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">Website</p>
                            <a href="{{ $partnership->organization_website }}" target="_blank" rel="noopener"
                                class="text-sm text-primary hover:underline break-all">
                                {{ $partnership->organization_website }}
                            </a>
                        </div>
                    @endif
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">Organization Phone</p>
                        <p class="text-sm text-gray-900">{{ $partnership->organization_phone }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">Contact Person</p>
                        <p class="text-sm text-gray-900">{{ $partnership->contact_name }}</p>
                        <p class="text-xs text-gray-600">{{ $partnership->contact_position }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">Contact Email</p>
                        <p class="text-sm text-primary break-all">{{ $partnership->contact_email }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase tracking-wide mb-1">Contact Phone</p>
                        <p class="text-sm text-gray-900">{{ $partnership->contact_phone }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <div class="relative bg-white rounded-lg max-w-md w-full shadow-xl">
            <div class="px-6 py-6">
                <div class="flex items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 text-center">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Delete Partnership Proposal</h3>
                    <p class="text-sm text-gray-500">
                        Are you sure you want to delete this partnership proposal? This action cannot be undone.
                    </p>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                <button onclick="closeDeleteModal()"
                    class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 font-medium transition-colors duration-200">
                    Cancel
                </button>
                <form method="POST" action="{{ route('partner.partnerships.destroy', $partnership->id) }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 font-medium transition-colors duration-200">
                        Delete Proposal
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openDeleteModal() {
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }

    // Close modal on escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeDeleteModal();
        }
    });

    // Close modal when clicking outside
    document.addEventListener('click', function(event) {
        const modal = document.getElementById('deleteModal');
        if (event.target === modal) {
            closeDeleteModal();
        }
    });
</script>
@endsection
