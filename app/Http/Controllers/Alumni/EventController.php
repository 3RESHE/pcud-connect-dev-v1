<?php

namespace App\Http\Controllers\Alumni;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegistration;
use Carbon\Carbon;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class EventController extends Controller
{
    /**
     * Display a list of available events for alumni.
     *
     * @return View
     */
    public function index(): View
    {
        try {
            $user = auth()->user();
            $query = Event::query()->where('status', 'published');

            // ===== FILTER LOGIC =====
            $filter = request('filter');

            if ($filter === 'my_registrations') {
                // Show only events user is registered for
                $query->whereHas('registrations', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            } elseif ($filter === 'alumni_only') {
                // Show only alumni-only events
                $query->where('is_alumni_only', true);
            } elseif ($filter === 'completed') {
                // Show only completed/past events
                $query->where('event_date', '<', Carbon::now());
            } else {
                // Default: Show upcoming events
                $query->where('event_date', '>=', Carbon::now());
            }

            // ===== SEARCH FILTERING =====
            if (request('search')) {
                $search = request('search');
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            // ===== SORTING =====
            $query->orderBy('event_date', 'asc');

            // ===== PAGINATION =====
            $events = $query->paginate(10);

            // ===== COUNTS FOR STATS =====
            try {
                $availableEventsCount = Event::where('status', 'published')
                    ->where('event_date', '>=', Carbon::now())
                    ->count();
            } catch (\Exception $e) {
                $availableEventsCount = 0;
            }

            try {
                $myRegistrationsCount = EventRegistration::where('user_id', $user->id)
                    ->whereHas('event', function ($q) {
                        $q->where('status', 'published');
                    })
                    ->count();
            } catch (\Exception $e) {
                $myRegistrationsCount = 0;
            }

            try {
                $alumniOnlyCount = Event::where('status', 'published')
                    ->where('is_alumni_only', true)
                    ->where('event_date', '>=', Carbon::now())
                    ->count();
            } catch (\Exception $e) {
                $alumniOnlyCount = 0;
            }

            try {
                $completedEventsCount = Event::where('status', 'published')
                    ->where('event_date', '<', Carbon::now())
                    ->count();
            } catch (\Exception $e) {
                $completedEventsCount = 0;
            }

        } catch (\Exception $e) {
            \Log::error('Events index error: ' . $e->getMessage());

            // Return empty paginated collection
            $events = new LengthAwarePaginator([], 0, 10);
            $availableEventsCount = 0;
            $myRegistrationsCount = 0;
            $alumniOnlyCount = 0;
            $completedEventsCount = 0;
        }

        return view('users.alumni.events.index', [
            'events' => $events,
            'availableEventsCount' => $availableEventsCount ?? 0,
            'myRegistrationsCount' => $myRegistrationsCount ?? 0,
            'alumniOnlyCount' => $alumniOnlyCount ?? 0,
            'completedEventsCount' => $completedEventsCount ?? 0,
        ]);
    }

    /**
     * Display a single event.
     *
     * @param Event $event
     * @return View
     */
    public function show(Event $event): View
    {
        try {
            // Check if event is published
            if ($event->status !== 'published') {
                abort(404, 'Event not found.');
            }

            $user = auth()->user();

            // Check if user is registered
            $isRegistered = false;
            $userRegistration = null;

            try {
                $isRegistered = EventRegistration::where('user_id', $user->id)
                    ->where('event_id', $event->id)
                    ->exists();

                if ($isRegistered) {
                    $userRegistration = EventRegistration::where('user_id', $user->id)
                        ->where('event_id', $event->id)
                        ->first();
                }
            } catch (\Exception $e) {
                \Log::warning('Error checking event registration: ' . $e->getMessage());
            }

            // Get registrations count
            $registeredCount = 0;
            try {
                $registeredCount = EventRegistration::where('event_id', $event->id)
                    ->where('status', 'confirmed')
                    ->count();
            } catch (\Exception $e) {
                \Log::warning('Error counting registrations: ' . $e->getMessage());
            }

            // Get similar events (same format or type)
            $similarEvents = collect();
            try {
                $similarEvents = Event::query()
                    ->where('status', 'published')
                    ->where('id', '!=', $event->id)
                    ->where('event_date', '>=', Carbon::now())
                    ->where(function ($q) use ($event) {
                        $q->where('event_format', $event->event_format)
                          ->orWhere('is_alumni_only', $event->is_alumni_only);
                    })
                    ->orderBy('event_date', 'asc')
                    ->limit(3)
                    ->get();
            } catch (\Exception $e) {
                \Log::warning('Error fetching similar events: ' . $e->getMessage());
            }

            return view('users.alumni.events.show', [
                'event' => $event,
                'isRegistered' => $isRegistered,
                'userRegistration' => $userRegistration,
                'registeredCount' => $registeredCount,
                'similarEvents' => $similarEvents,
            ]);
        } catch (\Exception $e) {
            \Log::error('Event show error: ' . $e->getMessage());
            abort(500, 'An error occurred while loading the event details.');
        }
    }

    /**
     * Register for an event.
     *
     * @param Event $event
     * @return RedirectResponse
     */
    public function register(Event $event): RedirectResponse
    {
        try {
            $user = auth()->user();

            // ===== VALIDATION: EVENT IS PUBLISHED =====
            if ($event->status !== 'published') {
                return redirect()->back()->with('error', 'This event is no longer available.');
            }

            // ===== VALIDATION: USER HASN'T ALREADY REGISTERED =====
            $existingRegistration = EventRegistration::where('user_id', $user->id)
                ->where('event_id', $event->id)
                ->first();

            if ($existingRegistration) {
                return redirect()->back()->with('error', 'You have already registered for this event.');
            }

            // ===== VALIDATION: ALUMNI ONLY CHECK =====
            if ($event->is_alumni_only && $user->role !== 'alumni') {
                return redirect()->back()->with('error', 'This event is only for alumni members.');
            }

            // ===== VALIDATION: MAX ATTENDEES CHECK =====
            if ($event->max_attendees) {
                $registeredCount = EventRegistration::where('event_id', $event->id)
                    ->where('status', 'confirmed')
                    ->count();

                if ($registeredCount >= $event->max_attendees) {
                    return redirect()->back()->with('error', 'This event has reached maximum capacity.');
                }
            }

            // ===== CREATE REGISTRATION =====
            EventRegistration::create([
                'event_id' => $event->id,
                'user_id' => $user->id,
                'status' => 'confirmed',
                'registered_at' => Carbon::now(),
            ]);

            return redirect()->route('alumni.events.show', $event->id)
                ->with('success', 'You have successfully registered for this event!');
        } catch (\Exception $e) {
            \Log::error('Event registration error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while registering. Please try again.');
        }
    }
}
