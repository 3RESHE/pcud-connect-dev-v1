<?php

namespace App\Http\Controllers\Shared;

use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EventController extends Controller
{
    /**
     * Display a listing of published events
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // Base query for published events
        $query = Event::where('status', 'published')
            ->with(['creator', 'registrations'])
            ->orderBy('event_date', 'asc');

        // Filter by event type if provided
        if ($request->has('type') && $request->type !== '') {
            $query->where('event_format', $request->type);
        }

        // Filter by date range if provided
        if ($request->has('from_date') && $request->from_date) {
            $query->whereDate('event_date', '>=', $request->from_date);
        }

        if ($request->has('to_date') && $request->to_date) {
            $query->whereDate('event_date', '<=', $request->to_date);
        }

        // Search by title or description
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by status tabs
        $status = $request->get('status', 'all');
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Paginate results
        $events = $query->paginate(12);

        // Get current user's registrations
        $registeredEventIds = $user->eventRegistrations()
            ->pluck('event_id')
            ->toArray();

        // Get event stats
        $stats = [
            'total' => Event::where('status', 'published')->count(),
            'upcoming' => Event::where('status', 'published')
                ->where('event_date', '>=', now()->toDateString())
                ->count(),
            'past' => Event::where('status', 'published')
                ->where('event_date', '<', now()->toDateString())
                ->count(),
            'registered' => count($registeredEventIds),
        ];

        return view('shared.events.index', [
            'events' => $events,
            'registeredEventIds' => $registeredEventIds,
            'userRole' => $user->role,
            'currentSearch' => $request->get('search'),
            'currentType' => $request->get('type'),
            'currentStatus' => $status,
            'stats' => $stats,
        ]);
    }

    /**
     * Display a specific event
     */
    public function show(Event $event)
    {
        // Verify event is published
        if ($event->status !== 'published') {
            abort(404, 'Event not found');
        }

        $user = auth()->user();

        // Check if user is registered
        $isRegistered = $event->registrations()
            ->where('user_id', $user->id)
            ->exists();

        // Get registration details if registered
        $registration = null;
        if ($isRegistered) {
            $registration = $event->registrations()
                ->where('user_id', $user->id)
                ->first();
        }

        // Get similar events
        $similarEvents = Event::where('status', 'published')
            ->where('event_format', $event->event_format)
            ->where('id', '!=', $event->id)
            ->where('event_date', '>=', now()->toDateString())
            ->orderBy('event_date', 'asc')
            ->limit(3)
            ->get();

        // Get registrations count
        $registrationCount = $event->registrations()->count();
        $capacityPercent = 0;
        if ($event->max_attendees) {
            $capacityPercent = round(($registrationCount / $event->max_attendees) * 100);
        }

        return view('shared.events.show', [
            'event' => $event,
            'isRegistered' => $isRegistered,
            'registration' => $registration,
            'similarEvents' => $similarEvents,
            'userRole' => $user->role,
            'registrationCount' => $registrationCount,
            'capacityPercent' => $capacityPercent,
        ]);
    }

    /**
     * Register user for an event
     */
    public function register(Request $request, Event $event)
    {
        // Verify event is published
        if ($event->status !== 'published') {
            return redirect()->back()->with('error', 'This event is not available for registration');
        }

        $user = auth()->user();

        // Check if already registered
        if ($event->registrations()->where('user_id', $user->id)->exists()) {
            return redirect()->back()->with('warning', 'You are already registered for this event');
        }

        // Check if event has reached capacity
        if ($event->max_attendees &&
            $event->registrations()->count() >= $event->max_attendees) {
            return redirect()->back()->with('error', 'This event has reached maximum capacity');
        }

        // Validate registration form
        $validated = $request->validate([
            'dietary_restriction' => 'nullable|string|max:100',
            'special_requirements' => 'nullable|string|max:500',
            'agree_terms' => 'required|accepted',
        ]);

        try {
            // Create registration
            EventRegistration::create([
                'event_id' => $event->id,
                'user_id' => $user->id,
                'registration_date' => now(),
                'status' => 'confirmed',
                'dietary_restriction' => $validated['dietary_restriction'] ?? null,
                'special_requirements' => $validated['special_requirements'] ?? null,
            ]);

            return redirect()->route('events.show', $event->id)
                ->with('success', 'Successfully registered for the event! Check your email for confirmation.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to register for event. Please try again.');
        }
    }

    /**
     * Unregister user from an event
     */
    public function unregister(Request $request, Event $event)
    {
        $user = auth()->user();

        // Find and delete registration
        $registration = $event->registrations()
            ->where('user_id', $user->id)
            ->first();

        if (!$registration) {
            return redirect()->back()->with('error', 'You are not registered for this event');
        }

        // Check if event has started
        if ($event->event_date <= now()->toDateString()) {
            return redirect()->back()->with('error', 'Cannot unregister from events that have already started');
        }

        try {
            $registration->delete();

            return redirect()->back()
                ->with('success', 'Successfully unregistered from the event');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to unregister. Please try again.');
        }
    }

    /**
     * Show user's event registrations
     */
    public function myRegistrations(Request $request)
    {
        $user = auth()->user();

        // Get all user registrations with event details
        $registrations = $user->eventRegistrations()
            ->with('event')
            ->when($request->get('filter') === 'upcoming', function ($q) {
                $q->whereHas('event', function ($eq) {
                    $eq->where('event_date', '>=', now()->toDateString());
                });
            })
            ->when($request->get('filter') === 'past', function ($q) {
                $q->whereHas('event', function ($eq) {
                    $eq->where('event_date', '<', now()->toDateString());
                });
            })
            ->orderByDesc('registration_date')
            ->paginate(10);

        // Stats
        $stats = [
            'total' => $user->eventRegistrations()->count(),
            'upcoming' => $user->eventRegistrations()
                ->whereHas('event', fn($q) => $q->where('event_date', '>=', now()->toDateString()))
                ->count(),
            'past' => $user->eventRegistrations()
                ->whereHas('event', fn($q) => $q->where('event_date', '<', now()->toDateString()))
                ->count(),
        ];

        return view('shared.events.registrations', [
            'registrations' => $registrations,
            'userRole' => $user->role,
            'filter' => $request->get('filter', 'all'),
            'stats' => $stats,
        ]);
    }
}
