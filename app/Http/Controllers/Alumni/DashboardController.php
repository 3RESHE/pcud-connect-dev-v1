<?php

namespace App\Http\Controllers\Alumni;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\NewsArticle;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the alumni dashboard.
     *
     * @return View
     */
    public function dashboard(): View
    {
        $user = auth()->user();

        // ===== ACTIVE JOB APPLICATIONS =====
        try {
            $activeApplicationsCount = JobApplication::query()
                ->where('applicant_id', $user->id)
                ->whereIn('status', ['pending', 'under_review', 'shortlisted'])
                ->count();
        } catch (\Exception $e) {
            $activeApplicationsCount = 0;
        }

        // ===== REGISTERED EVENTS =====
        try {
            $registeredEventsCount = EventRegistration::query()
                ->where('user_id', $user->id)
                ->where('status', 'confirmed')
                ->count();
        } catch (\Exception $e) {
            $registeredEventsCount = 0;
        }

        // ===== AVAILABLE JOBS =====
        try {
            $availableJobsCount = JobPosting::query()
                ->where('status', 'approved')
                ->count();
        } catch (\Exception $e) {
            $availableJobsCount = 0;
        }

        // ===== LATEST NEWS =====
        try {
            $latestNewsCount = NewsArticle::query()
                ->where('status', 'published')
                ->count();
        } catch (\Exception $e) {
            $latestNewsCount = 0;
        }

        // ===== RECENT JOB POSTINGS (Latest 4) =====
        try {
            $recentJobs = JobPosting::query()
                ->where('status', 'approved')
                ->orderBy('created_at', 'desc')
                ->limit(4)
                ->get();
        } catch (\Exception $e) {
            $recentJobs = collect();
        }

        // ===== LATEST NEWS ARTICLES (Latest 6) =====
        try {
            $latestNews = NewsArticle::query()
                ->where('status', 'published')
                ->orderBy('created_at', 'desc')
                ->limit(6)
                ->get();
        } catch (\Exception $e) {
            $latestNews = collect();
        }

        // ===== UPCOMING EVENTS (Next 3) =====
        try {
            $upcomingEvents = Event::query()
                ->where('status', 'published')
                ->where('event_date', '>=', Carbon::now())
                ->orderBy('event_date', 'asc')
                ->limit(3)
                ->get();
        } catch (\Exception $e) {
            $upcomingEvents = collect();
        }

        // ===== UPCOMING EVENTS COUNT =====
        try {
            $upcomingEventsCount = Event::query()
                ->where('status', 'published')
                ->where('event_date', '>=', Carbon::now())
                ->count();
        } catch (\Exception $e) {
            $upcomingEventsCount = 0;
        }

        // ===== PASS ALL VARIABLES TO VIEW =====
        return view('users.alumni.dashboard', [
            'activeApplicationsCount' => $activeApplicationsCount ?? 0,
            'registeredEventsCount' => $registeredEventsCount ?? 0,
            'availableJobsCount' => $availableJobsCount ?? 0,
            'latestNewsCount' => $latestNewsCount ?? 0,
            'upcomingEventsCount' => $upcomingEventsCount ?? 0,
            'recentJobs' => $recentJobs ?? collect(),
            'latestNews' => $latestNews ?? collect(),
            'upcomingEvents' => $upcomingEvents ?? collect(),
        ]);
    }
}
