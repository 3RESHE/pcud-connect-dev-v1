<?php

namespace App\Http\Controllers\Alumni;

use App\Http\Controllers\Controller;
use App\Models\NewsArticle;
use Illuminate\View\View;

class AlumniNewsController extends Controller
{
    /**
     * Display a list of published news articles for alumni.
     *
     * @return View
     */
    public function index(): View
    {
        try {
            $query = NewsArticle::query()->where('status', 'published');

            // ===== SEARCH FILTERING =====
            if (request('search')) {
                $search = request('search');
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('content', 'like', "%{$search}%");
                });
            }

            // ===== CATEGORY FILTERING =====
            if (request('category')) {
                $query->where('category', request('category'));
            }

            // ===== SORTING =====
            if (request('sort') === 'oldest') {
                $query->orderBy('created_at', 'asc');
            } else {
                $query->orderBy('created_at', 'desc');
            }

            // ===== PAGINATION =====
            $news = $query->paginate(6);

            // ===== GET FEATURED NEWS (First 4 by order) =====
            try {
                $featuredNews = NewsArticle::where('status', 'published')
                    ->where('is_featured', true)
                    ->orderBy('created_at', 'desc')
                    ->limit(4)
                    ->get();
            } catch (\Exception $e) {
                \Log::warning('Error fetching featured news: ' . $e->getMessage());
                $featuredNews = collect();
            }

        } catch (\Exception $e) {
            \Log::error('News index error: ' . $e->getMessage());
            $news = NewsArticle::query()->where('status', 'published')->paginate(6);
            $featuredNews = collect();
        }

        return view('users.alumni.news.index', [
            'news' => $news,
            'featuredNews' => $featuredNews ?? collect(),
        ]);
    }

    /**
     * Display a single news article.
     *
     * @param NewsArticle $article
     * @return View
     */
    public function show(NewsArticle $article): View
    {
        try {
            // Check if article is published
            if ($article->status !== 'published') {
                abort(404, 'Article not found.');
            }

            // Get related articles (same category, exclude current)
            $relatedArticles = collect();
            try {
                $relatedArticles = NewsArticle::where('status', 'published')
                    ->where('id', '!=', $article->id)
                    ->where(function ($q) use ($article) {
                        $q->where('category', $article->category);
                    })
                    ->orderBy('created_at', 'desc')
                    ->limit(3)
                    ->get();
            } catch (\Exception $e) {
                \Log::warning('Error fetching related articles: ' . $e->getMessage());
            }

            // Get recent articles (for sidebar)
            $recentArticles = collect();
            try {
                $recentArticles = NewsArticle::where('status', 'published')
                    ->where('id', '!=', $article->id)
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get();
            } catch (\Exception $e) {
                \Log::warning('Error fetching recent articles: ' . $e->getMessage());
            }

            return view('users.alumni.news.show', [
                'article' => $article,
                'relatedArticles' => $relatedArticles,
                'recentArticles' => $recentArticles,
            ]);
        } catch (\Exception $e) {
            \Log::error('News show error: ' . $e->getMessage());
            abort(500, 'An error occurred while loading the article.');
        }
    }
}
