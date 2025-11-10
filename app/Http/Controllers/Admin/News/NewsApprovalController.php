<?php

namespace App\Http\Controllers\Admin\News;

use App\Http\Controllers\Controller;
use App\Models\NewsArticle;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class NewsApprovalController extends Controller
{
    /**
     * Show all news articles (excluding drafts) with admin controls
     */
    public function index()
    {
        // Exclude draft articles - only show submitted/pending and beyond
        $articles = NewsArticle::whereNotIn('status', ['draft'])
            ->with('creator')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $data = [
            'articles' => $articles,
            'total_count' => NewsArticle::whereNotIn('status', ['draft'])->count(),
            'pending_count' => NewsArticle::where('status', 'pending')->count(),
            'approved_count' => NewsArticle::where('status', 'approved')->count(),
            'published_count' => NewsArticle::where('status', 'published')->count(),
            'rejected_count' => NewsArticle::where('status', 'rejected')->count(),
        ];

        return view('users.admin.approvals.news.index', $data);
    }

    /**
     * Show article details for approval
     */
    public function show(NewsArticle $newsArticle)
    {
        $newsArticle->load('creator', 'tags');
        return view('users.admin.approvals.news.show', ['article' => $newsArticle]);
    }

    /**
     * Approve news article
     */
    public function approve(Request $request, NewsArticle $newsArticle)
    {
        try {
            $newsArticle->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
            ]);

            // Log activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'approved_news',
                'subject' => 'NewsArticle',
                'subject_id' => $newsArticle->id,
                'details' => "Approved news article: {$newsArticle->title}",
            ]);

            return redirect()->back()->with('success', '✅ News article approved successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', '❌ Error approving article: ' . $e->getMessage());
        }
    }

    /**
     * Reject news article with reason
     */
    public function reject(Request $request, NewsArticle $newsArticle)
    {
        // Validate rejection reason
        $validated = $request->validate([
            'rejection_reason' => 'required|string|min:10|max:500',
        ], [
            'rejection_reason.required' => 'Please provide a rejection reason.',
            'rejection_reason.min' => 'Rejection reason must be at least 10 characters.',
            'rejection_reason.max' => 'Rejection reason cannot exceed 500 characters.',
        ]);

        try {
            // Update article with rejection
            $newsArticle->update([
                'status' => 'rejected',
                'approved_by' => auth()->id(),
                'rejection_reason' => $validated['rejection_reason'],
            ]);

            // Log activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'rejected_news',
                'subject' => 'NewsArticle',
                'subject_id' => $newsArticle->id,
                'details' => "Rejected news article: {$newsArticle->title}",
            ]);

            return redirect()->back()->with('success', '❌ News article rejected successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', '❌ Error rejecting article: ' . $e->getMessage());
        }
    }
}
