<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
     * ✅ FIXED: Get the student profile (no where clause here)
     */
    public function student(): HasOne
    {
        return $this->hasOne(StudentProfile::class, 'user_id', 'applicant_id');
    }

    /**
     * ✅ FIXED: Get the alumni profile (no where clause here)
     */
    public function alumni(): HasOne
    {
        return $this->hasOne(AlumniProfile::class, 'user_id', 'applicant_id');
    }

    // ===== SCOPES =====

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByApplicant($query, $applicantId)
    {
        return $query->where('applicant_id', $applicantId);
    }

    public function scopeByApplicantType($query, $type)
    {
        return $query->where('applicant_type', $type);
    }

    public function scopeUnreviewed($query)
    {
        return $query->whereNull('reviewed_at');
    }

    public function scopeRecentlySubmitted($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function scopeForJob($query, $jobId)
    {
        return $query->where('job_posting_id', $jobId);
    }

    // ===== HELPER METHODS =====

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isReviewed(): bool
    {
        return !is_null($this->reviewed_at);
    }

    public function getStatusDisplay(): array
    {
        return match ($this->status) {
            'pending' => ['text' => 'Pending Review', 'badge' => 'warning'],
            'approved' => ['text' => 'Approved', 'badge' => 'success'],
            'rejected' => ['text' => 'Rejected', 'badge' => 'danger'],
            default => ['text' => $this->status, 'badge' => 'secondary'],
        };
    }

    public function approve()
    {
        $this->update([
            'status' => 'approved',
            'reviewed_at' => now(),
        ]);
    }

    public function reject()
    {
        $this->update([
            'status' => 'rejected',
            'reviewed_at' => now(),
        ]);
    }

    public function getDaysSinceSubmitted(): int
    {
        return $this->created_at->diffInDays(now());
    }

    public function getDisplayName(): string
    {
        return 'Job Application - ' . ($this->jobPosting->title ?? 'Application #' . $this->id);
    }
}
