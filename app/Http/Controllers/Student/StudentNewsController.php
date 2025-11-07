<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\NewsArticle;
use Illuminate\View\View;

class StudentNewsController extends Controller
{
    /**
     * Display list of news articles.
     */
    public function index(): View
    {
        try {
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

        } catch (\Exception $e) {
            \Log::error('Student news index error: ' . $e->getMessage());
            $news = NewsArticle::where('status', 'published')->paginate(6);
        }

        return view('users.student.news.index', [
            'news' => $news,
        ]);
    }

    /**
     * Display news article.
     */
    public function show(NewsArticle $article): View
    {
        try {
            if ($article->status !== 'published') {
                abort(404, 'Article not found.');
            }

            // Related articles
            $relatedArticles = NewsArticle::where('status', 'published')
                ->where('id', '!=', $article->id)
                ->where('category', $article->category)
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get();

            // Recent articles
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
