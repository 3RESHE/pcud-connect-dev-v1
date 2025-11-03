<?php

namespace App\Http\Controllers\Staff\News;

use App\Http\Controllers\Controller;
use App\Models\NewsArticle;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        $staff = auth()->user();

        $articles = NewsArticle::where('created_by', $staff->id)
            ->latest('created_at')
            ->paginate(15);

        $data = [
            'articles' => $articles,
            'total_articles' => NewsArticle::where('created_by', $staff->id)->count(),
            'published_count' => NewsArticle::where('created_by', $staff->id)
                ->where('status', 'published')
                ->count(),
            'pending_count' => NewsArticle::where('created_by', $staff->id)
                ->where('status', 'pending')
                ->count(),
            'rejected_count' => NewsArticle::where('created_by', $staff->id)
                ->where('status', 'rejected')
                ->count(),
            'draft_count' => NewsArticle::where('created_by', $staff->id)
                ->where('status', 'draft')
                ->count(),
        ];

        return view('users.staff.news.index', $data);
    }

    public function create()
    {
        return view('users.staff.news.create');
    }

    public function store(Request $request)
    {
        // Implementation later
        return redirect()->route('staff.news.index')
            ->with('success', 'News article created successfully!');
    }

    public function show(NewsArticle $newsArticle)
    {
        // Authorization check
        if ($newsArticle->created_by !== auth()->id()) {
            abort(403);
        }

        return view('users.staff.news.show', compact('newsArticle'));
    }

    public function edit(NewsArticle $newsArticle)
    {
        // Authorization check
        if ($newsArticle->created_by !== auth()->id()) {
            abort(403);
        }

        return view('users.staff.news.edit', compact('newsArticle'));
    }

    public function update(Request $request, NewsArticle $newsArticle)
    {
        // Authorization check
        if ($newsArticle->created_by !== auth()->id()) {
            abort(403);
        }

        // Implementation later
        return redirect()->route('staff.news.show', $newsArticle->id)
            ->with('success', 'News article updated successfully!');
    }

    public function submit(NewsArticle $newsArticle)
    {
        // Authorization check
        if ($newsArticle->created_by !== auth()->id()) {
            abort(403);
        }

        $newsArticle->update(['status' => 'pending']);

        return redirect()->back()
            ->with('success', 'Article submitted for approval!');
    }

    public function withdraw(NewsArticle $newsArticle)
    {
        // Authorization check
        if ($newsArticle->created_by !== auth()->id()) {
            abort(403);
        }

        $newsArticle->update(['status' => 'draft']);

        return redirect()->back()
            ->with('success', 'Article withdrawn from review!');
    }

    public function publish(NewsArticle $newsArticle)
    {
        // Authorization check
        if ($newsArticle->created_by !== auth()->id()) {
            abort(403);
        }

        // Only publish if approved
        if ($newsArticle->status === 'approved') {
            $newsArticle->publish();
            return redirect()->back()
                ->with('success', 'Article published successfully!');
        }

        return redirect()->back()
            ->with('error', 'Only approved articles can be published!');
    }

    public function destroy(NewsArticle $newsArticle)
    {
        // Authorization check
        if ($newsArticle->created_by !== auth()->id()) {
            abort(403);
        }

        $newsArticle->delete();

        return redirect()->route('staff.news.index')
            ->with('success', 'News article deleted successfully!');
    }
}
