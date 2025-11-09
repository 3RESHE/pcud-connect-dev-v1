<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobApplication extends Model
{
    protected $fillable = [
        'job_posting_id',
        'applicant_id',
        'applicant_type',
        'cover_letter',
        'resume_path',
        'additional_documents',
        'status',
        'reviewed_at',
    ];

    protected $casts = [
        'additional_documents' => 'json',
        'reviewed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ===== RELATIONSHIPS =====

    /**
     * Get the job posting this application is for.
     */
    public function jobPosting(): BelongsTo
    {
        return $this->belongsTo(JobPosting::class);
    }

    /**
     * Get the applicant (student or alumni).
     */
    public function applicant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'applicant_id');
    }

    /**
     * Get the applicant's student profile.
     */
    public function studentProfile()
    {
        if ($this->applicant && $this->applicant->studentProfile) {
            return $this->applicant->studentProfile;
        }
        return null;
    }

    /**
     * Get the applicant's alumni profile.
     */
    public function alumniProfile()
    {
        if ($this->applicant && $this->applicant->alumniProfile) {
            return $this->applicant->alumniProfile;
        }
        return null;
    }

    // ===== SCOPES =====

    /**
     * Scope: Get pending applications.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Get approved applications.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope: Get rejected applications.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope: Get applications by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: Get applications by applicant.
     */
    public function scopeByApplicant($query, $applicantId)
    {
        return $query->where('applicant_id', $applicantId);
    }

    /**
     * Scope: Get applications by applicant type.
     */
    public function scopeByApplicantType($query, $type)
    {
        return $query->where('applicant_type', $type);
    }

    /**
     * Scope: Get unreviewed applications.
     */
    public function scopeUnreviewed($query)
    {
        return $query->whereNull('reviewed_at');
    }

    /**
     * Scope: Get recently submitted applications.
     */
    public function scopeRecentlySubmitted($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Scope: Get applications for a specific job.
     */
    public function scopeForJob($query, $jobId)
    {
        return $query->where('job_posting_id', $jobId);
    }

    // ===== HELPER METHODS =====

    /**
     * Check if application is pending review.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if application has been reviewed.
     */
    public function isReviewed(): bool
    {
        return !is_null($this->reviewed_at);
    }

    /**
     * Get status display text with badge color.
     */
    public function getStatusDisplay(): array
    {
        return match ($this->status) {
            'pending' => ['text' => 'Pending Review', 'badge' => 'warning'],
            'approved' => ['text' => 'Approved', 'badge' => 'success'],
            'rejected' => ['text' => 'Rejected', 'badge' => 'danger'],
            default => ['text' => $this->status, 'badge' => 'secondary'],
        };
    }

    /**
     * Approve this application.
     */
    public function approve()
    {
        $this->update([
            'status' => 'approved',
            'reviewed_at' => now(),
        ]);
    }

    /**
     * Reject this application.
     */
    public function reject()
    {
        $this->update([
            'status' => 'rejected',
            'reviewed_at' => now(),
        ]);
    }

    /**
     * Get days since application was submitted.
     */
    public function getDaysSinceSubmitted(): int
    {
        return $this->created_at->diffInDays(now());
    }

    /**
     * Get applicant's profile based on applicant_type.
     */
    public function getApplicantProfile()
    {
        if ($this->applicant_type === 'student') {
            return $this->studentProfile();
        }

        return $this->alumniProfile();
    }

    /**
     * Get display name for activity logs
     */
    public function getDisplayName(): string
    {
        return 'Job Application - ' . $this->jobPosting->title ?? 'Application #' . $this->id;
    }
}
