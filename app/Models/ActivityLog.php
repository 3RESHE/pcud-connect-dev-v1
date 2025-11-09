<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    public $timestamps = false; // Only created_at

    protected $fillable = [
        'user_id',
        'action',
        'description',
        'subject_type',
        'subject_id',
        'properties',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    protected $casts = [
        'properties' => 'json',
        'created_at' => 'datetime',
    ];

    // ===== RELATIONSHIPS =====

    /**
     * Get the user who performed the action.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ===== SCOPES =====

    /**
     * Scope: Get logs by action.
     */
    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope: Get logs by user.
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope: Get logs for specific subject (polymorphic).
     */
    public function scopeForSubject($query, $type, $id)
    {
        return $query->where('subject_type', $type)
            ->where('subject_id', $id);
    }

    /**
     * Scope: Get logs by subject type.
     */
    public function scopeBySubjectType($query, $type)
    {
        return $query->where('subject_type', $type);
    }

    /**
     * Scope: Recent logs.
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days))
            ->orderBy('created_at', 'desc');
    }

    /**
     * Scope: Today's logs.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', now()->toDateString());
    }

    /**
     * Scope: Logs from today onwards.
     */
    public function scopeFromToday($query)
    {
        return $query->where('created_at', '>=', now()->startOfDay());
    }

    /**
     * Scope: Get logs for create action.
     */
    public function scopeCreated($query)
    {
        return $query->where('action', 'created');
    }

    /**
     * Scope: Get logs for update action.
     */
    public function scopeUpdated($query)
    {
        return $query->where('action', 'updated');
    }

    /**
     * Scope: Get logs for delete action.
     */
    public function scopeDeleted($query)
    {
        return $query->where('action', 'deleted');
    }

    /**
     * Scope: Get logs for approval action.
     */
    public function scopeApproved($query)
    {
        return $query->where('action', 'approved');
    }

    /**
     * Scope: Get logs for rejection action.
     */
    public function scopeRejected($query)
    {
        return $query->where('action', 'rejected');
    }

    /**
     * Scope: Get logs for application action.
     */
    public function scopeApplied($query)
    {
        return $query->where('action', 'applied');
    }

    // ===== HELPER METHODS =====

    /**
     * Get action display name.
     */
    public function getActionDisplay(): string
    {
        return match($this->action) {
            'created' => 'Created',
            'updated' => 'Updated',
            'deleted' => 'Deleted',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'published' => 'Published',
            'archived' => 'Archived',
            'restored' => 'Restored',
            'completed' => 'Completed',
            'checked_in' => 'Checked In',
            'applied' => 'Applied',
            'paused' => 'Paused',
            'resumed' => 'Resumed',
            'closed' => 'Closed',
            default => ucfirst($this->action),
        };
    }

    /**
     * Get action color badge.
     */
    public function getActionColor(): string
    {
        return match($this->action) {
            'created' => 'success',
            'updated' => 'info',
            'deleted' => 'danger',
            'approved' => 'success',
            'rejected' => 'danger',
            'published' => 'success',
            'archived' => 'warning',
            'restored' => 'info',
            'completed' => 'success',
            'checked_in' => 'success',
            'applied' => 'indigo',
            'paused' => 'warning',
            'resumed' => 'success',
            'closed' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Get subject type display name.
     */
    public function getSubjectTypeDisplay(): string
    {
        $basename = class_basename($this->subject_type ?? '');

        return match($basename) {
            'JobPosting' => 'Job Posting',
            'JobApplication' => 'Job Application',
            'Event' => 'Event',
            'EventRegistration' => 'Event Registration',
            'NewsArticle' => 'News Article',
            'Partnership' => 'Partnership',
            'User' => 'User',
            'Application' => 'Application',
            default => $basename ?: 'Unknown',
        };
    }

    /**
     * Get subject (polymorphic).
     */
    public function getSubject()
    {
        if (!$this->subject_type || !$this->subject_id) {
            return null;
        }

        $modelClass = $this->subject_type;

        if (class_exists($modelClass)) {
            return $modelClass::find($this->subject_id);
        }

        return null;
    }

    /**
     * Get old values from properties.
     */
    public function getOldValues(): array
    {
        return $this->properties['old'] ?? [];
    }

    /**
     * Get new values from properties.
     */
    public function getNewValues(): array
    {
        return $this->properties['new'] ?? [];
    }

    /**
     * Get changed fields.
     */
    public function getChangedFields(): array
    {
        $old = $this->getOldValues();
        $new = $this->getNewValues();

        $changed = [];

        foreach ($new as $key => $value) {
            if (!isset($old[$key]) || $old[$key] !== $value) {
                $changed[$key] = [
                    'old' => $old[$key] ?? null,
                    'new' => $value,
                ];
            }
        }

        return $changed;
    }

    /**
     * Get changed fields count.
     */
    public function getChangedFieldsCount(): int
    {
        return count($this->getChangedFields());
    }

    /**
     * Get relative time since created (e.g., "2 hours ago").
     */
    public function getCreatedAtDiffForHumans(): string
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Get browser/client info.
     */
    public function getBrowserInfo(): string
    {
        if (!$this->user_agent) {
            return 'Unknown';
        }

        if (strpos($this->user_agent, 'Chrome') !== false) {
            return 'Chrome';
        }
        if (strpos($this->user_agent, 'Firefox') !== false) {
            return 'Firefox';
        }
        if (strpos($this->user_agent, 'Safari') !== false) {
            return 'Safari';
        }
        if (strpos($this->user_agent, 'Edge') !== false) {
            return 'Edge';
        }
        if (strpos($this->user_agent, 'Opera') !== false) {
            return 'Opera';
        }

        return 'Other';
    }

    /**
     * Static method to log activity.
     */
    public static function logActivity($userId, $action, $description = null, $subjectType = null, $subjectId = null, $properties = null)
    {
        return self::create([
            'user_id' => $userId,
            'action' => $action,
            'description' => $description,
            'subject_type' => $subjectType,
            'subject_id' => $subjectId,
            'properties' => is_array($properties) ? json_encode($properties) : $properties,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
    }
}
