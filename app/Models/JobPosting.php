<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
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
        'sub_status',
        'rejection_reason',
        'published_at',
        'closed_at',
    ];

    protected $casts = [
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
        'is_unpaid' => 'boolean',
        'duration_months' => 'integer',
        'positions_available' => 'integer',
        'preferred_start_date' => 'date',
        'application_deadline' => 'date',
        'published_at' => 'datetime',
        'closed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ✅ FIX: Auto-decode technical_skills from JSON
    protected function technicalSkills(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? json_decode($value, true) : [],
        );
    }

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
        return $this->hasMany(JobApplication::class, 'job_posting_id');
    }

    // ===== SCOPES =====

    /**
     * Scope: Get approved job postings.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope: Get active (non-paused) job postings.
     */
    public function scopeActiveOnly($query)
    {
        return $query->where('status', 'approved')
            ->where('sub_status', 'active')
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
        return $query->where('status', 'approved')
            ->where('published_at', '>=', now()->subDays($days));
    }

    /**
     * Scope: Search by title, description, or department.
     */
    public function scopeSearch($query, $keyword)
    {
        return $query->where('title', 'like', "%{$keyword}%")
            ->orWhere('description', 'like', "%{$keyword}%")
            ->orWhere('department', 'like', "%{$keyword}%");
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
            return "₱" . number_format($this->salary_min) . " - ₱" . number_format($this->salary_max) . " {$period}";
        }

        if ($this->salary_min) {
            $period = $this->salary_period ?? 'per period';
            return "₱" . number_format($this->salary_min) . "+ {$period}";
        }

        $period = $this->salary_period ?? 'per period';
        return "Up to ₱" . number_format($this->salary_max) . " {$period}";
    }

    /**
     * Check if job posting is still open for applications.
     */
    public function isOpenForApplications(): bool
    {
        return $this->status === 'approved'
            && $this->sub_status === 'active'
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
     * Check if job posting is paused.
     */
    public function isPaused(): bool
    {
        return $this->status === 'approved' && $this->sub_status === 'paused';
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
     * Get job posting status display.
     */
    public function getStatusDisplay(): string
    {
        return match($this->status) {
            'pending' => 'Pending Approval',
            'approved' => $this->sub_status === 'paused' ? 'Paused' : 'Approved',
            'rejected' => 'Rejected',
            'completed' => 'Completed',
            default => ucfirst($this->status),
        };
    }

    /**
     * Get job type display.
     */
    public function getJobTypeDisplay(): string
    {
        return match($this->job_type) {
            'fulltime' => 'Full-time',
            'parttime' => 'Part-time',
            'internship' => 'Internship',
            'other' => 'Other',
            default => ucfirst($this->job_type),
        };
    }

    /**
     * Get experience level display.
     */
    public function getExperienceLevelDisplay(): string
    {
        return match($this->experience_level) {
            'entry' => 'Entry Level',
            'mid' => 'Mid Level',
            'senior' => 'Senior Level (6+ years)',
            'lead' => 'Lead/Manager',
            'student' => 'Student/Fresh Graduate',
            default => ucfirst($this->experience_level),
        };
    }

    /**
     * Get work setup display.
     */
    public function getWorkSetupDisplay(): string
    {
        return match($this->work_setup) {
            'onsite' => 'On-site',
            'remote' => 'Remote',
            'hybrid' => 'Hybrid',
            default => ucfirst($this->work_setup),
        };
    }

    // ===== ACTION METHODS =====

    /**
     * Approve this job posting (set status and approver).
     */
    public function approve($adminId)
    {
        $this->update([
            'status' => 'approved',
            'sub_status' => 'active',
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
     * Pause this job posting.
     */
    public function pausePosting()
    {
        $this->update([
            'status' => 'approved',
            'sub_status' => 'paused',
        ]);
    }

    /**
     * Resume this job posting.
     */
    public function resumePosting()
    {
        $this->update([
            'status' => 'approved',
            'sub_status' => 'active',
        ]);
    }

    /**
     * Close this job posting.
     */
    public function closePosting()
    {
        $this->update([
            'status' => 'completed',
            'closed_at' => now(),
        ]);
    }

    /**
     * Check if the job posting can be edited.
     */
    public function canBeEdited(): bool
    {
        return in_array($this->status, ['pending', 'rejected', 'approved']);
    }

    /**
     * Check if the job posting can be withdrawn.
     */
    public function canBeWithdrawn(): bool
    {
        return $this->status === 'pending' || $this->status === 'rejected';
    }
}
