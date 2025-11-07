<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegistration;
use Carbon\Carbon;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class StudentEventController extends Controller
{
    /**
     * Display list of events for students.
     */
    public function index(): View
    {
        try {
            $user = auth()->user();
            $query = Event::query()->where('status', 'published');

            // Search filtering
            if (request('search')) {
                $search = request('search');
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            // Filter by event type
            if (request('format')) {
                $query->where('event_format', request('format'));
            }

            // Sort
            if (request('sort') === 'oldest') {
                $query->orderBy('event_date', 'asc');
            } else {
                $query->orderBy('event_date', 'desc');
            }

            $events = $query->paginate(10);

        } catch (\Exception $e) {
            \Log::error('Student events index error: ' . $e->getMessage());
            $events = Event::where('status', 'published')->paginate(10);
        }

        return view('users.student.events.index', [
            'events' => $events,
        ]);
    }

    /**
     * Display event details.
     */
    public function show(Event $event): View
    {
        try {
            if ($event->status !== 'published') {
                abort(404, 'Event not found.');
            }

            $user = auth()->user();

            $isRegistered = EventRegistration::where('user_id', $user->id)
                ->where('event_id', $event->id)
                ->exists();

            $registeredCount = EventRegistration::where('event_id', $event->id)
                ->where('status', 'confirmed')
                ->count();

            // Related events
            $relatedEvents = Event::where('status', 'published')
                ->where('id', '!=', $event->id)
                ->where('event_format', $event->event_format)
                ->orderBy('event_date', 'asc')
                ->limit(3)
                ->get();

            return view('users.student.events.show', [
                'event' => $event,
                'isRegistered' => $isRegistered,
                'registeredCount' => $registeredCount,
                'relatedEvents' => $relatedEvents,
            ]);
        } catch (\Exception $e) {
            \Log::error('Student event show error: ' . $e->getMessage());
            abort(500, 'An error occurred.');
        }
    }

    /**
     * Register for event.
     */
    public function register(Event $event): RedirectResponse
    {
        try {
            $user = auth()->user();

            if ($event->status !== 'published') {
                return redirect()->back()->with('error', 'Event is not available.');
            }

            $existingRegistration = EventRegistration::where('user_id', $user->id)
                ->where('event_id', $event->id)
                ->first();

            if ($existingRegistration) {
                return redirect()->back()->with('error', 'You are already registered for this event.');
            }

            // Check capacity
            if ($event->max_attendees) {
                $registeredCount = EventRegistration::where('event_id', $event->id)
                    ->where('status', 'confirmed')
                    ->count();

                if ($registeredCount >= $event->max_attendees) {
                    return redirect()->back()->with('error', 'Event has reached maximum capacity.');
                }
            }

            // Create registration
            EventRegistration::create([
                'event_id' => $event->id,
                'user_id' => $user->id,
                'status' => 'confirmed',
                'registered_at' => Carbon::now(),
            ]);

            return redirect()->route('student.events.show', $event->id)
                ->with('success', 'Successfully registered for event!');
        } catch (\Exception $e) {
            \Log::error('Student event register error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Registration failed. Please try again.');
        }
    }
}
