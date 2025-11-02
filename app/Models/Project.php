<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    protected $fillable = [
        'user_id',
        'user_type',
        'title',
        'url',
        'start_date',
        'end_date',
        'description',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // ===== RELATIONSHIPS =====

    /**
     * Get the user who owns this project.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ===== SCOPES =====

    /**
     * Scope: Get projects for students only.
     */
    public function scopeForStudents($query)
    {
        return $query->where('user_type', 'student');
    }

    /**
     * Scope: Get projects for alumni only.
     */
    public function scopeForAlumni($query)
    {
        return $query->where('user_type', 'alumni');
    }

    /**
     * Scope: Get projects with URLs only (public/portfolio projects).
     */
    public function scopeWithUrl($query)
    {
        return $query->whereNotNull('url');
    }

    // ===== HELPER METHODS =====

    /**
     * Check if project has a URL.
     */
    public function hasUrl(): bool
    {
        return !is_null($this->url);
    }

    /**
     * Get the duration of project in months.
     */
    public function getDurationInMonths(): ?int
    {
        if (!$this->start_date || !$this->end_date) {
            return null;
        }

        return $this->start_date->diffInMonths($this->end_date);
    }

    /**
     * Get formatted duration string.
     */
    public function getFormattedDuration(): string
    {
        if (!$this->start_date || !$this->end_date) {
            return 'Duration not specified';
        }

        $months = $this->start_date->diffInMonths($this->end_date);

        if ($months === 0) {
            return 'Less than a month';
        }

        if ($months < 12) {
            return "{$months} " . ($months === 1 ? 'month' : 'months');
        }

        $years = floor($months / 12);
        $remainingMonths = $months % 12;

        $result = "{$years} " . ($years === 1 ? 'year' : 'years');

        if ($remainingMonths > 0) {
            $result .= " {$remainingMonths} " . ($remainingMonths === 1 ? 'month' : 'months');
        }

        return $result;
    }

    // ===== MODEL EVENTS =====

    /**
     * Business logic validation.
     */
    protected static function booted()
    {
        static::saving(function ($project) {
            // Validate user_type matches user's actual role
            $user = $project->user;
            if ($user && !in_array($user->role, ['student', 'alumni'])) {
                throw new \Exception('Only students and alumni can have projects.');
            }

            if ($user && $project->user_type !== $user->role) {
                throw new \Exception('User type must match the user\'s actual role.');
            }
        });
    }
}
