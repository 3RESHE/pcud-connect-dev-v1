<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\NewsArticle;
use Illuminate\View\View;

class StudentNewsController extends Controller
{
    /**
     * Display list of news articles with featured section.
     */
    public function index(): View
    {
        try {
            // Get featured news (first 4 published articles)
            $featuredNews = NewsArticle::query()
                ->where('status', 'published')
                ->orderBy('created_at', 'desc')
                ->limit(4)
                ->get();

            // Get all news with filters
            $query = NewsArticle::query()->where('status', 'published');

            // Search
            if (request('search')) {
                $search = request('search');
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('content', 'like', "%{$search}%");
                });
            }

            // Filter by category
            if (request('category')) {
                $query->where('category', request('category'));
            }

            // Sort
            if (request('sort') === 'oldest') {
                $query->orderBy('created_at', 'asc');
            } else {
                $query->orderBy('created_at', 'desc');
            }

            $news = $query->paginate(6);

            return view('users.student.news.index', [
                'news' => $news,
                'featuredNews' => $featuredNews,
            ]);

        } catch (\Exception $e) {
            \Log::error('Student news index error: ' . $e->getMessage());
            return view('users.student.news.index', [
                'news' => NewsArticle::where('status', 'published')->paginate(6),
                'featuredNews' => collect(),
            ]);
        }
    }

    /**
     * Display single news article.
     */
    public function show(NewsArticle $article): View
    {
        try {
            // Check if article is published
            if ($article->status !== 'published') {
                abort(404, 'Article not found.');
            }

            // Related articles (same category)
            $relatedArticles = NewsArticle::where('status', 'published')
                ->where('id', '!=', $article->id)
                ->where('category', $article->category)
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get();

            // Recent articles (all categories)
            $recentArticles = NewsArticle::where('status', 'published')
                ->where('id', '!=', $article->id)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            return view('users.student.news.show', [
                'article' => $article,
                'relatedArticles' => $relatedArticles,
                'recentArticles' => $recentArticles,
            ]);

        } catch (\Exception $e) {
            \Log::error('Student news show error: ' . $e->getMessage());
            abort(500, 'An error occurred.');
        }
    }
}
