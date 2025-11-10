<?php

namespace App\Http\Controllers\Shared;

use App\Http\Controllers\Controller;
use App\Models\NewsArticle;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * Display list of all published news articles
     */
    public function index(Request $request)
    {
        // Get featured articles (for featured section)
        $featuredArticles = NewsArticle::published()
            ->where('is_featured', true)
            ->with(['creator', 'tags'])
            ->latest('published_at')
            ->get();

        // Get all published articles (for latest news grid)
        $query = NewsArticle::published()
            ->with(['creator', 'tags'])
            ->latest('published_at');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('summary', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Featured filter
        if ($request->filled('featured') && $request->featured === '1') {
            $query->where('is_featured', true);
        }

        // Tag filter
        if ($request->filled('tag')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('slug', $request->tag);
            });
        }

        $articles = $query->paginate(12);

        // Get stats for sidebar/filters
        $stats = [
            'total_count' => NewsArticle::published()->count(),
            'featured_count' => NewsArticle::featured()->count(),
            'categories' => NewsArticle::published()
                ->select('category', \DB::raw('count(*) as count'))
                ->groupBy('category')
                ->get(),
            'recent_tags' => \App\Models\NewsTag::withCount('articles')
                ->having('articles_count', '>', 0)
                ->orderBy('articles_count', 'desc')
                ->take(10)
                ->get(),
        ];

        return view('shared.news.index', compact('featuredArticles', 'articles', 'stats'));
    }

    /**
     * Display a specific news article
     */
    public function show(NewsArticle $newsArticle)
    {
        // Only show published articles
        if (!$newsArticle->isPublished()) {
            abort(404);
        }

        // Load relationships
        $newsArticle->load(['creator', 'tags']);

        // Increment view count
        $newsArticle->incrementViews();

        // Get related articles
        $relatedArticles = NewsArticle::published()
            ->where('id', '!=', $newsArticle->id)
            ->where('category', $newsArticle->category)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('shared.news.show', compact('newsArticle', 'relatedArticles'));
    }
}
