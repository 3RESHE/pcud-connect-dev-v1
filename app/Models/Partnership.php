<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Partnership extends Model
{
    protected $fillable = [
        'partner_id',
        'reviewed_by',
        'activity_type',
        'custom_activity_type',
        'organization_name',
        'organization_background',
        'organization_website',
        'organization_phone',
        'activity_title',
        'activity_description',
        'activity_date',
        'activity_time',
        'venue_address',
        'activity_objectives',
        'expected_impact',
        'contact_name',
        'contact_position',
        'contact_email',
        'contact_phone',
        'previous_experience',
        'additional_notes',
        'status',
        'admin_notes',
        'reviewed_at',
    ];

    protected $casts = [
        'activity_date' => 'date',
        'activity_time' => 'datetime',
        'reviewed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ===== RELATIONSHIPS =====

    /**
     * Get the partner who submitted this partnership proposal.
     */
    public function partner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'partner_id');
    }

    /**
     * Get the admin who reviewed this partnership.
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // ===== SCOPES =====

    /**
     * Scope: Get submitted partnerships.
     */
    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    /**
     * Scope: Get partnerships under review.
     */
    public function scopeUnderReview($query)
    {
        return $query->where('status', 'under_review');
    }

    /**
     * Scope: Get approved partnerships.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope: Get rejected partnerships.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope: Get partnerships by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: Get partnerships by partner.
     */
    public function scopeByPartner($query, $partnerId)
    {
        return $query->where('partner_id', $partnerId);
    }

    /**
     * Scope: Get partnerships by activity type.
     */
    public function scopeByActivityType($query, $type)
    {
        return $query->where('activity_type', $type);
    }

    /**
     * Scope: Get unreviewed partnerships.
     */
    public function scopeUnreviewed($query)
    {
        return $query->whereNull('reviewed_at');
    }

    /**
     * Scope: Get pending review partnerships.
     */
    public function scopePendingReview($query)
    {
        return $query->whereIn('status', ['submitted', 'under_review']);
    }

    /**
     * Scope: Recently submitted partnerships.
     */
    public function scopeRecentlySubmitted($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // ===== HELPER METHODS =====

    /**
     * Get activity type display name.
     */
    public function getActivityTypeDisplay(): string
    {
        if ($this->activity_type === 'other') {
            return $this->custom_activity_type ?? 'Other Activity';
        }

        return match($this->activity_type) {
            'feedingprogram' => 'Feeding Program',
            'brigadaeskwela' => 'Brigada Eskwela',
            'communitycleanup' => 'Community Cleanup',
            'treeplanting' => 'Tree Planting',
            'donationdrive' => 'Donation Drive',
            default => $this->activity_type,
        };
    }

    /**
     * Get activity type display color.
     */
    public function getActivityTypeColor(): string
    {
        return match($this->activity_type) {
            'feedingprogram' => 'warning',
            'brigadaeskwela' => 'info',
            'communitycleanup' => 'success',
            'treeplanting' => 'success',
            'donationdrive' => 'danger',
            'other' => 'secondary',
            default => 'secondary',
        };
    }

    /**
     * Get status display with badge color.
     */
    public function getStatusDisplay(): array
    {
        return match($this->status) {
            'submitted' => ['text' => 'Submitted', 'badge' => 'secondary'],
            'under_review' => ['text' => 'Under Review', 'badge' => 'warning'],
            'approved' => ['text' => 'Approved', 'badge' => 'success'],
            'rejected' => ['text' => 'Rejected', 'badge' => 'danger'],
            default => ['text' => $this->status, 'badge' => 'secondary'],
        };
    }

    /**
     * Check if partnership is pending review.
     */
    public function isPendingReview(): bool
    {
        return in_array($this->status, ['submitted', 'under_review']);
    }

    /**
     * Check if partnership has been reviewed.
     */
    public function isReviewed(): bool
    {
        return !is_null($this->reviewed_at);
    }

    /**
     * Get activity datetime display.
     */
    public function getActivityDatetimeDisplay(): string
    {
        $date = $this->activity_date->format('M d, Y');
        $time = $this->activity_time ? $this->activity_time->format('h:i A') : 'TBA';

        return "{$date} at {$time}";
    }

    /**
     * Mark partnership as under review.
     */
    public function markAsUnderReview()
    {
        $this->update(['status' => 'under_review']);
    }

    /**
     * Approve this partnership.
     */
    public function approve($adminId, $notes = null)
    {
        $this->update([
            'status' => 'approved',
            'reviewed_by' => $adminId,
            'reviewed_at' => now(),
            'admin_notes' => $notes,
        ]);
    }

    /**
     * Reject this partnership.
     */
    public function reject($adminId, $reason = null)
    {
        $this->update([
            'status' => 'rejected',
            'reviewed_by' => $adminId,
            'reviewed_at' => now(),
            'admin_notes' => $reason,
        ]);
    }

    /**
     * Get days since submission.
     */
    public function getDaysSinceSubmission(): int
    {
        return $this->created_at->diffInDays(now());
    }

    /**
     * Get days pending review.
     */
    public function getDaysPendingReview(): ?int
    {
        if ($this->isReviewed()) {
            return $this->created_at->diffInDays($this->reviewed_at);
        }

        return $this->created_at->diffInDays(now());
    }

    /**
     * Get reviewed datetime display.
     */
    public function getReviewedAtDisplay(): string
    {
        if (!$this->reviewed_at) {
            return 'Not reviewed';
        }

        return $this->reviewed_at->format('M d, Y h:i A');
    }

    /**
     * Get reviewed at relative time.
     */
    public function getReviewedAtDiffForHumans(): string
    {
        if (!$this->reviewed_at) {
            return 'Not reviewed';
        }

        return $this->reviewed_at->diffForHumans();
    }
}
