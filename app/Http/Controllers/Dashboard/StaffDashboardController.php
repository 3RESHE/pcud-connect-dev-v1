<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\NewsArticle;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class StaffDashboardController extends Controller
{
    /**
     * Show staff dashboard.
     */
    public function dashboard()
    {
        $user = auth()->user();

        $stats = [
            'total_events' => Event::where('created_by', $user->id)->count(),
            'published_events' => Event::where('created_by', $user->id)->where('status', 'published')->count(),
            'pending_events' => Event::where('created_by', $user->id)->where('status', 'pending')->count(),
            'total_articles' => NewsArticle::where('created_by', $user->id)->count(),
            'published_articles' => NewsArticle::where('created_by', $user->id)->where('status', 'published')->count(),
            'recent_events' => Event::where('created_by', $user->id)->latest()->limit(5)->get(),
        ];

        return view('users.staff.dashboard', $stats);
    }

    // ===== EVENT MANAGEMENT =====

    /**
     * Show all events created by staff.
     */
    public function events()
    {
        $user = auth()->user();
        $events = Event::where('created_by', $user->id)
            ->latest()
            ->paginate(20);

        return view('users.staff.events.index', ['events' => $events]);
    }

    /**
     * Show create event form.
     */
    public function createEvent()
    {
        $departments = \App\Models\Department::all();

        return view('users.staff.events.create', ['departments' => $departments]);
    }

    /**
     * Store new event.
     */
    public function storeEvent(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_format' => 'required|in:inperson,virtual,hybrid',
            'event_date' => 'required|date|after:today',
            'is_multiday' => 'boolean',
            'end_date' => 'nullable|date|after:event_date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'venue_name' => 'nullable|string',
            'venue_address' => 'nullable|string',
            'platform' => 'nullable|string',
            'meeting_link' => 'nullable|url',
            'registration_required' => 'boolean',
            'walkin_allowed' => 'boolean',
            'registration_deadline' => 'nullable|date|before:event_date',
            'target_audience' => 'required|in:allstudents,alumni,openforall',
            'contact_person' => 'required|string',
            'contact_email' => 'required|email',
            'contact_phone' => 'nullable|string',
        ]);

        $event = Event::create(array_merge($validated, [
            'created_by' => auth()->id(),
            'status' => 'pending',
        ]));

        ActivityLog::logActivity(
            auth()->id(),
            'created',
            "Created event: {$event->title}",
            Event::class,
            $event->id
        );

        return redirect()->route('staff.events.index')
            ->with('success', 'Event created and submitted for approval.');
    }

    /**
     * Show edit event form.
     */
    public function editEvent($id)
    {
        $user = auth()->user();
        $event = Event::where('created_by', $user->id)->findOrFail($id);
        $departments = \App\Models\Department::all();

        return view('users.staff.events.edit', ['event' => $event, 'departments' => $departments]);
    }

    /**
     * Update event.
     */
    public function updateEvent(Request $request, $id)
    {
        $user = auth()->user();
        $event = Event::where('created_by', $user->id)->findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_format' => 'required|in:inperson,virtual,hybrid',
            'event_date' => 'required|date',
            'is_multiday' => 'boolean',
            'end_date' => 'nullable|date|after:event_date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'venue_name' => 'nullable|string',
            'venue_address' => 'nullable|string',
            'platform' => 'nullable|string',
            'meeting_link' => 'nullable|url',
            'registration_required' => 'boolean',
            'walkin_allowed' => 'boolean',
            'registration_deadline' => 'nullable|date|before:event_date',
            'target_audience' => 'required|in:allstudents,alumni,openforall',
            'contact_person' => 'required|string',
            'contact_email' => 'required|email',
            'contact_phone' => 'nullable|string',
        ]);

        $event->update($validated);

        ActivityLog::logActivity(
            auth()->id(),
            'updated',
            "Updated event: {$event->title}",
            Event::class,
            $event->id
        );

        return redirect()->route('staff.events.index')
            ->with('success', 'Event updated successfully.');
    }

    /**
     * Delete event.
     */
    public function deleteEvent($id)
    {
        $user = auth()->user();
        $event = Event::where('created_by', $user->id)->findOrFail($id);

        ActivityLog::logActivity(
            auth()->id(),
            'deleted',
            "Deleted event: {$event->title}",
            Event::class,
            $event->id
        );

        $event->delete();

        return redirect()->route('staff.events.index')
            ->with('success', 'Event deleted successfully.');
    }

    /**
     * Show event registrations.
     */
    public function eventRegistrations($id)
    {
        $user = auth()->user();
        $event = Event::where('created_by', $user->id)->findOrFail($id);
        $registrations = EventRegistration::where('event_id', $event->id)
            ->with('user')
            ->paginate(50);

        return view('users.staff.events.registrations', [
            'event' => $event,
            'registrations' => $registrations,
        ]);
    }

    /**
     * Check in attendee.
     */
    public function checkInAttendee(Request $request, $id)
    {
        $user = auth()->user();
        $event = Event::where('created_by', $user->id)->findOrFail($id);

        $validated = $request->validate([
            'registration_id' => 'required|exists:event_registrations,id',
        ]);

        $registration = EventRegistration::where('event_id', $event->id)
            ->findOrFail($validated['registration_id']);

        $registration->checkIn();

        ActivityLog::logActivity(
            auth()->id(),
            'checked_in',
            "Checked in attendee",
            EventRegistration::class,
            $registration->id
        );

        return redirect()->back()->with('success', 'Attendee checked in.');
    }

    // ===== NEWS MANAGEMENT =====

    /**
     * Show all articles created by staff.
     */
    public function news()
    {
        $user = auth()->user();
        $articles = NewsArticle::where('created_by', $user->id)
            ->latest()
            ->paginate(20);

        return view('users.staff.news.index', ['articles' => $articles]);
    }

    /**
     * Show create article form.
     */
    public function createNews()
    {
        return view('users.staff.news.create');
    }

    /**
     * Store new article.
     */
    public function storeNews(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|in:university_updates,alumni_success,partnership_highlights,campus_events,general',
        ]);

        $article = NewsArticle::create(array_merge($validated, [
            'created_by' => auth()->id(),
            'status' => 'pending',
        ]));

        ActivityLog::logActivity(
            auth()->id(),
            'created',
            "Created article: {$article->title}",
            NewsArticle::class,
            $article->id
        );

        return redirect()->route('staff.news.index')
            ->with('success', 'Article created and submitted for approval.');
    }

    /**
     * Show edit article form.
     */
    public function editNews($id)
    {
        $user = auth()->user();
        $article = NewsArticle::where('created_by', $user->id)->findOrFail($id);

        return view('users.staff.news.edit', ['article' => $article]);
    }

    /**
     * Update article.
     */
    public function updateNews(Request $request, $id)
    {
        $user = auth()->user();
        $article = NewsArticle::where('created_by', $user->id)->findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|in:university_updates,alumni_success,partnership_highlights,campus_events,general',
        ]);

        $article->update($validated);

        ActivityLog::logActivity(
            auth()->id(),
            'updated',
            "Updated article: {$article->title}",
            NewsArticle::class,
            $article->id
        );

        return redirect()->route('staff.news.index')
            ->with('success', 'Article updated successfully.');
    }

    /**
     * Delete article.
     */
    public function deleteNews($id)
    {
        $user = auth()->user();
        $article = NewsArticle::where('created_by', $user->id)->findOrFail($id);

        ActivityLog::logActivity(
            auth()->id(),
            'deleted',
            "Deleted article: {$article->title}",
            NewsArticle::class,
            $article->id
        );

        $article->delete();

        return redirect()->route('staff.news.index')
            ->with('success', 'Article deleted successfully.');
    }
}
