<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\NewsArticle;
use Illuminate\Http\Request;

class StaffDashboardController extends Controller
{
    /**
     * Display the staff dashboard
     */
    public function dashboard()
    {
        $user = auth()->user();

        // Get news article statistics
        $total_articles = NewsArticle::where('created_by', $user->id)->count();
        $published_count = NewsArticle::where('created_by', $user->id)
            ->where('status', 'published')
            ->count();
        $pending_count = NewsArticle::where('created_by', $user->id)
            ->where('status', 'pending')
            ->count();
        $draft_count = NewsArticle::where('created_by', $user->id)
            ->where('status', 'draft')
            ->count();
        $approved_count = NewsArticle::where('created_by', $user->id)
            ->where('status', 'approved')
            ->count();
        $rejected_count = NewsArticle::where('created_by', $user->id)
            ->where('status', 'rejected')
            ->count();

        // Get recent articles (latest 5)
        $recent_articles = NewsArticle::where('created_by', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get recently published articles
        $recently_published = NewsArticle::where('created_by', $user->id)
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->limit(3)
            ->get();

        // Get pending articles
        $pending_articles = NewsArticle::where('created_by', $user->id)
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Calculate featured articles count
        $featured_articles = NewsArticle::where('created_by', $user->id)
            ->where('is_featured', true)
            ->count();

        return view('users.staff.dashboard.index', [
            'user' => $user,
            'total_articles' => $total_articles,
            'published_count' => $published_count,
            'pending_count' => $pending_count,
            'draft_count' => $draft_count,
            'approved_count' => $approved_count,
            'rejected_count' => $rejected_count,
            'recent_articles' => $recent_articles,
            'recently_published' => $recently_published,
            'pending_articles' => $pending_articles,
            'featured_articles' => $featured_articles,
        ]);
    }
}
