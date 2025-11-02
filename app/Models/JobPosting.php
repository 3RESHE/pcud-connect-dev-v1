<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobPosting extends Model
{
    protected $fillable = [
        'partner_id',
        'approved_by',
        'job_type',
        'title',
        'department',
        'custom_department',
        'experience_level',
        'description',
        'work_setup',
        'location',
        'salary_min',
        'salary_max',
        'salary_period',
        'is_unpaid',
        'duration_months',
        'preferred_start_date',
        'education_requirements',
        'technical_skills',
        'experience_requirements',
        'application_deadline',
        'positions_available',
        'application_process',
        'benefits',
        'status',
        'rejection_reason',
        'published_at',
    ];

    protected $casts = [
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
        'is_unpaid' => 'boolean',
        'duration_months' => 'integer',
        'positions_available' => 'integer',
        'preferred_start_date' => 'date',
        'application_deadline' => 'date',
        'technical_skills' => 'json',
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ===== RELATIONSHIPS =====

    /**
     * Get the partner who created this job posting.
     */
    public function partner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'partner_id');
    }

    /**
     * Get the admin who approved this job posting.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get all applications for this job posting.
     */
    public function applications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }

    // ===== SCOPES =====

    /**
     * Scope: Get published job postings.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope: Get active job postings (published and not expired).
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'published')
            ->where('application_deadline', '>=', now()->toDateString());
    }

    /**
     * Scope: Get job postings by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: Get pending job postings awaiting approval.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Get job postings by partner.
     */
    public function scopeByPartner($query, $partnerId)
    {
        return $query->where('partner_id', $partnerId);
    }

    /**
     * Scope: Get job postings by job type.
     */
    public function scopeByJobType($query, $jobType)
    {
        return $query->where('job_type', $jobType);
    }

    /**
     * Scope: Get job postings by experience level.
     */
    public function scopeByExperienceLevel($query, $level)
    {
        return $query->where('experience_level', $level);
    }

    /**
     * Scope: Get job postings by work setup.
     */
    public function scopeByWorkSetup($query, $setup)
    {
        return $query->where('work_setup', $setup);
    }

    /**
     * Scope: Recently published jobs.
     */
    public function scopeRecentlyPublished($query, $days = 7)
    {
        return $query->where('status', 'published')
            ->where('published_at', '>=', now()->subDays($days));
    }

    // ===== HELPER METHODS =====

    /**
     * Get salary range display.
     */
    public function getSalaryRangeDisplay(): string
    {
        if ($this->is_unpaid) {
            return 'Unpaid';
        }

        if (!$this->salary_min && !$this->salary_max) {
            return 'Not specified';
        }

        if ($this->salary_min && $this->salary_max) {
            $period = $this->salary_period ?? 'per period';
            return "₱{$this->salary_min} - ₱{$this->salary_max} {$period}";
        }

        if ($this->salary_min) {
            $period = $this->salary_period ?? 'per period';
            return "₱{$this->salary_min}+ {$period}";
        }

        $period = $this->salary_period ?? 'per period';
        return "Up to ₱{$this->salary_max} {$period}";
    }

    /**
     * Check if job posting is still open for applications.
     */
    public function isOpenForApplications(): bool
    {
        return $this->status === 'published'
            && $this->application_deadline->isAfter(now());
    }

    /**
     * Check if job posting has expired.
     */
    public function isExpired(): bool
    {
        return $this->application_deadline->isBefore(now());
    }

    /**
     * Get available positions count (total - accepted applications).
     */
    public function getAvailablePositions(): int
    {
        $acceptedCount = $this->applications()
            ->where('status', 'approved')
            ->count();

        return max(0, $this->positions_available - $acceptedCount);
    }

    /**
     * Get total applications count.
     */
    public function getTotalApplications(): int
    {
        return $this->applications()->count();
    }

    /**
     * Check if all positions are filled.
     */
    public function isPositionsFilled(): bool
    {
        return $this->getAvailablePositions() === 0;
    }

    /**
     * Approve this job posting (set status and approver).
     */
    public function approve($adminId)
    {
        $this->update([
            'status' => 'approved',
            'approved_by' => $adminId,
        ]);
    }

    /**
     * Reject this job posting with reason.
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
     * Publish this job posting.
     */
    public function publish()
    {
        $this->update([
            'status' => 'published',
            'published_at' => now(),
        ]);
    }

    /**
     * Pause this job posting.
     */
    public function pause()
    {
        $this->update(['status' => 'paused']);
    }

    /**
     * Complete this job posting.
     */
    public function complete()
    {
        $this->update(['status' => 'completed']);
    }
}
