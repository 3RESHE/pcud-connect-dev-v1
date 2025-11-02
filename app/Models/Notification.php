<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'user_id',
        'type',
        'notifiable_type',
        'notifiable_id',
        'data',
        'read_at',
    ];

    protected $casts = [
        'data' => 'json',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ===== RELATIONSHIPS =====

    /**
     * Get the user who receives this notification.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ===== SCOPES =====

    /**
     * Scope: Get unread notifications.
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope: Get read notifications.
     */
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    /**
     * Scope: Get notifications by type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope: Get notifications by user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope: Get notifications for a specific notifiable model.
     */
    public function scopeForNotifiable($query, $type, $id)
    {
        return $query->where('notifiable_type', $type)
            ->where('notifiable_id', $id);
    }

    /**
     * Scope: Recently created notifications.
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days))
            ->orderBy('created_at', 'desc');
    }

    /**
     * Scope: Get notifications created today.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', now()->toDateString());
    }

    // ===== HELPER METHODS =====

    /**
     * Check if notification is read.
     */
    public function isRead(): bool
    {
        return !is_null($this->read_at);
    }

    /**
     * Check if notification is unread.
     */
    public function isUnread(): bool
    {
        return is_null($this->read_at);
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead()
    {
        if ($this->isUnread()) {
            $this->update(['read_at' => now()]);
        }
    }

    /**
     * Mark notification as unread.
     */
    public function markAsUnread()
    {
        $this->update(['read_at' => null]);
    }

    /**
     * Get notification title based on type.
     */
    public function getTitle(): string
    {
        return match ($this->type) {
            'JobApplicationReceived' => 'New Job Application',
            'JobApplicationApproved' => 'Job Application Approved',
            'JobApplicationRejected' => 'Job Application Rejected',
            'EventApproved' => 'Event Approved',
            'EventRejected' => 'Event Rejected',
            'NewsArticleApproved' => 'Article Approved',
            'NewsArticleRejected' => 'Article Rejected',
            'PartnershipApproved' => 'Partnership Approved',
            'PartnershipRejected' => 'Partnership Rejected',
            'EventRegistrationConfirmed' => 'Event Registration Confirmed',
            'EventReminder' => 'Event Reminder',
            default => $this->type,
        };
    }

    /**
     * Get notification message based on type and data.
     */
    /**
     * Get notification message based on type and data.
     */
    public function getMessage(): string
    {
        $jobTitle = $this->data['job_title'] ?? 'a job';
        $eventTitle = $this->data['event_title'] ?? 'an event';
        $articleTitle = $this->data['article_title'] ?? 'an article';

        return match ($this->type) {
            'JobApplicationReceived' => "New application for {$jobTitle}",
            'JobApplicationApproved' => "Your application for {$jobTitle} has been approved!",
            'JobApplicationRejected' => "Your application for {$jobTitle} was not selected.",
            'EventApproved' => "{$eventTitle} has been approved",
            'EventRejected' => "{$eventTitle} was rejected",
            'NewsArticleApproved' => "{$articleTitle} has been approved",
            'NewsArticleRejected' => "{$articleTitle} was rejected",
            'PartnershipApproved' => "Your partnership proposal has been approved",
            'PartnershipRejected' => "Your partnership proposal was not approved",
            'EventRegistrationConfirmed' => "You're registered for {$eventTitle}",
            'EventReminder' => "{$eventTitle} is coming up!",
            default => 'You have a new notification',
        };
    }


    /**
     * Get notification icon based on type.
     */
    public function getIcon(): string
    {
        return match ($this->type) {
            'JobApplicationReceived' => 'briefcase',
            'JobApplicationApproved' => 'check-circle',
            'JobApplicationRejected' => 'x-circle',
            'EventApproved' => 'check-circle',
            'EventRejected' => 'x-circle',
            'NewsArticleApproved' => 'check-circle',
            'NewsArticleRejected' => 'x-circle',
            'PartnershipApproved' => 'check-circle',
            'PartnershipRejected' => 'x-circle',
            'EventRegistrationConfirmed' => 'calendar',
            'EventReminder' => 'bell',
            default => 'bell',
        };
    }

    /**
     * Get notification color badge based on type.
     */
    public function getColor(): string
    {
        return match ($this->type) {
            'JobApplicationReceived' => 'info',
            'JobApplicationApproved', 'EventApproved', 'NewsArticleApproved', 'PartnershipApproved' => 'success',
            'JobApplicationRejected', 'EventRejected', 'NewsArticleRejected', 'PartnershipRejected' => 'danger',
            'EventRegistrationConfirmed' => 'success',
            'EventReminder' => 'warning',
            default => 'secondary',
        };
    }

    /**
     * Get relative time since created (e.g., "2 minutes ago").
     */
    public function getCreatedAtDiffForHumans(): string
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Get related notifiable model (polymorphic).
     */
    public function getNotifiable()
    {
        if (!$this->notifiable_type || !$this->notifiable_id) {
            return null;
        }

        $modelClass = $this->notifiable_type;

        if (class_exists($modelClass)) {
            return $modelClass::find($this->notifiable_id);
        }

        return null;
    }

    /**
     * Static method to create notification.
     */
    public static function createNotification($userId, $type, $data = [], $notifiableType = null, $notifiableId = null)
    {
        return self::create([
            'user_id' => $userId,
            'type' => $type,
            'data' => $data,
            'notifiable_type' => $notifiableType,
            'notifiable_id' => $notifiableId,
        ]);
    }
}
