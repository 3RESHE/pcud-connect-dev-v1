<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Experience extends Model
{
    protected $fillable = [
        'user_id',
        'user_type',
        'role_position',
        'organization',
        'start_date',
        'end_date',
        'is_current',
        'experience_type',
        'description',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_current' => 'boolean',
    ];

    // ===== RELATIONSHIPS =====

    /**
     * Get the user who owns this experience.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ===== SCOPES =====

    /**
     * Scope: Get only current experiences.
     */
    public function scopeCurrent($query)
    {
        return $query->where('is_current', true);
    }

    /**
     * Scope: Get experiences by type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('experience_type', $type);
    }

    /**
     * Scope: Get experiences for students only.
     */
    public function scopeForStudents($query)
    {
        return $query->where('user_type', 'student');
    }

    /**
     * Scope: Get experiences for alumni only.
     */
    public function scopeForAlumni($query)
    {
        return $query->where('user_type', 'alumni');
    }

    // ===== HELPER METHODS =====

    /**
     * Get the duration of experience in months.
     */
    public function getDurationInMonths(): ?int
    {
        if (!$this->start_date) {
            return null;
        }

        $endDate = $this->is_current ? now() : $this->end_date;

        if (!$endDate) {
            return null;
        }

        return $this->start_date->diffInMonths($endDate);
    }

    /**
     * Get formatted duration string.
     */
    public function getFormattedDuration(): string
    {
        if (!$this->start_date) {
            return 'Duration not specified';
        }

        if ($this->is_current) {
            $months = $this->start_date->diffInMonths(now());
            return $months > 0 ? "{$months} months (current)" : "Current";
        }

        if (!$this->end_date) {
            return 'End date not specified';
        }

        $months = $this->start_date->diffInMonths($this->end_date);
        return $months > 0 ? "{$months} months" : "Less than a month";
    }

    // ===== MODEL EVENTS =====

    /**
     * Business logic validation.
     */
    protected static function booted()
    {
        static::saving(function ($experience) {
            // If is_current is true, end_date should be null
            if ($experience->is_current) {
                $experience->end_date = null;
            }

            // Validate user_type matches user's actual role
            $user = $experience->user;
            if ($user && !in_array($user->role, ['student', 'alumni'])) {
                throw new \Exception('Only students and alumni can have experiences.');
            }

            if ($user && $experience->user_type !== $user->role) {
                throw new \Exception('User type must match the user\'s actual role.');
            }
        });
    }
}
