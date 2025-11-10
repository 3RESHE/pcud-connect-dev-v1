@extends('layouts.partner')

@section('content')
    <div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
        <!-- Back Link -->
        <a href="javascript:history.back()" class="inline-flex items-center text-sm text-blue-600 hover:text-blue-800 mb-6">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Job Info Card -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Job Information</h2>
                    <div class="space-y-4">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Position</p>
                            <p class="text-lg text-gray-900 font-semibold">{{ $jobPosting->title }}</p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Company</p>
                            <p class="text-lg text-gray-900">{{ $jobPosting->partnerProfile->company_name }}</p>
                        </div>
                        <div class="pt-3 border-t">
                            <a href="{{ route('partner.job-postings.applications', $jobPosting) }}"
                                class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                ← Back to all applications
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Applicant Info Card -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Applicant Information</h2>
                    <div class="space-y-4">
                        <!-- Profile Header -->
                        <div class="flex flex-col sm:flex-row items-start gap-4 pb-4 border-b">
                            <div
                                class="h-16 w-16 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-2xl font-bold flex-shrink-0">
                                {{ substr($applicant->name, 0, 1) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-bold text-gray-900 break-words">
                                    {{ $applicant->name }}</h3>
                                <p class="text-gray-600 text-sm break-words">{{ $applicant->email }}</p>
                                <div class="mt-2">
                                    <span
                                        class="inline-block px-3 py-1 text-xs font-semibold rounded-full
                                    @if ($application->applicant_type === 'student') bg-blue-100 text-blue-800
                                    @else
                                        bg-purple-100 text-purple-800 @endif">
                                        {{ ucfirst($application->applicant_type) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Department -->
                        @if ($applicantProfile && $applicantProfile->department)
                            <div>
                                <p class="text-sm font-medium text-gray-500">Department</p>
                                <p class="text-gray-900 break-words">
                                    {{ $applicantProfile->department->title }}</p>
                            </div>
                        @endif

                        <!-- Bio -->
                        @if ($applicantProfile && $applicantProfile->bio)
                            <div>
                                <p class="text-sm font-medium text-gray-500 mb-2">Bio</p>
                                <p class="text-gray-700 text-sm whitespace-pre-wrap break-words">
                                    {{ $applicantProfile->bio }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Cover Letter Card -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Cover Letter</h2>
                    <div class="bg-gray-50 p-4 rounded-lg overflow-auto max-h-96">
                        <p class="text-gray-700 text-sm whitespace-pre-wrap break-words">
                            {{ $application->cover_letter }}</p>
                    </div>
                </div>

                <!-- Timeline Card -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Timeline</h2>
                    <div class="space-y-4">
                        <div class="flex gap-4">
                            <div class="w-2 h-2 bg-blue-500 rounded-full mt-2 flex-shrink-0"></div>
                            <div class="min-w-0">
                                <p class="text-xs font-medium text-gray-500 uppercase">Applied</p>
                                <p class="text-sm text-gray-900">
                                    {{ $application->created_at->format('M d, Y H:i A') }}</p>
                            </div>
                        </div>

                        @if ($application->reviewed_at)
                            <div class="flex gap-4">
                                <div class="w-2 h-2 bg-purple-500 rounded-full mt-2 flex-shrink-0"></div>
                                <div class="min-w-0">
                                    <p class="text-xs font-medium text-gray-500 uppercase">Reviewed</p>
                                    <p class="text-sm text-gray-900">
                                        {{ $application->reviewed_at->format('M d, Y H:i A') }}</p>
                                </div>
                            </div>
                        @endif

                        @if ($application->last_contacted_at)
                            <div class="flex gap-4">
                                <div class="w-2 h-2 bg-green-500 rounded-full mt-2 flex-shrink-0"></div>
                                <div class="min-w-0">
                                    <p class="text-xs font-medium text-gray-500 uppercase">Last Contacted</p>
                                    <p class="text-sm text-gray-900">
                                        {{ $application->last_contacted_at->format('M d, Y H:i A') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Status Card -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Status</h3>

                    <div class="mb-6 p-4 bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg text-center">
                        <p
                            class="text-4xl font-bold break-words
                        @if ($application->status === 'pending') text-yellow-600
                        @elseif($application->status === 'contacted')
                            text-purple-600
                        @elseif($application->status === 'approved')
                            text-green-600
                        @elseif($application->status === 'rejected')
                            text-red-600 @endif">
                            {{ ucfirst(str_replace('_', ' ', $application->status)) }}
                        </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-2">
                        {{-- Approve Button - Only show if pending or contacted --}}
                        @if ($application->status === 'pending' || $application->status === 'contacted')
                            <button type="button"
                                class="w-full approve-btn px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition flex items-center justify-center gap-2"
                                data-application-id="{{ $application->id }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Approve
                            </button>

                            <button type="button"
                                class="w-full reject-btn px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition flex items-center justify-center gap-2"
                                data-application-id="{{ $application->id }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Reject
                            </button>
                        @endif

                        {{-- Send Email Button - Only show if NOT approved or rejected --}}
                        @if ($application->status !== 'approved' && $application->status !== 'rejected')
                            <button type="button"
                                class="w-full contact-btn px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition flex items-center justify-center gap-2"
                                data-application-id="{{ $application->id }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                    </path>
                                </svg>
                                Send Email
                            </button>
                        @endif

                        {{-- Download Resume - Always visible --}}
                        <a href="{{ route('partner.applications.download-resume', $application) }}"
                            class="w-full block px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition text-center">
                            Download Resume
                        </a>
                    </div>
                </div>

                <!-- Quick Info Card -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Quick Info</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Application ID</p>
                            <p class="text-sm font-mono text-gray-900 break-all">
                                {{ str_pad($application->id, 8, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <div class="pt-3 border-t">
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Applicant Type</p>
                            <p class="text-sm text-gray-900 capitalize">
                                {{ $application->applicant_type }}</p>
                        </div>
                        <div class="pt-3 border-t">
                            <p class="text-xs text-gray-500 uppercase tracking-wider">Applied</p>
                            <p class="text-sm text-gray-900">
                                {{ $application->created_at->diffForHumans() }}</p>
                        </div>
                        @if ($application->rejection_reason)
                            <div class="pt-3 border-t">
                                <p class="text-xs text-gray-500 uppercase tracking-wider">Rejection Reason</p>
                                <p class="text-sm text-gray-700 mt-1 break-words">
                                    {{ $application->rejection_reason }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Modal -->
    @include('users.partner.job-postings.contact-modal')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Contact button
            document.querySelector('.contact-btn')?.addEventListener('click', function() {
                openContactModal(this.dataset.applicationId);
            });

            // Approve button
            document.querySelector('.approve-btn')?.addEventListener('click', function() {
                approveApplication(this.dataset.applicationId);
            });

            // Reject button
            document.querySelector('.reject-btn')?.addEventListener('click', function() {
                rejectApplication(this.dataset.applicationId);
            });
        });

        function openContactModal(applicationId) {
            const modal = document.getElementById('contactModal');
            const applicationIdInput = document.getElementById('applicationId');

            console.log('Opening modal for application:', applicationId);

            if (applicationIdInput) {
                applicationIdInput.value = applicationId;
            }

            modal.classList.remove('hidden');
        }


        function approveApplication(applicationId) {
            if (!confirm('Approve this application?')) return;

            fetch(`/partner/applications/${applicationId}/approve`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('✓ Application approved!', 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showToast('❌ ' + data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('❌ Failed to approve application', 'error');
                });
        }

        function rejectApplication(applicationId) {
            const reason = prompt('Rejection reason (optional):');
            if (reason === null) return;

            fetch(`/partner/applications/${applicationId}/reject`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        rejection_reason: reason
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('✓ Application rejected!', 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showToast('❌ ' + data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('❌ Failed to reject application', 'error');
                });
        }

        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg text-white z-50 shadow-lg animate-pulse
            ${type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500'}`;
            toast.textContent = message;
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transition = 'opacity 0.3s ease-out';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }
    </script>
@endsection
