<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\NewsArticle;
use Illuminate\Http\Request;

class StaffDashboardController extends Controller
{
    public function dashboard()
    {
        $staff = auth()->user();

        // Events Stats
        $total_events = Event::where('created_by', $staff->id)->count();
        $published_events = Event::where('created_by', $staff->id)
            ->where('status', 'published')
            ->count();
        $upcoming_events = Event::where('created_by', $staff->id)
            ->where('status', 'published')
            ->where('event_date', '>=', now())
            ->count();
        $pending_events = Event::where('created_by', $staff->id)
            ->where('status', 'under_review')
            ->count();

        // News Stats
        $total_news = NewsArticle::where('created_by', $staff->id)->count();
        $pending_news = NewsArticle::where('created_by', $staff->id)
            ->where('status', 'pending')
            ->count();

        // Total Registrations
        $total_registrations = Event::where('created_by', $staff->id)
            ->withCount('registrations')
            ->get()
            ->sum('registrations_count');

        // Upcoming Events List
        $upcoming_events_list = Event::where('created_by', $staff->id)
            ->where('status', 'published')
            ->where('event_date', '>=', now())
            ->orderBy('event_date', 'asc')
            ->take(5)
            ->get();

        // Pending Items (Events + News)
        $pending_events_list = Event::where('created_by', $staff->id)
            ->where('status', 'under_review')
            ->latest()
            ->take(3)
            ->get()
            ->map(fn($e) => (object)['type' => 'event', 'id' => $e->id, 'title' => $e->title, 'created_at' => $e->created_at]);

        $pending_news_list = NewsArticle::where('created_by', $staff->id)
            ->where('status', 'pending')
            ->latest()
            ->take(2)
            ->get()
            ->map(fn($n) => (object)['type' => 'news', 'id' => $n->id, 'title' => $n->title, 'created_at' => $n->created_at]);

        $pending_items_list = $pending_events_list->merge($pending_news_list)->sortByDesc('created_at')->take(5);
        $pending_items = $pending_items_list->count();

        // Recent News
        $recent_news = NewsArticle::where('created_by', $staff->id)
            ->latest()
            ->take(3)
            ->get();

        // Recent Activities (placeholder - implement later)
        $recent_activities = collect([]);

        return view('users.staff.dashboard', compact(
            'total_events',
            'published_events',
            'upcoming_events',
            'pending_events',
            'total_news',
            'pending_news',
            'total_registrations',
            'upcoming_events_list',
            'pending_items_list',
            'pending_items',
            'recent_news',
            'recent_activities'
        ));
    }
}
