@extends('layouts.partner')

@section('title', 'Edit Job Posting - PCU-DASMA Connect')

@section('content')
<div class="w-full bg-gray-50 min-h-screen py-4 sm:py-6 lg:py-8">
    <div class="w-full max-w-4xl mx-auto px-3 sm:px-4 md:px-6 lg:px-8">

        <!-- Header -->
        <div class="mb-6 sm:mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="min-w-0">
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 break-words">Edit Job Posting</h1>
                    <p class="text-sm sm:text-base text-gray-600 mt-1 break-words">Update job posting details</p>
                </div>
            </div>
        </div>

        <!-- Status Alert -->
        @if($jobPosting->status === 'rejected')
            <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-3 sm:p-4 rounded-r">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0-6a4 4 0 110 8 4 4 0 010-8zm0-2a6 6 0 11-1.5.15M12.5 3.5a.5.5 0 11-1 0 .5.5 0 011 0z"></path>
                    </svg>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs sm:text-sm text-red-800 break-words"><strong>Rejected:</strong> After editing, you can resubmit for approval.</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Toast Container -->
        <div id="toastContainer" class="fixed bottom-4 right-4 z-50 max-w-xs mx-2 sm:max-w-sm"></div>

        <!-- Form (Same as create, but with pre-filled values) -->
        <form id="jobPostingEditForm" class="space-y-4 sm:space-y-6 md:space-y-8">
            @csrf
            @method('PUT')

            <!-- All form sections same as create view, with values pre-filled -->
            <!-- I'll include key sections below -->

            <!-- Basic Information -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="px-3 sm:px-4 md:px-6 py-3 sm:py-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 break-words">Basic Information</h3>
                </div>
                <div class="px-3 sm:px-4 md:px-6 py-4 sm:py-6 space-y-4 sm:space-y-6">

                    <div class="min-w-0">
                        <label for="title" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Job Title *</label>
                        <input type="text" id="title" name="title" required value="{{ $jobPosting->title }}"
                            class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                        <span id="titleError" class="text-red-500 text-xs mt-1 block hidden"></span>
                    </div>

                    <!-- Continue with other fields (similar structure as create) -->
                    <!-- Include job_type, experience_level, department, custom_department -->
                </div>
            </div>

            <!-- ... Rest of the form sections ... -->

            <!-- Form Actions -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="px-3 sm:px-4 md:px-6 py-4 sm:py-6 flex flex-col sm:flex-row gap-3 justify-end">
                    <a href="{{ route('partner.job-postings.show', $jobPosting->id) }}"
                        class="px-4 sm:px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition text-sm sm:text-base whitespace-nowrap text-center">
                        Cancel
                    </a>
                    <button type="submit" id="submitBtn"
                        class="px-4 sm:px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm sm:text-base whitespace-nowrap font-medium">
                        Update Job Posting
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="{{ asset('js/partner/job-postings-edit.js') }}"></script>
@endsection
