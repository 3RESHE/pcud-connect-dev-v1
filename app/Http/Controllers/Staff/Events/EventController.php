<?php

namespace App\Http\Controllers\Staff\Events;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

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
                ->where('status', 'under_review')
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
     * Show create form
     */
    public function create()
    {
        // Blank implementation - will implement later
        return view('users.staff.events.create');
    }

    /**
     * Store event
     */
    public function store(Request $request)
    {
        // Blank implementation - will implement later
        return redirect()->route('staff.events.index')
            ->with('success', 'Event created successfully!');
    }

    /**
     * Show event details
     */
    public function show(Event $event)
    {
        // Blank implementation - will implement later
        return view('users.staff.events.show', compact('event'));
    }

    /**
     * Show edit form
     */
    public function edit(Event $event)
    {
        // Blank implementation - will implement later
        return view('users.staff.events.edit', compact('event'));
    }

    /**
     * Update event
     */
    public function update(Request $request, Event $event)
    {
        // Blank implementation - will implement later
        return redirect()->route('staff.events.show', $event->id)
            ->with('success', 'Event updated successfully!');
    }

    /**
     * Publish event
     */
    public function publish(Event $event)
    {
        $event->update([
            'status' => 'published',
            'published_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Event published successfully!');
    }

    /**
     * Cancel event
     */
    public function cancel(Event $event)
    {
        $event->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', 'Event cancelled successfully!');
    }

    /**
     * View event registrations
     */
    public function registrations(Event $event)
    {
        // Blank implementation - will implement later
        return view('users.staff.events.registrations', compact('event'));
    }

    /**
     * Delete event
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->route('staff.events.index')
            ->with('success', 'Event deleted successfully!');
    }
}
