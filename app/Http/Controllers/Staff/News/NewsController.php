<?php

namespace App\Http\Controllers\Staff\News;

use App\Http\Controllers\Controller;
use App\Models\NewsArticle;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class NewsController extends Controller
{
    /**
     * Display list of staff's news articles
     */
    public function index()
    {
        $staff = auth()->user();

        $articles = NewsArticle::with(['creator', 'tags'])
            ->where('created_by', $staff->id)
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

    /**
     * Show create article form
     */
    public function create()
    {
        return view('users.staff.news.create');
    }

    /**
     * Store new article (with all new fields)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:university_updates,alumni_success,partnership_highlights,campus_events,general',
            'event_date' => 'required|date',
            'partnership_with' => 'nullable|string|max:255',
            'summary' => 'required|string|max:500',
            'content' => 'required|string',
            'tags' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB
            'action' => 'required|in:draft,submit',
        ]);

        try {
            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('featured_image')) {
                $imagePath = $request->file('featured_image')->store('news/featured', 'public');
            }

            // Determine status based on action
            $status = $validated['action'] === 'submit' ? 'pending' : 'draft';

            // Create article
            $article = NewsArticle::create([
                'created_by' => auth()->id(),
                'title' => $validated['title'],
                'slug' => Str::slug($validated['title']) . '-' . time(),
                'summary' => $validated['summary'],
                'content' => $validated['content'],
                'category' => $validated['category'],
                'event_date' => $validated['event_date'],
                'partnership_with' => $validated['partnership_with'],
                'featured_image' => $imagePath,
                'status' => $status,
            ]);

            // Sync tags
            $article->syncTagsFromString($validated['tags']);

            $message = $status === 'pending'
                ? '✓ Article submitted for review!'
                : '✓ Article saved as draft!';

            return redirect()->route('staff.news.index')->with('success', $message);
        } catch (\Exception $e) {
            \Log::error('News creation error: ' . $e->getMessage());
            return back()->with('error', 'Failed to create article: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show single article
     */
    public function show($id)
    {
        $article = NewsArticle::findOrFail($id);
        $article->load(['creator', 'approver', 'tags']);

        return view('users.staff.news.show', compact('article'));
    }

    /**
     * Show edit article form
     */
    public function edit($id)
    {
        $article = NewsArticle::findOrFail($id);
        $article->load(['creator', 'tags']);

        return view('users.staff.news.edit', compact('article'));
    }

    /**
     * Update article (with all new fields including image replacement)
     */
    public function update(Request $request, $id)
    {
        $article = NewsArticle::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:university_updates,alumni_success,partnership_highlights,campus_events,general',
            'event_date' => 'required|date',
            'partnership_with' => 'nullable|string|max:255',
            'summary' => 'required|string|max:500',
            'content' => 'required|string',
            'tags' => 'required|string',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB
            'action' => 'required|in:draft,submit',
        ]);

        try {
            // Handle image upload
            $imagePath = $article->featured_image;
            if ($request->hasFile('featured_image')) {
                // Delete old image if exists
                if ($article->featured_image && Storage::disk('public')->exists($article->featured_image)) {
                    Storage::disk('public')->delete($article->featured_image);
                }
                $imagePath = $request->file('featured_image')->store('news/featured', 'public');
            }

            // Determine status based on action
            $status = $validated['action'] === 'submit' ? 'pending' : 'draft';

            // Update article
            $article->update([
                'title' => $validated['title'],
                'slug' => Str::slug($validated['title']) . '-' . time(),
                'summary' => $validated['summary'],
                'content' => $validated['content'],
                'category' => $validated['category'],
                'event_date' => $validated['event_date'],
                'partnership_with' => $validated['partnership_with'],
                'featured_image' => $imagePath,
                'status' => $status,
                'rejection_reason' => null, // Clear rejection reason on update
            ]);

            // Sync tags
            $article->syncTagsFromString($validated['tags']);

            $message = $status === 'pending'
                ? '✓ Article updated and resubmitted for review!'
                : '✓ Article updated successfully!';

            return redirect()->route('staff.news.index')->with('success', $message);
        } catch (\Exception $e) {
            \Log::error('News update error: ' . $e->getMessage());
            return back()->with('error', 'Failed to update article: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Submit draft article for review
     */
    public function submit($id)
    {
        $article = NewsArticle::findOrFail($id);

        // Only allow from draft status
        if ($article->status !== 'draft') {
            return redirect()->back()
                ->with('error', 'Only draft articles can be submitted!');
        }

        $article->update(['status' => 'pending']);

        return redirect()->back()
            ->with('success', 'Article submitted for approval!');
    }

    /**
     * Withdraw pending article (revert to draft)
     */
    public function withdraw($id)
    {
        $article = NewsArticle::findOrFail($id);

        // Only allow from pending status
        if ($article->status !== 'pending') {
            return redirect()->back()
                ->with('error', 'Only pending articles can be withdrawn!');
        }

        $article->update(['status' => 'draft']);

        return redirect()->back()
            ->with('success', 'Article withdrawn from review!');
    }

    /**
     * Publish an approved article
     */
    /**
     * Publish an approved news article
     */
    public function publish(NewsArticle $newsArticle)
    {
        // Verify authorization (check if user owns the article)
        if ($newsArticle->created_by !== auth()->id()) {
            return redirect()->route('staff.news.index')
                ->with('error', 'Unauthorized action.');
        }

        // Check if article is approved
        if ($newsArticle->status !== 'approved') {
            return redirect()->route('staff.news.show', $newsArticle->id)
                ->with('error', 'Only approved articles can be published.');
        }

        // Update status to published
        $newsArticle->update([
            'status' => 'published',
            'published_at' => now(),
        ]);

        return redirect()->route('staff.news.show', $newsArticle->id)
            ->with('success', 'Article published successfully! 🎉');
    }





    /**
     * Delete article (with image cleanup)
     */
    public function destroy($id)
    {
        $article = NewsArticle::findOrFail($id);

        // Delete featured image if exists
        if ($article->featured_image && Storage::disk('public')->exists($article->featured_image)) {
            Storage::disk('public')->delete($article->featured_image);
        }

        $article->delete();

        return redirect()->route('staff.news.index')
            ->with('success', 'News article deleted successfully!');
    }
}
