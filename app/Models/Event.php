<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    protected $fillable = [
        'created_by',
        'approved_by',
        'title',
        'description',
        'event_format',
        'event_date',
        'is_multiday',
        'end_date',
        'start_time',
        'end_time',
        'venue_name',
        'venue_capacity',
        'venue_address',
        'platform',
        'custom_platform',
        'virtual_capacity',
        'meeting_link',
        'registration_required',
        'walkin_allowed',
        'registration_deadline',
        'max_attendees',
        'target_audience',
        'selected_departments',
        'event_tags',
        'contact_person',
        'contact_email',
        'contact_phone',
        'special_instructions',
        'event_image',
        'status',
        'rejection_reason',
        'published_at',
    ];

    protected $casts = [
        'event_date' => 'date',
        'end_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_multiday' => 'boolean',
        'registration_required' => 'boolean',
        'walkin_allowed' => 'boolean',
        'registration_deadline' => 'date',
        'venue_capacity' => 'integer',
        'virtual_capacity' => 'integer',
        'max_attendees' => 'integer',
        'selected_departments' => 'json',
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ===== RELATIONSHIPS =====

    /**
     * Get the staff member who created this event.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the admin who approved this event.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get all registrations for this event.
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class);
    }

    // ===== SCOPES =====

    /**
     * Scope: Get published events.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope: Get upcoming events.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('status', 'published')
            ->where('event_date', '>=', now()->toDateString())
            ->orderBy('event_date', 'asc');
    }

    /**
     * Scope: Get ongoing events.
     */
    public function scopeOngoing($query)
    {
        return $query->where('status', 'ongoing');
    }

    /**
     * Scope: Get past events.
     */
    public function scopePast($query)
    {
        return $query->where('event_date', '<', now()->toDateString());
    }

    /**
     * Scope: Get pending events awaiting approval.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Get events by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: Get events by creator.
     */
    public function scopeByCreator($query, $creatorId)
    {
        return $query->where('created_by', $creatorId);
    }

    /**
     * Scope: Get events by format.
     */
    public function scopeByFormat($query, $format)
    {
        return $query->where('event_format', $format);
    }

    /**
     * Scope: Get events targeting specific audience.
     */
    public function scopeByTargetAudience($query, $audience)
    {
        return $query->where('target_audience', $audience);
    }

    /**
     * Scope: Get events with registration required.
     */
    public function scopeRequiresRegistration($query)
    {
        return $query->where('registration_required', true);
    }

    /**
     * Scope: Get events allowing walk-in.
     */
    public function scopeAllowsWalkin($query)
    {
        return $query->where('walkin_allowed', true);
    }

    /**
     * Scope: Recently published events.
     */
    public function scopeRecentlyPublished($query, $days = 7)
    {
        return $query->where('status', 'published')
            ->where('published_at', '>=', now()->subDays($days));
    }

    // ===== HELPER METHODS =====

    /**
     * Get event datetime details.
     */
    public function getEventDatetimeDisplay(): string
    {
        $date = $this->event_date->format('M d, Y');
        $time = $this->start_time ? $this->start_time->format('h:i A') : 'TBA';

        if ($this->is_multiday && $this->end_date) {
            $endDate = $this->end_date->format('M d, Y');
            return "{$date} - {$endDate} at {$time}";
        }

        return "{$date} at {$time}";
    }

    /**
     * Check if event is still open for registration.
     */
    public function isOpenForRegistration(): bool
    {
        if (!$this->registration_required) {
            return $this->status === 'published';
        }

        $currentDate = now()->toDateString();

        return $this->status === 'published'
            && $currentDate <= $this->event_date->toDateString()
            && (!$this->registration_deadline || $currentDate <= $this->registration_deadline->toDateString());
    }

    /**
     * Check if registration deadline has passed.
     */
    public function isRegistrationDeadlinePassed(): bool
    {
        return $this->registration_deadline
            && now()->toDateString() > $this->registration_deadline->toDateString();
    }

    /**
     * Get total registrations count.
     */
    public function getTotalRegistrations(): int
    {
        return $this->registrations()->count();
    }

    /**
     * Get attendee count (registered + attended).
     */
    public function getAttendeeCount(): int
    {
        return $this->registrations()
            ->whereIn('attendance_status', ['registered', 'attended'])
            ->count();
    }

    /**
     * Get attended count.
     */
    public function getAttendedCount(): int
    {
        return $this->registrations()
            ->where('attendance_status', 'attended')
            ->count();
    }

    /**
     * Check if event is at capacity.
     */
    public function isAtCapacity(): bool
    {
        if (!$this->max_attendees) {
            return false;
        }

        return $this->getTotalRegistrations() >= $this->max_attendees;
    }

    /**
     * Get available registration slots.
     */
    public function getAvailableSlots(): ?int
    {
        if (!$this->max_attendees) {
            return null;
        }

        return max(0, $this->max_attendees - $this->getTotalRegistrations());
    }

    /**
     * Get registration slots percentage used.
     */
    public function getRegistrationPercentage(): float
    {
        if (!$this->max_attendees || $this->max_attendees === 0) {
            return 0;
        }

        return round(($this->getTotalRegistrations() / $this->max_attendees) * 100, 2);
    }

    /**
     * Get event location display.
     */
    public function getLocationDisplay(): string
    {
        return match($this->event_format) {
            'inperson' => $this->venue_name ?? 'TBA',
            'virtual' => $this->platform ?? 'Virtual',
            'hybrid' => ($this->venue_name ?? 'TBA') . ' / ' . ($this->platform ?? 'Virtual'),
            default => 'TBA',
        };
    }

    /**
     * Approve this event.
     */
    public function approve($adminId)
    {
        $this->update([
            'status' => 'approved',
            'approved_by' => $adminId,
        ]);
    }

    /**
     * Reject this event with reason.
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
     * Publish this event.
     */
    public function publish()
    {
        $this->update([
            'status' => 'published',
            'published_at' => now(),
        ]);
    }

    /**
     * Mark event as ongoing.
     */
    public function markAsOngoing()
    {
        $this->update(['status' => 'ongoing']);
    }

    /**
     * Mark event as completed.
     */
    public function markAsCompleted()
    {
        $this->update(['status' => 'completed']);
    }
}
