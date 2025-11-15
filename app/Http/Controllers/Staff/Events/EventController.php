<?php

namespace App\Http\Controllers\Staff\Events;

use App\Models\Event;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

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
            'published_events' => Event::where('created_by', $staff->id)->where('status', 'published')->count(),
            'pending_events' => Event::where('created_by', $staff->id)->where('status', 'pending')->count(),
            'approved_events' => Event::where('created_by', $staff->id)->where('status', 'approved')->count(),
            'ongoing_events' => Event::where('created_by', $staff->id)->where('status', 'ongoing')->count(),
            'rejected_events' => Event::where('created_by', $staff->id)->where('status', 'rejected')->count(),
            'completed_events' => Event::where('created_by', $staff->id)->where('status', 'completed')->count(),
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
            ]);

            $action = $request->input('action', 'draft');

            $is_multiday = $request->has('is_multiday') ? 1 : 0;
            $registration_required = $request->has('registration_required') ? 1 : 0;
            $walkin_allowed = $request->has('walkin_allowed') ? 1 : 0;

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

            $event = Event::create($eventData);

            // ✅ LOG ACTIVITY
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'created',
                'subject_type' => Event::class,
                'subject_id' => $event->id,
                'description' => "Created event: {$event->title}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            if ($action === 'submit') {
                return redirect()->route('staff.events.show', $event->id)
                    ->with('success', 'Event created and submitted for admin review!');
            } else {
                return redirect()->route('staff.events.show', $event->id)
                    ->with('success', 'Event saved as draft. You can publish it later.');
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            \Log::error('Event creation error: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Failed to create event: ' . $e->getMessage()])->withInput();
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
     * Update event - Status automatically changes to 'pending' when updated
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
                'start_time' => 'required',
                'end_time' => 'required|after:start_time',
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
            ]);

            $is_multiday = $request->has('is_multiday') ? 1 : 0;
            $registration_required = $request->has('registration_required') ? 1 : 0;
            $walkin_allowed = $request->has('walkin_allowed') ? 1 : 0;

            $start_time = is_string($validated['start_time']) ? $validated['start_time'] : $validated['start_time']->format('H:i');
            $end_time = is_string($validated['end_time']) ? $validated['end_time'] : $validated['end_time']->format('H:i');

            // Store previous status for logging
            $previousStatus = $event->status;

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
                'status' => 'pending', // ✅ AUTOMATICALLY CHANGE TO PENDING
                'approved_by' => null, // ✅ CLEAR PREVIOUS APPROVAL
            ]);

            if ($request->hasFile('event_image')) {
                $this->updateEventImage($event, $request->file('event_image'));
            }

            // ✅ LOG ACTIVITY - Include status change details
            $statusMessage = $previousStatus !== 'pending' ? " (Changed from '{$previousStatus}' to 'pending')" : '';
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'updated',
                'subject_type' => Event::class,
                'subject_id' => $event->id,
                'description' => "Updated event: {$event->title}{$statusMessage}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()->route('staff.events.show', $event->id)
                ->with('success', 'Event updated successfully! Status has been changed to pending and requires admin review.');
        } catch (\Exception $e) {
            \Log::error('Event update error: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Failed to update event'])->withInput();
        }
    }

    /**
     * Delete event
     */
    public function destroy(Event $event)
    {
        $this->authorizeEventOwner($event);

        if (!in_array($event->status, ['draft', 'pending', 'rejected'])) {
            return redirect()->back()->with('error', 'Only draft, pending, or rejected events can be deleted.');
        }

        $eventTitle = $event->title; // Save title before deletion

        if ($event->event_image && Storage::disk('public')->exists($event->event_image)) {
            Storage::disk('public')->delete($event->event_image);
        }

        $event->delete();

        // ✅ LOG ACTIVITY
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'deleted',
            'subject_type' => Event::class,
            'subject_id' => $event->id,
            'description' => "Deleted event: {$eventTitle}",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('staff.events.index')->with('success', 'Event deleted successfully!');
    }

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

        // ✅ LOG ACTIVITY
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'published',
            'subject_type' => Event::class,
            'subject_id' => $event->id,
            'description' => "Published event: {$event->title}",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
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

        // ✅ LOG ACTIVITY
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'updated',
            'subject_type' => Event::class,
            'subject_id' => $event->id,
            'description' => "Marked event as ongoing: {$event->title}",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

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

        // ✅ LOG ACTIVITY
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'completed',
            'subject_type' => Event::class,
            'subject_id' => $event->id,
            'description' => "Marked event as completed: {$event->title}",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->back()->with('success', 'Event marked as completed!');
    }

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
            if ($event->event_image && Storage::disk('public')->exists($event->event_image)) {
                Storage::disk('public')->delete($event->event_image);
            }

            $filename = time() . '_' . uniqid() . '.' . $imageFile->getClientOriginalExtension();
            $path = $imageFile->storeAs('events', $filename, 'public');
            $event->update(['event_image' => 'storage/' . $path]);
        } catch (\Exception $e) {
            \Log::error('Event image update failed: ' . $e->getMessage());
        }
    }
}
