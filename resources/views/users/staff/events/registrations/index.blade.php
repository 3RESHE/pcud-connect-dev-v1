@extends('layouts.staff')

@section('title', 'Manage Registrations - ' . $event->title)

@section('content')
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('staff.events.show', $event->id) }}"
            class="inline-flex items-center text-sm text-gray-600 hover:text-primary">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Event Details
        </a>
    </div>

    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Manage Registrations</h1>
        <p class="text-gray-600">{{ $event->title }}</p>
        <p class="text-sm text-gray-500 mt-1">
            @if($event->is_multiday && $event->end_date)
                {{ \Carbon\Carbon::parse($event->event_date)->format('F d, Y') }} - {{ \Carbon\Carbon::parse($event->end_date)->format('F d, Y') }}
            @else
                {{ \Carbon\Carbon::parse($event->event_date)->format('F d, Y') }}
            @endif
        </p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Total Registrations -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Total Registrations</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $registrations->total() }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Event Format -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Event Format</p>
                    <p class="text-lg font-bold text-gray-900">{{ ucfirst(str_replace('_', ' ', $event->event_format)) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Event Status -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Event Status</p>
                    <p class="text-lg font-bold text-gray-900 capitalize">{{ $event->status }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Max Capacity -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Max Capacity</p>
                    <p class="text-3xl font-bold text-gray-900">{{ $event->max_attendees ?? 'Unlimited' }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0a6 6 0 11-12 0 6 6 0 0112 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters & Actions -->
    <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <!-- Search -->
            <div class="flex-1">
                <div class="relative">
                    <input type="text" id="searchInput" onkeyup="searchRegistrations()"
                        placeholder="Search by name or email..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3">
                <a href="{{ route('staff.events.registrations.export', $event->id) }}"
                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium flex items-center transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    Export CSV
                </a>
                <button onclick="openEmailModal()"
                    class="px-4 py-2 bg-primary hover:bg-blue-700 text-white rounded-lg font-medium flex items-center transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                        </path>
                    </svg>
                    Send Email
                </button>
            </div>
        </div>
    </div>

    <!-- Registrations Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left">
                            <input type="checkbox" id="selectAll" onclick="toggleSelectAll()"
                                class="w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary">
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Participant Info
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            User Type
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Registration Date
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($registrations as $registration)
                        <tr class="registration-row hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <input type="checkbox" class="row-checkbox w-4 h-4 text-primary border-gray-300 rounded focus:ring-primary"
                                    data-email="{{ $registration->user->email }}"
                                    data-name="{{ $registration->user->first_name }} {{ $registration->user->last_name }}">
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    {{-- Profile Photo with Fallback --}}
                                    @php
                                        $userProfile = null;
                                        if ($registration->user->role === 'student') {
                                            $userProfile = $registration->user->studentProfile;
                                        } elseif ($registration->user->role === 'alumni') {
                                            $userProfile = $registration->user->alumniProfile;
                                        }
                                    @endphp

                                    @if($userProfile && $userProfile->profile_photo)
                                        <img src="{{ asset('storage/' . $userProfile->profile_photo) }}"
                                             alt="{{ $registration->user->first_name }} {{ $registration->user->last_name }}"
                                             class="w-10 h-10 rounded-full object-cover mr-3 border-2 border-gray-200">
                                    @else
                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                            <span class="text-blue-600 font-semibold text-sm">
                                                {{ strtoupper(substr($registration->user->first_name, 0, 1)) }}{{ strtoupper(substr($registration->user->last_name, 0, 1)) }}
                                            </span>
                                        </div>
                                    @endif

                                    <div>
                                        <p class="font-semibold text-gray-900">
                                            {{ $registration->user->first_name }} {{ $registration->user->last_name }}
                                        </p>
                                        <!-- ✅ Display Student/Alumni ID -->
                                        <p class="text-sm text-gray-500">
                                            @if($registration->user->role === 'student' && $registration->user->studentProfile)
                                                {{ $registration->user->studentProfile->student_id }}
                                            @elseif($registration->user->role === 'alumni')
                                                Alumni
                                            @else
                                                N/A
                                            @endif
                                        </p>
                                        <p class="text-xs text-gray-400">{{ $registration->user->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-xs font-medium rounded-full
                                    @if($registration->user->role === 'student') bg-blue-100 text-blue-800
                                    @elseif($registration->user->role === 'alumni') bg-amber-100 text-amber-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($registration->user->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $registration->created_at->format('M d, Y') }}
                                <span class="text-gray-400">{{ $registration->created_at->format('h:i A') }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <p class="text-gray-500 text-lg font-medium">No registrations found</p>
                                    <p class="text-gray-400 text-sm">Registrations will appear here when users register for your event</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($registrations->hasPages())
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                {{ $registrations->links() }}
            </div>
        @endif
    </div>

    <!-- Email Modal -->
    <div id="emailModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center rounded-t-xl">
                <h2 class="text-xl font-bold text-gray-900">Send Email to Selected Participants</h2>
                <button onclick="closeEmailModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <form action="{{ route('staff.events.registrations.send-email', $event->id) }}" method="POST" class="p-6">
                @csrf
                <div class="space-y-4">
                    <!-- Recipients -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Recipients</label>
                        <textarea id="recipientsField" readonly rows="2"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-700 text-sm"></textarea>
                        <input type="hidden" name="recipients" id="recipientsInput">
                        <p class="text-xs text-gray-500 mt-1">Email addresses of selected participants</p>
                    </div>

                    <!-- Email Template -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Template</label>
                        <select id="emailTemplate" onchange="loadTemplate()"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            <option value="">-- Select Template --</option>
                            <option value="reminder">Event Reminder</option>
                            <option value="update">Event Update</option>
                            <option value="link">Meeting Link</option>
                            <option value="venue_change">Venue Change</option>
                            <option value="cancellation">Event Cancellation</option>
                            <option value="custom">Custom Message</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Select a pre-built template or choose custom</p>
                    </div>

                    <!-- Subject -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Subject *</label>
                        <input type="text" name="subject" id="emailSubject" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="Enter email subject">
                    </div>

                    <!-- Message -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Message *</label>
                        <textarea name="message" id="emailMessage" rows="8" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="Type your message here..."></textarea>
                        <p class="text-xs text-gray-500 mt-1">Compose your message to all selected participants</p>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end gap-3 pt-4 border-t">
                        <button type="button" onclick="closeEmailModal()"
                            class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-6 py-2 bg-primary text-white rounded-lg hover:bg-blue-700 flex items-center transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                            Send Email
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        const eventTitle = @json($event->title);
        const eventDate = @json(\Carbon\Carbon::parse($event->event_date)->format('F d, Y'));
        const eventTime = @json(\Carbon\Carbon::parse($event->start_time)->format('g:i A'));
        const eventVenue = @json($event->venue_name ?? 'TBA');
        const eventFormat = @json($event->event_format);

        const templates = {
            reminder: {
                subject: `Reminder: ${eventTitle} - ${eventDate}`,
                message: `Dear Participant,\n\nThis is a friendly reminder about your upcoming event registration:\n\nEvent: ${eventTitle}\nDate: ${eventDate}\nTime: ${eventTime}\n${eventFormat !== 'online' ? `Venue: ${eventVenue}\n` : ''}\nPlease make sure to mark your calendar and prepare accordingly. We're looking forward to seeing you there!\n\nIf you have any questions, feel free to reach out.\n\nBest regards,\n${@json(config('app.name'))}`
            },
            update: {
                subject: `Important Update: ${eventTitle}`,
                message: `Dear Participant,\n\nWe have an important update regarding the event you're registered for:\n\nEvent: ${eventTitle}\nDate: ${eventDate}\n\n[Please add your update information here]\n\nThank you for your understanding.\n\nBest regards,\n${@json(config('app.name'))}`
            },
            link: {
                subject: `Meeting Link: ${eventTitle}`,
                message: `Dear Participant,\n\nHere is the meeting link for the upcoming event:\n\nEvent: ${eventTitle}\nDate: ${eventDate}\nTime: ${eventTime}\n\nMeeting Link: [INSERT MEETING LINK HERE]\nMeeting ID: [INSERT MEETING ID]\nPasscode: [INSERT PASSCODE]\n\nPlease join on time. We look forward to your participation!\n\nBest regards,\n${@json(config('app.name'))}`
            },
            venue_change: {
                subject: `Venue Change: ${eventTitle}`,
                message: `Dear Participant,\n\nPlease note that the venue for the following event has been changed:\n\nEvent: ${eventTitle}\nDate: ${eventDate}\nTime: ${eventTime}\n\nNEW Venue: [INSERT NEW VENUE HERE]\nPrevious Venue: ${eventVenue}\n\nPlease take note of this change. We apologize for any inconvenience.\n\nBest regards,\n${@json(config('app.name'))}`
            },
            cancellation: {
                subject: `Event Cancellation: ${eventTitle}`,
                message: `Dear Participant,\n\nWe regret to inform you that the following event has been cancelled:\n\nEvent: ${eventTitle}\nScheduled Date: ${eventDate}\n\nReason: [INSERT REASON HERE]\n\nWe sincerely apologize for any inconvenience this may cause. We will notify you of any rescheduled dates.\n\nThank you for your understanding.\n\nBest regards,\n${@json(config('app.name'))}`
            },
            custom: {
                subject: '',
                message: ''
            }
        };

        function loadTemplate() {
            const template = document.getElementById('emailTemplate').value;
            if (template && templates[template]) {
                document.getElementById('emailSubject').value = templates[template].subject;
                document.getElementById('emailMessage').value = templates[template].message;
            }
        }

        function toggleSelectAll() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.row-checkbox');
            checkboxes.forEach((checkbox) => {
                checkbox.checked = selectAll.checked;
            });
        }

        function searchRegistrations() {
            const input = document.getElementById('searchInput').value.toLowerCase();
            const rows = document.querySelectorAll('.registration-row');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(input) ? '' : 'none';
            });
        }

        function openEmailModal() {
            const checkedBoxes = Array.from(document.querySelectorAll('.row-checkbox:checked'));

            if (checkedBoxes.length === 0) {
                alert('Please select at least one participant to send email.');
                return;
            }

            const emails = checkedBoxes.map(cb => cb.getAttribute('data-email'));

            document.getElementById('recipientsField').value = emails.join(', ');
            document.getElementById('recipientsInput').value = JSON.stringify(emails);
            document.getElementById('emailModal').classList.remove('hidden');
        }

        function closeEmailModal() {
            document.getElementById('emailModal').classList.add('hidden');
            document.getElementById('emailTemplate').value = '';
            document.getElementById('emailSubject').value = '';
            document.getElementById('emailMessage').value = '';
        }

        // Close modal when clicking outside
        document.getElementById('emailModal').addEventListener('click', function(event) {
            if (event.target === this) {
                closeEmailModal();
            }
        });
    </script>
@endsection
