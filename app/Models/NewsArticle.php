<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class NewsArticle extends Model
{
    protected $fillable = [
        'created_by',
        'approved_by',
        'title',
        'slug',
        'summary',              // ✅ NEW
        'content',
        'category',
        'event_date',           // ✅ NEW
        'partnership_with',     // ✅ NEW
        'featured_image',
        'is_featured',
        'is_archived',
        'status',
        'rejection_reason',
        'published_at',
        'views_count',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_archived' => 'boolean',
        'views_count' => 'integer',
        'event_date' => 'date',         // ✅ NEW
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ===== RELATIONSHIPS =====

    /**
     * Get the staff member who created this article.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the admin who approved this article.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * ✅ NEW: Get tags for this article
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(NewsTag::class, 'news_article_tag', 'news_article_id', 'news_tag_id')
                    ->withTimestamps();
    }

    // ===== SCOPES =====

    /**
     * Scope: Get published articles.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')->where('is_archived', false);
    }

    /**
     * Scope: Get featured articles.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)->where('status', 'published');
    }

    /**
     * Scope: Get pending articles awaiting approval.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Get draft articles.
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope: Get archived articles.
     */
    public function scopeArchived($query)
    {
        return $query->where('is_archived', true);
    }

    /**
     * Scope: Get articles by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: Get articles by category.
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope: Get articles by creator.
     */
    public function scopeByCreator($query, $creatorId)
    {
        return $query->where('created_by', $creatorId);
    }

    /**
     * Scope: Get most viewed articles.
     */
    public function scopeMostViewed($query, $limit = 10)
    {
        return $query->where('status', 'published')
            ->orderBy('views_count', 'desc')
            ->limit($limit);
    }

    /**
     * Scope: Get recently published articles.
     */
    public function scopeRecentlyPublished($query, $days = 7)
    {
        return $query->where('status', 'published')
            ->where('published_at', '>=', now()->subDays($days))
            ->orderBy('published_at', 'desc');
    }

    /**
     * ✅ NEW: Scope by event date
     */
    public function scopeUpcomingEvents($query)
    {
        return $query->where('event_date', '>=', now()->toDateString())
                    ->where('status', 'published')
                    ->orderBy('event_date', 'asc');
    }

    /**
     * ✅ NEW: Scope by partnership
     */
    public function scopeByPartnership($query, $partnership)
    {
        return $query->where('partnership_with', $partnership)
                    ->where('status', 'published');
    }

    // ===== HELPER METHODS =====

    /**
     * Get category display name.
     */
    public function getCategoryDisplayName(): string
    {
        return match($this->category) {
            'university_updates' => 'University Updates',
            'alumni_success' => 'Alumni Success',
            'partnership_highlights' => 'Partnership Highlights',
            'campus_events' => 'Campus Events',
            'general' => 'General',
            default => $this->category,
        };
    }

    /**
     * Get category display color.
     */
    public function getCategoryColor(): string
    {
        return match($this->category) {
            'university_updates' => 'primary',
            'alumni_success' => 'success',
            'partnership_highlights' => 'info',
            'campus_events' => 'warning',
            'general' => 'secondary',
            default => 'secondary',
        };
    }

    /**
     * ✅ NEW: Get formatted event date
     */
    public function getEventDateDisplay(): string
    {
        if (!$this->event_date) {
            return 'N/A';
        }

        return $this->event_date->format('M d, Y');
    }

    /**
     * ✅ NEW: Get excerpt from summary
     */
    public function getSummaryExcerpt($length = 100): string
    {
        if (!$this->summary) {
            return $this->getExcerpt($length);
        }

        if (strlen($this->summary) <= $length) {
            return $this->summary;
        }

        return substr($this->summary, 0, $length) . '...';
    }

    /**
     * Increment views count.
     */
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    /**
     * Get views count display.
     */
    public function getViewsDisplay(): string
    {
        if ($this->views_count < 1000) {
            return (string)$this->views_count;
        }

        $thousands = floor($this->views_count / 1000);
        $remainder = $this->views_count % 1000;

        if ($remainder === 0) {
            return "{$thousands}K";
        }

        $hundredths = round($remainder / 100);
        return "{$thousands}.{$hundredths}K";
    }

    /**
     * Get excerpt of content.
     */
    public function getExcerpt($length = 150): string
    {
        $text = strip_tags($this->content);

        if (strlen($text) <= $length) {
            return $text;
        }

        return substr($text, 0, $length) . '...';
    }

    /**
     * Check if article is published.
     */
    public function isPublished(): bool
    {
        return $this->status === 'published' && !$this->is_archived;
    }

    /**
     * Check if article is featured.
     */
    public function isFeatured(): bool
    {
        return $this->is_featured && $this->isPublished();
    }

    /**
     * ✅ NEW: Sync tags from comma-separated string
     */
    public function syncTagsFromString(string $tagsString): void
    {
        $tagNames = array_map('trim', explode(',', $tagsString));
        $tagIds = [];

        foreach ($tagNames as $tagName) {
            if (empty($tagName)) {
                continue;
            }

            $tag = NewsTag::firstOrCreate(
                ['name' => $tagName],
                ['slug' => Str::slug($tagName)]
            );

            $tagIds[] = $tag->id;
        }

        $this->tags()->sync($tagIds);
    }

    /**
     * ✅ NEW: Get tags as comma-separated string
     */
    public function getTagsString(): string
    {
        return $this->tags->pluck('name')->join(', ');
    }

    /**
     * ✅ NEW: Get tags as array
     */
    public function getTagsArray(): array
    {
        return $this->tags->pluck('name')->toArray();
    }

    /**
     * Approve this article.
     */
    public function approve($adminId)
    {
        $this->update([
            'status' => 'approved',
            'approved_by' => $adminId,
        ]);
    }

    /**
     * Reject this article with reason.
     */
    public function reject($adminId, $reason)
    {
        $this->update([
            'status' => 'rejected',
            'approved_by' => $adminId,
            'rejection_reason' => $reason,
        ]);
    }

    /**
     * Publish this article.
     */
    public function publish()
    {
        // Auto-generate slug if not set
        if (!$this->slug) {
            $this->slug = Str::slug($this->title) . '-' . now()->timestamp;
        }

        $this->update([
            'status' => 'published',
            'published_at' => now(),
        ]);
    }

    /**
     * Archive this article (soft delete).
     */
    public function archive()
    {
        $this->update(['is_archived' => true]);
    }

    /**
     * Unarchive this article.
     */
    public function unarchive()
    {
        $this->update(['is_archived' => false]);
    }

    /**
     * Feature this article.
     */
    public function feature()
    {
        $this->update(['is_featured' => true]);
    }

    /**
     * Unfeature this article.
     */
    public function unfeature()
    {
        $this->update(['is_featured' => false]);
    }

    /**
     * Get published datetime display.
     */
    public function getPublishedAtDisplay(): string
    {
        if (!$this->published_at) {
            return 'Not published';
        }

        return $this->published_at->format('M d, Y h:i A');
    }

    /**
     * Get relative time since published (e.g., "2 days ago").
     */
    public function getPublishedAtDiffForHumans(): string
    {
        if (!$this->published_at) {
            return 'Not published';
        }

        return $this->published_at->diffForHumans();
    }

    /**
     * ✅ NEW: Get status display badge
     */
    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            'draft' => 'bg-gray-100 text-gray-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-blue-100 text-blue-800',
            'published' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * ✅ NEW: Get status display text
     */
    public function getStatusDisplay(): string
    {
        return match($this->status) {
            'draft' => 'Draft',
            'pending' => 'Pending Review',
            'approved' => 'Approved',
            'published' => 'Published',
            'rejected' => 'Rejected',
            default => ucfirst($this->status),
        };
    }

    // ===== MODEL EVENTS =====

    /**
     * Auto-generate slug on create.
     */
    protected static function booted()
    {
        static::creating(function ($article) {
            if (!$article->slug) {
                $article->slug = Str::slug($article->title) . '-' . now()->timestamp;
            }
        });
    }
}
