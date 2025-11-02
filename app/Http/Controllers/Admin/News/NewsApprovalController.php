<?php

namespace App\Http\Controllers\Admin\News;

use App\Http\Controllers\Controller;
use App\Models\NewsArticle;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class NewsApprovalController extends Controller
{
    /**
     * Show pending news for approval
     */
    public function index()
    {
        $articles = NewsArticle::where('status', 'pending')
            ->with('creator')
            ->paginate(20);

        $data = [
            'articles' => $articles,
            'total_count' => NewsArticle::count(),
            'pending_count' => NewsArticle::where('status', 'pending')->count(),
            'approved_count' => NewsArticle::where('status', 'approved')->count(),
            'published_count' => NewsArticle::where('status', 'published')->count(),
            'rejected_count' => NewsArticle::where('status', 'rejected')->count(),
        ];

        return view('users.admin.approvals.news.index', $data);
    }

    /**
     * Approve news article (API endpoint)
     */
    public function approve(Request $request, $id)
    {
        // Blank implementation - will implement later
        return response()->json(['message' => 'News approval not implemented yet'], 501);
    }

    /**
     * Reject news article (API endpoint)
     */
    public function reject(Request $request, $id)
    {
        // Blank implementation - will implement later
        return response()->json(['message' => 'News rejection not implemented yet'], 501);
    }
}
