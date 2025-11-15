<?php

namespace App\Http\Controllers\Shared;

use App\Models\Event;
use App\Models\EventRegistration;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EventController extends Controller
{
    /**
     * Display a listing of events with advanced filtering using model scopes
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Event::whereIn('status', ['published', 'ongoing', 'completed'])
            ->with(['creator', 'registrations']);

        if ($user->role === 'student') {
            $query->whereIn('target_audience', ['allstudents', 'openforall']);
        } elseif ($user->role === 'alumni') {
            $query->whereIn('target_audience', ['alumni', 'openforall']);
        }

        if ($request->filled('type')) {
            $query->byFormat($request->type);
        }

        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        if ($request->filled('date_from')) {
            $dateFrom = Carbon::parse($request->date_from);
            $query->where(function ($q) use ($dateFrom) {
                $q->where('event_date', '>=', $dateFrom)
                    ->orWhere('end_date', '>=', $dateFrom);
            });
        }

        if ($request->filled('date_to')) {
            $dateTo = Carbon::parse($request->date_to);
            $query->where(function ($q) use ($dateTo) {
                $q->where('event_date', '<=', $dateTo)
                    ->orWhere('end_date', '<=', $dateTo);
            });
        }

        if ($request->filled('capacity')) {
            if ($request->capacity === 'available') {
                $query->where(function ($q) {
                    $q->whereNull('max_attendees')
                        ->orWhereRaw('(SELECT COUNT(*) FROM event_registrations WHERE event_registrations.event_id = events.id) < events.max_attendees');
                });
            } elseif ($request->capacity === 'full') {
                $query->where('max_attendees', '>', 0)
                    ->whereRaw('(SELECT COUNT(*) FROM event_registrations WHERE event_registrations.event_id = events.id) >= events.max_attendees');
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('venue_name', 'like', "%{$search}%")
                    ->orWhereHas('creator', function ($creatorQuery) use ($search) {
                        $creatorQuery->where('full_name', 'like', "%{$search}%");
                    });
            });
        }

        $events = $query->orderBy('event_date', 'asc')->paginate(12);

        $registeredEventIds = $user->eventRegistrations()
            ->pluck('event_id')
            ->toArray();

        $stats = $this->getEventStats($user);

        return view('shared.events.index', [
            'events' => $events,
            'registeredEventIds' => $registeredEventIds,
            'userRole' => $user->role,
            'currentSearch' => $request->get('search'),
            'currentType' => $request->get('type'),
            'currentStatus' => $request->get('status'),
            'currentDateFrom' => $request->get('date_from'),
            'currentDateTo' => $request->get('date_to'),
            'currentCapacity' => $request->get('capacity'),
            'stats' => $stats,
        ]);
    }

    /**
     * Display a specific event
     */
    public function show(Event $event)
    {
        if (!in_array($event->status, ['published', 'ongoing', 'completed'])) {
            abort(404, 'Event not found');
        }

        $user = auth()->user();

        $allowedAudiences = ['openforall'];
        if ($user->role === 'student') {
            $allowedAudiences[] = 'allstudents';
        }
        if ($user->role === 'alumni') {
            $allowedAudiences[] = 'alumni';
        }

        if (!in_array($event->target_audience, $allowedAudiences)) {
            abort(403, 'You do not have access to this event');
        }

        $isRegistered = $event->registrations()
            ->where('user_id', $user->id)
            ->exists();

        $registration = null;
        if ($isRegistered) {
            $registration = $event->registrations()
                ->where('user_id', $user->id)
                ->first();
        }

        $similarEvents = Event::whereIn('status', ['published', 'ongoing', 'completed'])
            ->where('event_format', $event->event_format)
            ->where('id', '!=', $event->id)
            ->where('event_date', '>=', now())
            ->orderBy('event_date', 'asc')
            ->limit(3)
            ->get();

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
        if (!in_array($event->status, ['published', 'ongoing'])) {
            return redirect()->back()->with('error', 'This event is not available for registration');
        }

        $user = auth()->user();

        $allowedAudiences = ['openforall'];
        if ($user->role === 'student') {
            $allowedAudiences[] = 'allstudents';
        }
        if ($user->role === 'alumni') {
            $allowedAudiences[] = 'alumni';
        }

        if (!in_array($event->target_audience, $allowedAudiences)) {
            return redirect()->back()->with('error', 'You do not have permission to register for this event');
        }

        if ($event->registrations()->where('user_id', $user->id)->exists()) {
            return redirect()->back()->with('warning', 'You are already registered for this event');
        }

        if ($event->max_attendees && $event->registrations()->count() >= $event->max_attendees) {
            return redirect()->back()->with('error', 'This event has reached maximum capacity');
        }

        $request->validate([
            'agree_terms' => 'required|accepted',
        ]);

        try {
            EventRegistration::create([
                'event_id' => $event->id,
                'user_id' => $user->id,
                'user_type' => $user->role,
                'registration_type' => 'online',
                'attendance_status' => 'registered',
            ]);

            return redirect()->route('events.show', $event->id)
                ->with('success', 'Successfully registered for the event! Check your email for confirmation.');
        } catch (\Exception $e) {
            \Log::error('Event registration failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to register for event. Please try again.');
        }
    }

    /**
     * Unregister user from an event
     */
    public function unregister(Request $request, Event $event)
    {
        $user = auth()->user();

        $registration = $event->registrations()
            ->where('user_id', $user->id)
            ->first();

        if (!$registration) {
            return redirect()->back()->with('error', 'You are not registered for this event');
        }

        $eventEndDate = $event->is_multiday && $event->end_date
            ? $event->end_date
            : $event->event_date;

        if ($eventEndDate <= now()) {
            return redirect()->back()->with('error', 'Cannot unregister from events that have already started or ended');
        }

        try {
            $registration->delete();
            return redirect()->back()->with('success', 'Successfully unregistered from the event');
        } catch (\Exception $e) {
            \Log::error('Event unregistration failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to unregister. Please try again.');
        }
    }

    /**
     * Show user's event registrations with tabs
     */
    public function myRegistrations(Request $request)
    {
        $user = auth()->user();
        $filter = $request->get('filter', 'all');

        $registrations = $user->eventRegistrations()
            ->with('event')
            ->when($filter === 'upcoming', function ($q) {
                $q->whereHas('event', function ($eq) {
                    $eq->where('status', 'published')
                        ->where(function ($subQuery) {
                            $subQuery->where('event_date', '>=', now());
                        });
                });
            })
            ->when($filter === 'ongoing', function ($q) {
                $q->whereHas('event', function ($eq) {
                    $eq->where('status', 'ongoing');
                });
            })
            ->when($filter === 'completed', function ($q) {
                $q->whereHas('event', function ($eq) {
                    $eq->where(function ($subQuery) {
                        $subQuery->where('status', 'completed')
                            ->orWhere('event_date', '<', now());
                    });
                });
            })
            ->orderByDesc('created_at')
            ->paginate(10);

        $stats = [
            'total' => $user->eventRegistrations()->count(),
            'upcoming' => $user->eventRegistrations()
                ->whereHas('event', function ($q) {
                    $q->where('status', 'published')
                        ->where('event_date', '>=', now());
                })
                ->count(),
            'ongoing' => $user->eventRegistrations()
                ->whereHas('event', function ($q) {
                    $q->where('status', 'ongoing');
                })
                ->count(),
            'completed' => $user->eventRegistrations()
                ->whereHas('event', function ($q) {
                    $q->where(function ($subQuery) {
                        $subQuery->where('status', 'completed')
                            ->orWhere('event_date', '<', now());
                    });
                })
                ->count(),
        ];

        return view('shared.events.registrations', [
            'registrations' => $registrations,
            'userRole' => $user->role,
            'filter' => $filter,
            'stats' => $stats,
        ]);
    }

    /**
     * Get event statistics
     */
    private function getEventStats($user)
    {
        $baseQuery = Event::whereIn('status', ['published', 'ongoing', 'completed'])
            ->when($user->role === 'student', fn($q) => $q->whereIn('target_audience', ['allstudents', 'openforall']))
            ->when($user->role === 'alumni', fn($q) => $q->whereIn('target_audience', ['alumni', 'openforall']));

        return [
            'total' => (clone $baseQuery)->count(),
            'upcoming' => (clone $baseQuery)
                ->where('status', 'published')
                ->where('event_date', '>=', now())
                ->count(),
            'ongoing' => (clone $baseQuery)
                ->where('status', 'ongoing')
                ->count(),
            'completed' => (clone $baseQuery)
                ->where('status', 'completed')
                ->count(),
            'past' => (clone $baseQuery)
                ->where('event_date', '<', now())
                ->count(),
            'registered' => $user->eventRegistrations()->count(),
        ];
    }
}
