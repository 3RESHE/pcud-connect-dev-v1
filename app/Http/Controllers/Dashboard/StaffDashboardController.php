<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class StaffDashboardController extends Controller
{
    /**
     * Display staff dashboard
     */
    public function dashboard()
    {
        $staff = auth()->user();

        $data = [
            'total_events' => Event::where('created_by', $staff->id)->count(),
            'upcoming_events' => Event::where('created_by', $staff->id)
                ->where('status', 'published')
                ->where('event_date', '>=', now())
                ->count(),
            'completed_events' => Event::where('created_by', $staff->id)
                ->where('status', 'published')
                ->where('event_date', '<', now())
                ->count(),
            'total_registrations' => Event::where('created_by', $staff->id)
                ->withCount('registrations')
                ->get()
                ->sum('registrations_count'),
        ];

        return view('users.staff.dashboard', $data);
    }

    /**
     * Display list of events
     */
    public function events()
    {
        $staff = auth()->user();

        $events = Event::where('created_by', $staff->id)
            ->latest('created_at')
            ->paginate(10);

        $data = [
            'events' => $events,
            'total_events' => Event::where('created_by', $staff->id)->count(),
            'upcoming_events' => Event::where('created_by', $staff->id)
                ->where('status', 'published')
                ->where('event_date', '>=', now())
                ->count(),
            'completed_events' => Event::where('created_by', $staff->id)
                ->where('status', 'published')
                ->where('event_date', '<', now())
                ->count(),
            'total_registrations' => Event::where('created_by', $staff->id)
                ->withCount('registrations')
                ->get()
                ->sum('registrations_count'),
        ];

        return view('users.staff.events.index', $data);
    }

    /**
     * Show create event form
     */
    public function createEvent()
    {
        // Blank implementation - will implement later
        return view('users.staff.events.create');
    }

    /**
     * Store event
     */
    public function storeEvent(Request $request)
    {
        // Blank implementation - will implement later
        return redirect()->route('staff.events.index')
            ->with('success', 'Event created successfully!');
    }

    /**
     * Show event details
     */
    public function showEvent(Event $event)
    {
        // Blank implementation - will implement later
        return view('users.staff.events.show', compact('event'));
    }

    /**
     * Show edit event form
     */
    public function editEvent(Event $event)
    {
        // Blank implementation - will implement later
        return view('users.staff.events.edit', compact('event'));
    }

    /**
     * Update event
     */
    public function updateEvent(Request $request, Event $event)
    {
        // Blank implementation - will implement later
        return redirect()->route('staff.events.index')
            ->with('success', 'Event updated successfully!');
    }

    /**
     * Publish event
     */
    public function publishEvent(Event $event)
    {
        $event->update(['status' => 'published']);
        return redirect()->back()->with('success', 'Event published successfully!');
    }

    /**
     * View event registrations
     */
    public function eventRegistrations(Event $event)
    {
        // Blank implementation - will implement later
        return view('users.staff.events.registrations', compact('event'));
    }

    /**
     * Check in attendee
     */
    public function checkInAttendee(Request $request, Event $event)
    {
        // Blank implementation - will implement later
        return back()->with('success', 'Attendee checked in successfully!');
    }

    /**
     * Display staff news
     */
    public function news()
    {
        // Blank implementation - will implement later
        return view('users.staff.news.index');
    }

    /**
     * Create news
     */
    public function createNews()
    {
        // Blank implementation - will implement later
        return view('users.staff.news.create');
    }

    /**
     * Store news
     */
    public function storeNews(Request $request)
    {
        // Blank implementation - will implement later
        return redirect()->route('staff.news.index')
            ->with('success', 'News created successfully!');
    }

    /**
     * Edit news
     */
    public function editNews($id)
    {
        // Blank implementation - will implement later
        return view('users.staff.news.edit');
    }

    /**
     * Update news
     */
    public function updateNews(Request $request, $id)
    {
        // Blank implementation - will implement later
        return redirect()->route('staff.news.index')
            ->with('success', 'News updated successfully!');
    }

    /**
     * Delete news
     */
    public function deleteNews($id)
    {
        // Blank implementation - will implement later
        return redirect()->route('staff.news.index')
            ->with('success', 'News deleted successfully!');
    }
}
