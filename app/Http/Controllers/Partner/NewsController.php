<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\NewsArticle;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * Display list of published news for partner
     */
    public function index()
    {
        // Get featured published articles
        $featured_articles = NewsArticle::published()
            ->featured()
            ->latest('published_at')
            ->take(4)
            ->get();

        // Get all published articles with pagination
        $articles = NewsArticle::published()
            ->latest('published_at')
            ->paginate(9);

        $data = [
            'featured_articles' => $featured_articles,
            'articles' => $articles,
            'total_articles' => NewsArticle::published()->count(),
        ];

        return view('users.partner.news.index', $data);
    }

    /**
     * Load more articles (AJAX)
     */
    public function loadMore(Request $request)
    {
        $page = $request->query('page', 1);
        $category = $request->query('category', '');
        $search = $request->query('search', '');

        $query = NewsArticle::published();

        // Filter by category if provided
        if ($category) {
            $query->where('category', $category);
        }

        // Filter by search if provided
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $articles = $query->latest('published_at')
            ->paginate(9, ['*'], 'page', $page);

        // Format articles for JSON response
        $formatted = $articles->map(function ($article) {
            return [
                'id' => $article->id,
                'title' => $article->title,
                'content' => $article->content,
                'category' => $article->category,
                'featured_image' => $article->featured_image,
                'published_at' => $article->published_at,
                'views_count' => $article->views_count,
            ];
        });

        return response()->json([
            'articles' => $formatted,
            'has_more_pages' => $articles->hasMorePages(),
            'current_page' => $articles->currentPage(),
            'total' => $articles->total(),
        ]);
    }

    /**
     * Show single news article
     */
    public function show(NewsArticle $newsArticle)
    {
        // Only show if published
        if ($newsArticle->status !== 'published' || $newsArticle->is_archived) {
            abort(404);
        }

        // Increment views
        $newsArticle->incrementViews();

        // Get related articles (same category)
        $related = NewsArticle::published()
            ->where('category', $newsArticle->category)
            ->where('id', '!=', $newsArticle->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('users.partner.news.show', [
            'article' => $newsArticle,
            'related' => $related,
        ]);
    }
}
