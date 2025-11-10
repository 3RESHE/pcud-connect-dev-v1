<?php

namespace App\Http\Controllers\Staff\Events;

use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class EventController extends Controller
{
    /**
     * Display all events for staff member
     */
    public function index()
    {
        $staff = auth()->user();

        $events = Event::where('created_by', $staff->id)
            ->latest('created_at')
            ->paginate(15);

        $data = [
            'events' => $events,
            'total_events' => Event::where('created_by', $staff->id)->count(),
            'published_events' => Event::where('created_by', $staff->id)
                ->where('status', 'published')
                ->count(),
            'pending_events' => Event::where('created_by', $staff->id)
                ->where('status', 'pending')
                ->count(),
            'approved_events' => Event::where('created_by', $staff->id)
                ->where('status', 'approved')
                ->count(),
            'ongoing_events' => Event::where('created_by', $staff->id)
                ->where('status', 'ongoing')
                ->count(),
            'rejected_events' => Event::where('created_by', $staff->id)
                ->where('status', 'rejected')
                ->count(),
            'completed_events' => Event::where('created_by', $staff->id)
                ->where('status', 'completed')
                ->count(),
        ];

        return view('users.staff.events.index', $data);
    }

    /**
     * Show the create event form
     */
    public function create()
    {
        return view('users.staff.events.create');
    }

    /**
     * Store event in database
     */
    public function store(Request $request)
    {
        try {
            // VALIDATION RULES
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string|min:10',
                'event_format' => 'required|in:inperson,virtual,hybrid',
                'event_date' => 'required|date|after_or_equal:today',
                'is_multiday' => 'nullable|boolean',
                'end_date' => 'nullable|date|after_or_equal:event_date',
                'start_time' => 'required|date_format:H:i',
                'end_time' => 'required|date_format:H:i|after:start_time',
                'venue_name' => 'required_if:event_format,inperson|nullable|string|max:255',
                'venue_capacity' => 'required_if:event_format,inperson|nullable|integer|min:1',
                'venue_address' => 'nullable|string',
                'platform' => 'required_if:event_format,virtual,hybrid|nullable|string',
                'custom_platform' => 'required_if:platform,other|nullable|string|max:255',
                'virtual_capacity' => 'nullable|integer|min:1',
                'meeting_link' => 'nullable|url',
                'registration_required' => 'nullable|boolean',
                'walkin_allowed' => 'nullable|boolean',
                'registration_deadline' => 'nullable|date|before_or_equal:event_date',
                'max_attendees' => 'nullable|integer|min:1',
                'target_audience' => 'required|in:allstudents,alumni,openforall',
                'event_tags' => 'nullable|string|max:500',
                'contact_person' => 'required|string|max:255',
                'contact_email' => 'required|email',
                'contact_phone' => 'nullable|string|max:20',
                'special_instructions' => 'nullable|string',
                'event_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            ], [
                'venue_name.required_if' => 'Venue Name is required for in-person events.',
                'venue_capacity.required_if' => 'Venue Capacity is required for in-person events.',
                'platform.required_if' => 'Platform is required for virtual/hybrid events.',
                'custom_platform.required_if' => 'Custom Platform name is required when selecting "Other".',
                'end_time.after' => 'End time must be after start time.',
                'end_date.after_or_equal' => 'End date must be on or after event date.',
                'registration_deadline.before_or_equal' => 'Registration deadline cannot be after event date.',
            ]);

            $action = $request->input('action', 'draft');

            // Convert checkboxes to boolean (0 or 1)
            $is_multiday = $request->has('is_multiday') ? 1 : 0;
            $registration_required = $request->has('registration_required') ? 1 : 0;
            $walkin_allowed = $request->has('walkin_allowed') ? 1 : 0;

            // Prepare event data
            $eventData = [
                'created_by' => auth()->id(),
                'title' => $validated['title'],
                'description' => $validated['description'],
                'event_format' => $validated['event_format'],
                'event_date' => $validated['event_date'],
                'is_multiday' => $is_multiday,
                'end_date' => $validated['end_date'] ?? null,
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'venue_name' => $validated['venue_name'] ?? null,
                'venue_capacity' => $validated['venue_capacity'] ?? null,
                'venue_address' => $validated['venue_address'] ?? null,
                'platform' => $validated['platform'] ?? null,
                'custom_platform' => $validated['custom_platform'] ?? null,
                'virtual_capacity' => $validated['virtual_capacity'] ?? null,
                'meeting_link' => $validated['meeting_link'] ?? null,
                'registration_required' => $registration_required,
                'walkin_allowed' => $walkin_allowed,
                'registration_deadline' => $validated['registration_deadline'] ?? null,
                'max_attendees' => $validated['max_attendees'] ?? null,
                'target_audience' => $validated['target_audience'],
                'event_tags' => $validated['event_tags'] ?? null,
                'contact_person' => $validated['contact_person'],
                'contact_email' => $validated['contact_email'],
                'contact_phone' => $validated['contact_phone'] ?? null,
                'special_instructions' => $validated['special_instructions'] ?? null,
                'status' => $action === 'submit' ? 'pending' : 'draft',
            ];

            // Handle image upload
            if ($request->hasFile('event_image')) {
                try {
                    $file = $request->file('event_image');
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('events', $filename, 'public');
                    $eventData['event_image'] = 'storage/' . $path;
                } catch (\Exception $e) {
                    \Log::error('Event image upload failed: ' . $e->getMessage());
                    return redirect()->back()
                        ->withErrors(['event_image' => 'Failed to upload image. Please try again.'])
                        ->withInput();
                }
            }

            // Create the event
            $event = Event::create($eventData);

            // Redirect based on action
            if ($action === 'submit') {
                return redirect()->route('staff.events.show', $event->id)
                    ->with('success', 'Event created and submitted for admin review!');
            } else {
                return redirect()->route('staff.events.show', $event->id)
                    ->with('success', 'Event saved as draft. You can publish it later.');
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            \Log::error('Event creation error: ' . $e->getMessage());
            return redirect()->back()
                ->withErrors(['error' => 'Failed to create event: ' . $e->getMessage()])
                ->withInput();
        }
    }

    /**
     * Display a specific event
     */
    public function show(Event $event)
    {
        $this->authorizeEventOwner($event);

        return view('users.staff.events.show', compact('event'));
    }

    /**
     * Edit event form
     */
    public function edit(Event $event)
    {
        $this->authorizeEventOwner($event);

        if (!in_array($event->status, ['draft', 'pending', 'rejected'])) {
            return redirect()->route('staff.events.show', $event->id)
                ->with('error', 'This event cannot be edited. Only draft, pending, or rejected events can be edited.');
        }

        return view('users.staff.events.edit', compact('event'));
    }

    /**
     * Update event
     */
    public function update(Request $request, Event $event)
    {
        try {
            $this->authorizeEventOwner($event);

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string|min:10',
                'event_format' => 'required|in:inperson,virtual,hybrid',
                'event_date' => 'required|date|after_or_equal:today',
                'is_multiday' => 'nullable|boolean',
                'end_date' => 'nullable|date|after_or_equal:event_date',
                'start_time' => 'required', // CHANGED: Remove date_format validation
                'end_time' => 'required|after:start_time', // CHANGED: Remove date_format validation
                'venue_name' => 'required_if:event_format,inperson|nullable|string|max:255',
                'venue_capacity' => 'required_if:event_format,inperson|nullable|integer|min:1',
                'venue_address' => 'nullable|string',
                'platform' => 'required_if:event_format,virtual,hybrid|nullable|string',
                'custom_platform' => 'required_if:platform,other|nullable|string|max:255',
                'virtual_capacity' => 'nullable|integer|min:1',
                'meeting_link' => 'nullable|url',
                'registration_required' => 'nullable|boolean',
                'walkin_allowed' => 'nullable|boolean',
                'registration_deadline' => 'nullable|date|before_or_equal:event_date',
                'max_attendees' => 'nullable|integer|min:1',
                'target_audience' => 'required|in:allstudents,alumni,openforall',
                'event_tags' => 'nullable|string|max:500',
                'contact_person' => 'required|string|max:255',
                'contact_email' => 'required|email',
                'contact_phone' => 'nullable|string|max:20',
                'special_instructions' => 'nullable|string',
                'event_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            ], [
                'venue_name.required_if' => 'Venue Name is required for in-person events.',
                'venue_capacity.required_if' => 'Venue Capacity is required for in-person events.',
                'platform.required_if' => 'Platform is required for virtual/hybrid events.',
                'custom_platform.required_if' => 'Custom Platform name is required when selecting "Other".',
                'end_time.after' => 'End time must be after start time.',
                'end_date.after_or_equal' => 'End date must be on or after event date.',
                'registration_deadline.before_or_equal' => 'Registration deadline cannot be after event date.',
            ]);

            // Convert checkboxes to boolean (0 or 1)
            $is_multiday = $request->has('is_multiday') ? 1 : 0;
            $registration_required = $request->has('registration_required') ? 1 : 0;
            $walkin_allowed = $request->has('walkin_allowed') ? 1 : 0;

            // Format time fields properly
            $start_time = is_string($validated['start_time']) ? $validated['start_time'] : $validated['start_time']->format('H:i');
            $end_time = is_string($validated['end_time']) ? $validated['end_time'] : $validated['end_time']->format('H:i');

            $event->update([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'event_format' => $validated['event_format'],
                'event_date' => $validated['event_date'],
                'is_multiday' => $is_multiday,
                'end_date' => $validated['end_date'] ?? null,
                'start_time' => $start_time,
                'end_time' => $end_time,
                'venue_name' => $validated['venue_name'] ?? null,
                'venue_capacity' => $validated['venue_capacity'] ?? null,
                'venue_address' => $validated['venue_address'] ?? null,
                'platform' => $validated['platform'] ?? null,
                'custom_platform' => $validated['custom_platform'] ?? null,
                'virtual_capacity' => $validated['virtual_capacity'] ?? null,
                'meeting_link' => $validated['meeting_link'] ?? null,
                'registration_required' => $registration_required,
                'walkin_allowed' => $walkin_allowed,
                'registration_deadline' => $validated['registration_deadline'] ?? null,
                'max_attendees' => $validated['max_attendees'] ?? null,
                'target_audience' => $validated['target_audience'],
                'event_tags' => $validated['event_tags'] ?? null,
                'contact_person' => $validated['contact_person'],
                'contact_email' => $validated['contact_email'],
                'contact_phone' => $validated['contact_phone'] ?? null,
                'special_instructions' => $validated['special_instructions'] ?? null,
            ]);

            // Handle image update
            if ($request->hasFile('event_image')) {
                $this->updateEventImage($event, $request->file('event_image'));
            }

            return redirect()->route('staff.events.show', $event->id)
                ->with('success', 'Event updated successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            \Log::error('Event update error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()
                ->withErrors(['error' => 'Failed to update event: ' . $e->getMessage()])
                ->withInput();
        }
    }



    /**
     * Delete event
     */
    public function destroy(Event $event)
    {
        $this->authorizeEventOwner($event);

        if (!in_array($event->status, ['draft', 'pending', 'rejected'])) {
            return redirect()->back()
                ->with('error', 'Only draft, pending, or rejected events can be deleted.');
        }

        if ($event->event_image && Storage::disk('public')->exists($event->event_image)) {
            Storage::disk('public')->delete($event->event_image);
        }

        $event->delete();

        return redirect()->route('staff.events.index')
            ->with('success', 'Event deleted successfully!');
    }

    // ===== STATUS CHANGE METHODS =====

    /**
     * Publish event (from approved status)
     */
    public function publish(Event $event)
    {
        $this->authorizeEventOwner($event);

        if ($event->status !== 'approved') {
            return redirect()->back()->with('error', 'Only approved events can be published.');
        }

        $event->update([
            'status' => 'published',
            'published_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Event published successfully!');
    }

    /**
     * Mark event as ongoing
     */
    public function markOngoing(Event $event)
    {
        $this->authorizeEventOwner($event);

        if ($event->status !== 'published') {
            return redirect()->back()->with('error', 'Only published events can be marked as ongoing.');
        }

        $event->update(['status' => 'ongoing']);

        return redirect()->back()->with('success', 'Event marked as ongoing!');
    }

    /**
     * Mark event as completed
     */
    public function markCompleted(Event $event)
    {
        $this->authorizeEventOwner($event);

        if ($event->status !== 'ongoing') {
            return redirect()->back()->with('error', 'Only ongoing events can be marked as completed.');
        }

        $event->update(['status' => 'completed']);

        return redirect()->back()->with('success', 'Event marked as completed!');
    }

    // ===== REGISTRATION MANAGEMENT METHODS =====

    /**
     * Manage registrations for an event
     */
    public function manageRegistrations(Event $event)
    {
        $this->authorizeEventOwner($event);

        $registrations = $event->registrations()
            ->with('user')
            ->latest('created_at')
            ->get();

        $totalRegistrations = $registrations->count();
        $confirmedCount = $registrations->where('status', 'confirmed')->count();
        $cancelledCount = $registrations->where('status', 'cancelled')->count();

        return view('users.staff.events.manage-registrations', compact(
            'event',
            'registrations',
            'totalRegistrations',
            'confirmedCount',
            'cancelledCount'
        ));
    }

    /**
     * View registration details
     */
    public function registrationDetails(Event $event, EventRegistration $registration)
    {
        $this->authorizeEventOwner($event);

        if ($registration->event_id !== $event->id) {
            abort(404, 'Registration not found');
        }

        return view('users.staff.events.registration-details', compact('event', 'registration'));
    }

    /**
     * Confirm registration
     */
    public function confirmRegistration(Event $event, EventRegistration $registration)
    {
        $this->authorizeEventOwner($event);

        if ($registration->event_id !== $event->id) {
            abort(404, 'Registration not found');
        }

        $registration->update(['status' => 'confirmed']);

        return redirect()->back()->with('success', 'Registration confirmed!');
    }

    /**
     * Cancel registration
     */
    public function cancelRegistration(Event $event, EventRegistration $registration)
    {
        $this->authorizeEventOwner($event);

        if ($registration->event_id !== $event->id) {
            abort(404, 'Registration not found');
        }

        $registration->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', 'Registration cancelled!');
    }

    // ===== ATTENDANCE MANAGEMENT METHODS =====

    /**
     * Manage attendance
     */
    public function manageAttendance(Event $event)
    {
        $this->authorizeEventOwner($event);

        if ($event->status !== 'ongoing') {
            return redirect()->back()->with('error', 'Can only manage attendance for ongoing events.');
        }

        $registrations = $event->registrations()
            ->with('user')
            ->where('status', 'confirmed')
            ->latest('created_at')
            ->get();

        return view('users.staff.events.manage-attendance', compact('event', 'registrations'));
    }

    /**
     * Check-in attendee
     */
    public function checkIn(Event $event, EventRegistration $registration)
    {
        $this->authorizeEventOwner($event);

        if ($registration->event_id !== $event->id) {
            abort(404, 'Registration not found');
        }

        $registration->update([
            'attendance_status' => 'attended',
            'checked_in_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Attendee checked in!');
    }

    /**
     * Check-out attendee
     */
    public function checkOut(Event $event, EventRegistration $registration)
    {
        $this->authorizeEventOwner($event);

        if ($registration->event_id !== $event->id) {
            abort(404, 'Registration not found');
        }

        $registration->update(['checked_out_at' => now()]);

        return redirect()->back()->with('success', 'Attendee checked out!');
    }

    // ===== STATISTICS & REPORTING METHODS =====

    /**
     * View event statistics
     */
    public function statistics(Event $event)
    {
        $this->authorizeEventOwner($event);

        $totalRegistrations = $event->registrations()->count();
        $confirmedRegistrations = $event->registrations()->where('status', 'confirmed')->count();
        $cancelledRegistrations = $event->registrations()->where('status', 'cancelled')->count();
        $attended = $event->registrations()->where('attendance_status', 'attended')->count();

        $data = [
            'event' => $event,
            'totalRegistrations' => $totalRegistrations,
            'confirmedRegistrations' => $confirmedRegistrations,
            'cancelledRegistrations' => $cancelledRegistrations,
            'attended' => $attended,
            'noShow' => $totalRegistrations - $attended,
            'attendanceRate' => $totalRegistrations > 0 ? round(($attended / $totalRegistrations) * 100, 2) : 0,
        ];

        return view('users.staff.events.statistics', $data);
    }

    /**
     * Download event report (CSV)
     */
    public function downloadReport(Event $event)
    {
        $this->authorizeEventOwner($event);

        $registrations = $event->registrations()->with('user')->get();

        $fileName = 'event-report-' . $event->id . '-' . now()->format('Y-m-d-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename=' . $fileName,
        ];

        $callback = function () use ($event, $registrations) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Event Report - ' . $event->title]);
            fputcsv($file, ['Generated on ' . now()->format('Y-m-d H:i:s')]);
            fputcsv($file, []);

            fputcsv($file, [
                'Name',
                'Email',
                'Phone',
                'Student ID',
                'Department',
                'Registration Status',
                'Attendance Status',
                'Registered On',
                'Checked In',
                'Checked Out',
            ]);

            foreach ($registrations as $registration) {
                fputcsv($file, [
                    $registration->user->name,
                    $registration->user->email,
                    $registration->user->phone ?? 'N/A',
                    $registration->user->student_id ?? 'N/A',
                    $registration->user->department ?? 'N/A',
                    ucfirst($registration->status),
                    ucfirst($registration->attendance_status ?? 'pending'),
                    $registration->created_at->format('Y-m-d H:i'),
                    $registration->checked_in_at?->format('Y-m-d H:i') ?? 'N/A',
                    $registration->checked_out_at?->format('Y-m-d H:i') ?? 'N/A',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export registrations (CSV)
     */
    public function exportRegistrations(Event $event)
    {
        return $this->downloadReport($event);
    }

    /**
     * Export attendance (CSV)
     */
    public function exportAttendance(Event $event)
    {
        $this->authorizeEventOwner($event);

        $registrations = $event->registrations()
            ->where('status', 'confirmed')
            ->with('user')
            ->get();

        $fileName = 'attendance-' . $event->id . '-' . now()->format('Y-m-d-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename=' . $fileName,
        ];

        $callback = function () use ($event, $registrations) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Attendance Report - ' . $event->title]);
            fputcsv($file, ['Event Date: ' . $event->event_date->format('Y-m-d')]);
            fputcsv($file, ['Generated on ' . now()->format('Y-m-d H:i:s')]);
            fputcsv($file, []);

            fputcsv($file, [
                'Name',
                'Email',
                'Student ID',
                'Status',
                'Checked In',
                'Checked Out',
            ]);

            foreach ($registrations as $registration) {
                fputcsv($file, [
                    $registration->user->name,
                    $registration->user->email,
                    $registration->user->student_id ?? 'N/A',
                    ucfirst($registration->attendance_status ?? 'absent'),
                    $registration->checked_in_at?->format('Y-m-d H:i') ?? 'No',
                    $registration->checked_out_at?->format('Y-m-d H:i') ?? 'No',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ===== HELPER METHODS =====

    /**
     * Authorize event owner
     */
    private function authorizeEventOwner(Event $event)
    {
        if ($event->created_by !== auth()->id()) {
            abort(403, 'Unauthorized action');
        }
    }

    /**
     * Update event image
     */
    private function updateEventImage(Event $event, $imageFile)
    {
        try {
            // Delete old image
            if ($event->event_image && Storage::disk('public')->exists($event->event_image)) {
                Storage::disk('public')->delete($event->event_image);
            }

            // Upload new image
            $filename = time() . '_' . uniqid() . '.' . $imageFile->getClientOriginalExtension();
            $path = $imageFile->storeAs('events', $filename, 'public');
            $event->update(['event_image' => 'storage/' . $path]);
        } catch (\Exception $e) {
            \Log::error('Event image update failed: ' . $e->getMessage());
        }
    }
}
