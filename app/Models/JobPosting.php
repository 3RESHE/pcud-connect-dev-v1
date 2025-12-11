<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class JobPosting extends Model
{
    protected $table = 'job_postings';

    protected $fillable = [
        'partner_id',
        'department_id', // ✅ CHANGED from 'department' & 'custom_department'
        'approved_by',
        'rejected_by',
        'unpublished_by',
        'job_type',
        'title',
        'experience_level',
        'description',
        'benefits',
        'work_setup',
        'location',
        'salary_min',
        'salary_max',
        'salary_period',
        'is_unpaid',
        'is_featured',
        'duration_months',
        'preferred_start_date',
        'education_requirements',
        'technical_skills',
        'experience_requirements',
        'application_deadline',
        'positions_available',
        'application_process',
        'application_instructions',
        'status',
        'sub_status',
        'rejection_reason',
        'unpublish_reason',
        'published_at',
        'approved_at',
        'rejected_at',
        'unpublished_at',
        'closed_at',
    ];

    protected $casts = [
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
        'is_unpaid' => 'boolean',
        'is_featured' => 'boolean',
        'duration_months' => 'integer',
        'positions_available' => 'integer',
        'preferred_start_date' => 'date',
        'application_deadline' => 'date',
        'published_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'unpublished_at' => 'datetime',
        'closed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'technical_skills' => 'json',
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
     * Get the department for this job posting. ✅ NEW
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    /**
     * Get the partner's profile (company information).
     */
    public function partnerProfile(): HasOneThrough
    {
        return $this->hasOneThrough(
            PartnerProfile::class,
            User::class,
            'id',           // Foreign key on users table
            'user_id',      // Foreign key on partner_profiles table
            'partner_id',   // Local key on job_postings table
            'id'            // Local key on users table
        );
    }

    /**
     * Get the admin who approved this job posting.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the admin who rejected this job posting.
     */
    public function rejector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    /**
     * Get the admin who unpublished this job posting.
     */
    public function unpublisher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'unpublished_by');
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
     * Scope: Get rejected job postings.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope: Get unpublished job postings.
     */
    public function scopeUnpublished($query)
    {
        return $query->where('status', 'unpublished');
    }

    /**
     * Scope: Get job postings by partner.
     */
    public function scopeByPartner($query, $partnerId)
    {
        return $query->where('partner_id', $partnerId);
    }

    /**
     * Scope: Get job postings by department. ✅ NEW
     */
    public function scopeByDepartment($query, $departmentId)
    {
        return $query->where('department_id', $departmentId);
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
     * Scope: Featured jobs.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)
            ->where('status', 'approved')
            ->where('sub_status', 'active');
    }

    /**
     * Scope: Search by title, description, or department. ✅ UPDATED
     */
    public function scopeSearch($query, $keyword)
    {
        return $query->where('title', 'like', "%{$keyword}%")
            ->orWhere('description', 'like', "%{$keyword}%")
            ->orWhereHas('department', function ($query) use ($keyword) {
                $query->where('title', 'like', "%{$keyword}%")
                    ->orWhere('code', 'like', "%{$keyword}%");
            });
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
            $period = $this->salary_period ? strtolower($this->salary_period) : 'monthly';
            return "₱" . number_format($this->salary_min) . " - ₱" . number_format($this->salary_max) . " /{$period}";
        }

        if ($this->salary_min) {
            $period = $this->salary_period ? strtolower($this->salary_period) : 'monthly';
            return "₱" . number_format($this->salary_min) . "+ /{$period}";
        }

        $period = $this->salary_period ? strtolower($this->salary_period) : 'monthly';
        return "Up to ₱" . number_format($this->salary_max) . " /{$period}";
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
     * Check if job posting is featured.
     */
    public function isFeatured(): bool
    {
        return $this->is_featured === true;
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
            'unpublished' => 'Unpublished',
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

    /**
     * Get salary period display.
     */
    public function getSalaryPeriodDisplay(): string
    {
        return match($this->salary_period) {
            'monthly' => 'Monthly',
            'hourly' => 'Hourly',
            'daily' => 'Daily',
            'project' => 'Per Project',
            default => 'Per Period',
        };
    }

    // ===== ACTION METHODS =====

    /**
     * Approve this job posting.
     */
    public function approve($adminId)
    {
        $this->update([
            'status' => 'approved',
            'sub_status' => 'active',
            'approved_by' => $adminId,
            'approved_at' => now(),
            'published_at' => now(),
            'rejected_by' => null,
            'rejection_reason' => null,
            'rejected_at' => null,
        ]);
    }

    /**
     * Reject this job posting with reason.
     */
    public function reject($adminId, $reason)
    {
        $this->update([
            'status' => 'rejected',
            'sub_status' => null,
            'rejected_by' => $adminId,
            'rejection_reason' => $reason,
            'rejected_at' => now(),
            'approved_by' => null,
            'approved_at' => null,
            'published_at' => null,
        ]);
    }

    /**
     * Unpublish this job posting.
     */
    public function unpublish($adminId, $reason = null)
    {
        $this->update([
            'status' => 'unpublished',
            'sub_status' => null,
            'unpublished_by' => $adminId,
            'unpublish_reason' => $reason,
            'unpublished_at' => now(),
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
     * Feature this job posting.
     */
    public function feature()
    {
        $this->update([
            'is_featured' => true,
        ]);
    }

    /**
     * Unfeature this job posting.
     */
    public function unfeature()
    {
        $this->update([
            'is_featured' => false,
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
            'sub_status' => 'paused',
        ]);
    }

    /**
     * Check if the job posting can be edited.
     */
    public function canBeEdited(): bool
    {
        return in_array($this->status, ['pending', 'rejected']);
    }

    /**
     * Check if the job posting can be withdrawn.
     */
    public function canBeWithdrawn(): bool
    {
        return in_array($this->status, ['pending', 'rejected']);
    }

    /**
     * Check if the job posting can be closed.
     */
    public function canBeClosed(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if the job posting can be unpublished.
     */
    public function canBeUnpublished(): bool
    {
        return $this->status === 'approved';
    }
}
