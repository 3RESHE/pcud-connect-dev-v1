<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\JobPosting;
use App\Models\NewsArticle;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        // Get 3 featured upcoming events (published only)
        $featuredEvents = Event::where('status', 'published')
            ->where('event_date', '>=', Carbon::today())
            ->orderBy('event_date', 'asc')
            ->take(3)
            ->get();

        // Get 3 featured job postings (approved only, not closed)
        $featuredJobs = JobPosting::where('status', 'approved')
            ->where('application_deadline', '>=', Carbon::today())
            ->whereNull('closed_at')  // ✅ FIXED: closed_at (with underscore)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        // Get 3 featured news articles (published only, not archived)
        $featuredNews = NewsArticle::where('status', 'published')
            ->where('is_archived', false)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return view('welcome', compact('featuredEvents', 'featuredJobs', 'featuredNews'));
    }
}
