<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\JobPosting;
use App\Models\NewsArticle;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the student dashboard.
     *
     * @return View
     */
    public function dashboard(): View
    {
        $user = auth()->user();

        // ===== NEW JOBS COUNT =====
        try {
            $newJobsCount = JobPosting::where('status', 'approved')
                ->where('created_at', '>=', Carbon::now()->subDays(7))
                ->count();
        } catch (\Exception $e) {
            $newJobsCount = 0;
        }

        // ===== UPCOMING EVENTS COUNT =====
        try {
            $upcomingEventsCount = Event::where('status', 'published')
                ->where('event_date', '>=', Carbon::now())
                ->count();
        } catch (\Exception $e) {
            $upcomingEventsCount = 0;
        }

        // ===== LATEST NEWS COUNT =====
        try {
            $latestNewsCount = NewsArticle::where('status', 'published')
                ->count();
        } catch (\Exception $e) {
            $latestNewsCount = 0;
        }

        // ===== EVENT REGISTRATIONS COUNT =====
        try {
            $eventRegistrationsCount = EventRegistration::where('user_id', $user->id)
                ->where('status', 'confirmed')
                ->count();
        } catch (\Exception $e) {
            $eventRegistrationsCount = 0;
        }

        // ===== RECENT JOB POSTINGS (Latest 4) =====
        try {
            $recentJobs = JobPosting::where('status', 'approved')
                ->orderBy('created_at', 'desc')
                ->limit(4)
                ->get();
        } catch (\Exception $e) {
            $recentJobs = collect();
        }

        // ===== LATEST NEWS ARTICLES (Latest 3) =====
        try {
            $latestNews = NewsArticle::where('status', 'published')
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get();
        } catch (\Exception $e) {
            $latestNews = collect();
        }

        // ===== UPCOMING EVENTS (Next 3) =====
        try {
            $upcomingEvents = Event::where('status', 'published')
                ->where('event_date', '>=', Carbon::now())
                ->orderBy('event_date', 'asc')
                ->limit(3)
                ->get();
        } catch (\Exception $e) {
            $upcomingEvents = collect();
        }

        // ===== PASS ALL VARIABLES TO VIEW =====
        return view('users.student.dashboard', [
            'newJobsCount' => $newJobsCount ?? 0,
            'upcomingEventsCount' => $upcomingEventsCount ?? 0,
            'latestNewsCount' => $latestNewsCount ?? 0,
            'eventRegistrationsCount' => $eventRegistrationsCount ?? 0,
            'recentJobs' => $recentJobs ?? collect(),
            'latestNews' => $latestNews ?? collect(),
            'upcomingEvents' => $upcomingEvents ?? collect(),
        ]);
    }
}
