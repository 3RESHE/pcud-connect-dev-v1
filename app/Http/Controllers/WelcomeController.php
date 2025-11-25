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
        $featuredEvents = Event::whereIn('status', ['published', 'ongoing', 'completed'])
            ->where('is_featured', true)
            ->where('event_date', '>=', Carbon::today())
            ->orderBy('event_date', 'asc')
            ->take(3)
            ->get();

        // Get 3 featured job postings (approved only, not closed, featured only)
        $featuredJobs = JobPosting::where('status', 'approved')
            ->where('is_featured', true) // ✅ FEATURED ONLY
            ->where('application_deadline', '>=', Carbon::today())
            ->whereNull('closed_at')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        // Get 3 featured news articles (published only, not archived, featured only)
        $featuredNews = NewsArticle::where('status', 'published')
            ->where('is_featured', true) // ✅ FEATURED ONLY
            ->where('is_archived', false)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();

        return view('welcome', compact('featuredEvents', 'featuredJobs', 'featuredNews'));
    }
}
