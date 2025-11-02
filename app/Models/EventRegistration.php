<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventRegistration extends Model
{
    protected $fillable = [
        'event_id',
        'user_id',
        'user_type',
        'registration_type',
        'attendance_status',
        'checked_in_at',
    ];

    protected $casts = [
        'checked_in_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ===== RELATIONSHIPS =====

    /**
     * Get the event this registration is for.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the user who registered.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ===== SCOPES =====

    /**
     * Scope: Get registered attendees.
     */
    public function scopeRegistered($query)
    {
        return $query->where('attendance_status', 'registered');
    }

    /**
     * Scope: Get attended registrations.
     */
    public function scopeAttended($query)
    {
        return $query->where('attendance_status', 'attended');
    }

    /**
     * Scope: Get no-show registrations.
     */
    public function scopeNoShow($query)
    {
        return $query->where('attendance_status', 'no_show');
    }

    /**
     * Scope: Get registrations by attendance status.
     */
    public function scopeByAttendanceStatus($query, $status)
    {
        return $query->where('attendance_status', $status);
    }

    /**
     * Scope: Get online registrations.
     */
    public function scopeOnlineRegistrations($query)
    {
        return $query->where('registration_type', 'online');
    }

    /**
     * Scope: Get walk-in registrations.
     */
    public function scopeWalkinRegistrations($query)
    {
        return $query->where('registration_type', 'walkin');
    }

    /**
     * Scope: Get checked-in attendees.
     */
    public function scopeCheckedIn($query)
    {
        return $query->whereNotNull('checked_in_at');
    }

    /**
     * Scope: Get by user type.
     */
    public function scopeByUserType($query, $userType)
    {
        return $query->where('user_type', $userType);
    }

    /**
     * Scope: Recently registered.
     */
    public function scopeRecentlyRegistered($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // ===== HELPER METHODS =====

    /**
     * Check if attendee has checked in.
     */
    public function hasCheckedIn(): bool
    {
        return !is_null($this->checked_in_at);
    }

    /**
     * Check in the attendee.
     */
    public function checkIn()
    {
        $this->update([
            'checked_in_at' => now(),
            'attendance_status' => 'attended',
        ]);
    }

    /**
     * Mark as attended without check-in.
     */
    public function markAttended()
    {
        $this->update(['attendance_status' => 'attended']);
    }

    /**
     * Mark as no-show.
     */
    public function markNoShow()
    {
        $this->update(['attendance_status' => 'no_show']);
    }

    /**
     * Get days since registration.
     */
    public function getDaysSinceRegistration(): int
    {
        return $this->created_at->diffInDays(now());
    }

    /**
     * Get check-in time display.
     */
    public function getCheckInTimeDisplay(): string
    {
        if (!$this->hasCheckedIn()) {
            return 'Not checked in';
        }

        return $this->checked_in_at->format('M d, Y h:i A');
    }

    /**
     * Get attendance status display with badge color.
     */
    public function getAttendanceStatusDisplay(): array
    {
        return match($this->attendance_status) {
            'registered' => ['text' => 'Registered', 'badge' => 'info'],
            'attended' => ['text' => 'Attended', 'badge' => 'success'],
            'no_show' => ['text' => 'No Show', 'badge' => 'danger'],
            default => ['text' => $this->attendance_status, 'badge' => 'secondary'],
        };
    }

    /**
     * Get registration type display.
     */
    public function getRegistrationTypeDisplay(): string
    {
        return match($this->registration_type) {
            'online' => 'Online Registration',
            'walkin' => 'Walk-in',
            default => $this->registration_type,
        };
    }

    /**
     * Get user's profile based on user_type.
     */
    public function getUserProfile()
    {
        if ($this->user_type === 'student') {
            return $this->user->studentProfile;
        }

        return $this->user->alumniProfile;
    }
}
