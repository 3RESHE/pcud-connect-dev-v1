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
     * Display a listing of events (published, ongoing, completed)
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // ✅ UPDATED: Show published, ongoing, and completed events
        $query = Event::whereIn('status', ['published', 'ongoing', 'completed'])
            ->with(['creator', 'registrations'])
            ->orderBy('event_date', 'asc');

        // Filter by target audience based on user role
        if ($user->role === 'student') {
            $query->whereIn('target_audience', ['allstudents', 'openforall']);
        } elseif ($user->role === 'alumni') {
            $query->whereIn('target_audience', ['alumni', 'openforall']);
        }

        // Filter by event type if provided
        if ($request->has('type') && $request->type !== '') {
            $query->where('event_format', $request->type);
        }

        // Server-side search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('creator', function ($creatorQuery) use ($search) {
                        $creatorQuery->where('full_name', 'like', "%{$search}%");
                    });
            });
        }

        // Paginate results
        $events = $query->paginate(12);

        // Get current user's registrations
        $registeredEventIds = $user->eventRegistrations()
            ->pluck('event_id')
            ->toArray();

        // Get event statistics
        $stats = $this->getEventStats($user);

        return view('shared.events.index', [
            'events' => $events,
            'registeredEventIds' => $registeredEventIds,
            'userRole' => $user->role,
            'currentSearch' => $request->get('search'),
            'currentType' => $request->get('type'),
            'stats' => $stats,
        ]);
    }

    /**
     * Display a specific event (published, ongoing, completed)
     */
    public function show(Event $event)
    {
        // ✅ UPDATED: Allow viewing of published, ongoing, and completed events
        if (!in_array($event->status, ['published', 'ongoing', 'completed'])) {
            abort(404, 'Event not found');
        }

        $user = auth()->user();

        // Check if user role is allowed for this event's target audience
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

        // Check if user is registered for this event
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

        // ✅ UPDATED: Get similar events (published, ongoing, completed) - multi-day support
        $similarEvents = Event::whereIn('status', ['published', 'ongoing', 'completed'])
            ->where('event_format', $event->event_format)
            ->where('id', '!=', $event->id)
            ->where(function ($q) {
                $q->where(function ($singleDay) {
                    $singleDay->where('is_multiday', false)
                        ->where('event_date', '>=', today());
                })
                ->orWhere(function ($multiDay) {
                    $multiDay->where('is_multiday', true)
                        ->where('end_date', '>=', today());
                });
            })
            ->orderBy('event_date', 'asc')
            ->limit(3)
            ->get();

        // Get registration statistics
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
        // ✅ UPDATED: Allow registration for published and ongoing events only
        if (!in_array($event->status, ['published', 'ongoing'])) {
            return redirect()->back()->with('error', 'This event is not available for registration');
        }

        $user = auth()->user();

        // Check if user role is allowed
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

        // Check if already registered
        if ($event->registrations()->where('user_id', $user->id)->exists()) {
            return redirect()->back()->with('warning', 'You are already registered for this event');
        }

        // Check if event has reached capacity
        if (
            $event->max_attendees &&
            $event->registrations()->count() >= $event->max_attendees
        ) {
            return redirect()->back()->with('error', 'This event has reached maximum capacity');
        }

        // Validate registration form
        $validated = $request->validate([
            'agree_terms' => 'required|accepted',
        ]);

        try {
            // Create registration record
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

        // Find registration record
        $registration = $event->registrations()
            ->where('user_id', $user->id)
            ->first();

        if (!$registration) {
            return redirect()->back()->with('error', 'You are not registered for this event');
        }

        // ✅ UPDATED: Check end_date for multi-day events
        $eventEndDate = $event->is_multiday && $event->end_date
            ? $event->end_date
            : $event->event_date;

        if ($eventEndDate <= today()) {
            return redirect()->back()->with('error', 'Cannot unregister from events that have already started or ended');
        }

        try {
            // Delete registration record
            $registration->delete();

            return redirect()->back()
                ->with('success', 'Successfully unregistered from the event');
        } catch (\Exception $e) {
            \Log::error('Event unregistration failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to unregister. Please try again.');
        }
    }

    /**
     * ✅ UPDATED: Show user's event registrations with tabs (All, Upcoming, Ongoing, Completed)
     */
    public function myRegistrations(Request $request)
    {
        $user = auth()->user();
        $filter = $request->get('filter', 'all');

        // ✅ UPDATED: Show registrations based on filter
        $registrations = $user->eventRegistrations()
            ->with('event')
            ->when($filter === 'upcoming', function ($q) {
                $q->whereHas('event', function ($eq) {
                    $eq->where(function ($subQuery) {
                        $subQuery->where(function ($singleDay) {
                            $singleDay->where('is_multiday', false)
                                ->where('event_date', '>=', today());
                        })
                        ->orWhere(function ($multiDay) {
                            $multiDay->where('is_multiday', true)
                                ->where('end_date', '>=', today());
                        });
                    })->where('status', 'published');
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
                            ->orWhere(function ($dateQuery) {
                                $dateQuery->where('is_multiday', false)
                                    ->where('event_date', '<', today());
                            })
                            ->orWhere(function ($multiDayQuery) {
                                $multiDayQuery->where('is_multiday', true)
                                    ->where('end_date', '<', today());
                            });
                    });
                });
            })
            ->orderByDesc('created_at')
            ->paginate(10);

        // ✅ UPDATED: Calculate statistics for all four tabs
        $stats = [
            'total' => $user->eventRegistrations()->count(),
            'upcoming' => $user->eventRegistrations()
                ->whereHas('event', function ($q) {
                    $q->where('status', 'published')
                        ->where(function ($subQuery) {
                            $subQuery->where(function ($singleDay) {
                                $singleDay->where('is_multiday', false)
                                    ->where('event_date', '>=', today());
                            })
                            ->orWhere(function ($multiDay) {
                                $multiDay->where('is_multiday', true)
                                    ->where('end_date', '>=', today());
                            });
                        });
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
                            ->orWhere(function ($dateQuery) {
                                $dateQuery->where('is_multiday', false)
                                    ->where('event_date', '<', today());
                            })
                            ->orWhere(function ($multiDayQuery) {
                                $multiDayQuery->where('is_multiday', true)
                                    ->where('end_date', '<', today());
                            });
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
     * ✅ UPDATED: Get event statistics helper method
     */
    private function getEventStats($user)
    {
        // ✅ UPDATED: Include published, ongoing, and completed events
        $baseQuery = Event::whereIn('status', ['published', 'ongoing', 'completed'])
            ->when($user->role === 'student', fn($q) => $q->whereIn('target_audience', ['allstudents', 'openforall']))
            ->when($user->role === 'alumni', fn($q) => $q->whereIn('target_audience', ['alumni', 'openforall']));

        return [
            'total' => (clone $baseQuery)->count(),
            'upcoming' => (clone $baseQuery)
                ->where('status', 'published')
                ->where(function ($q) {
                    $q->where(function ($singleDay) {
                        $singleDay->where('is_multiday', false)
                            ->where('event_date', '>=', today());
                    })
                    ->orWhere(function ($multiDay) {
                        $multiDay->where('is_multiday', true)
                            ->where('end_date', '>=', today());
                    });
                })
                ->count(),
            'ongoing' => (clone $baseQuery)
                ->where('status', 'ongoing')
                ->count(),
            'completed' => (clone $baseQuery)
                ->where('status', 'completed')
                ->count(),
            'past' => (clone $baseQuery)
                ->where(function ($q) {
                    $q->where(function ($singleDay) {
                        $singleDay->where('is_multiday', false)
                            ->where('event_date', '<', today());
                    })
                    ->orWhere(function ($multiDay) {
                        $multiDay->where('is_multiday', true)
                            ->where('end_date', '<', today());
                    });
                })
                ->count(),
            'registered' => $user->eventRegistrations()->count(),
        ];
    }
}
