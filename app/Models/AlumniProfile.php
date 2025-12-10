<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AlumniProfile extends Model
{
    protected $table = 'alumni_profiles';

    protected $fillable = [
        'user_id',
        'department_id',
        'is_fresh_grad',
        'profile_photo',
        'headline',
        'personal_email',
        'phone',
        'current_location',
        'linkedin_url',
        'github_url',
        'portfolio_url',
        'resumes',
        'certifications',
        'degree_program',
        'graduation_year',
        'gwa',
        'honors',
        'thesis_title',
        'professional_summary',
        'current_organization',
        'current_position',
        'current_industry',
        'willing_to_relocate',
        'organizations',
        'technical_skills',
        'soft_skills',
        'languages',
        'profile_complete',
        'profile_completed_at',
        'is_public',
    ];

    protected $casts = [
        'is_fresh_grad' => 'boolean',
        'graduation_year' => 'integer',
        'gwa' => 'decimal:2',
        'willing_to_relocate' => 'boolean',
        'profile_complete' => 'boolean',
        'is_public' => 'boolean',
        'profile_completed_at' => 'datetime',
        'resumes' => 'array',
        'certifications' => 'array',
        'technical_skills' => 'array',
        'soft_skills' => 'array',
        'languages' => 'array',
    ];

    // ===== RELATIONSHIPS =====

    /**
     * Get the user that owns this alumni profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the department this alumni graduated from.
     * Note: This is optional - alumni may not have a department assigned.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get all experiences for this alumni.
     */
    public function experiences(): HasMany
    {
        return $this->user->experiences()->where('user_type', 'alumni');
    }

    /**
     * Get all projects for this alumni.
     */
    public function projects(): HasMany
    {
        return $this->user->projects()->where('user_type', 'alumni');
    }

    // ===== SCOPES =====

    /**
     * Scope: Get only fresh graduates.
     */
    public function scopeFreshGraduates($query)
    {
        return $query->where('is_fresh_grad', true);
    }

    /**
     * Scope: Get only experienced alumni.
     */
    public function scopeExperienced($query)
    {
        return $query->where('is_fresh_grad', false);
    }

    /**
     * Scope: Get only public profiles.
     */
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    /**
     * Scope: Get only complete profiles.
     */
    public function scopeComplete($query)
    {
        return $query->where('profile_complete', true);
    }

    /**
     * Scope: Get alumni from specific graduation year.
     */
    public function scopeGraduationYear($query, $year)
    {
        return $query->where('graduation_year', $year);
    }

    /**
     * Scope: Get alumni from specific industry.
     */
    public function scopeIndustry($query, $industry)
    {
        return $query->where('current_industry', $industry);
    }

    /**
     * Scope: Get alumni from a specific department.
     */
    public function scopeByDepartment($query, $departmentId)
    {
        return $query->where('department_id', $departmentId);
    }

    /**
     * Scope: Get alumni without a department assigned.
     */
    public function scopeWithoutDepartment($query)
    {
        return $query->whereNull('department_id');
    }

    /**
     * Scope: Get alumni with a department assigned.
     */
    public function scopeWithDepartment($query)
    {
        return $query->whereNotNull('department_id');
    }

    /**
     * Scope: Search alumni by name, organization, or skills.
     */
    public function scopeSearch($query, $keyword)
    {
        return $query->whereHas('user', function ($q) use ($keyword) {
            $q->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%$keyword%"]);
        })->orWhere('current_organization', 'LIKE', "%$keyword%")
          ->orWhere('technical_skills', 'LIKE', "%$keyword%")
          ->orWhere('headline', 'LIKE', "%$keyword%");
    }

    // ===== HELPER METHODS =====

    /**
     * Get alumni full name.
     */
    public function getFullName(): string
    {
        return $this->user->getFullNameAttribute();
    }

    /**
     * Get alumni email (priority: personal_email > user email).
     */
    public function getEmail(): string
    {
        return $this->personal_email ?? $this->user->email ?? 'N/A';
    }

    /**
     * Get alumni department name.
     */
    public function getDepartmentName(): string
    {
        return $this->department?->name ?? 'Not Assigned';
    }

    /**
     * Check if alumni has a department assigned.
     */
    public function hasDepartment(): bool
    {
        return !is_null($this->department_id);
    }

    /**
     * Check if alumni is fresh graduate.
     */
    public function isFreshGraduate(): bool
    {
        return $this->is_fresh_grad === true;
    }

    /**
     * Get fresh grad status display.
     */
    public function getFreshGradStatusDisplay(): string
    {
        return $this->is_fresh_grad ? 'Fresh Graduate' : 'Experienced Professional';
    }

    /**
     * Check if profile is complete based on employment status.
     * Fresh grads: Only require academic info
     * Experienced: Require professional info too
     */
    public function isProfileComplete(): bool
    {
        // Required for both fresh grad and experienced
        $basicRequired = !empty($this->personal_email) &&
                         !empty($this->phone) &&
                         !empty($this->current_location) &&
                         !empty($this->degree_program) &&
                         !empty($this->graduation_year);

        // Fresh graduates - only basic info required
        if ($this->is_fresh_grad) {
            return $basicRequired;
        }

        // Experienced professionals - also need professional info
        return $basicRequired &&
               !empty($this->headline) &&
               !empty($this->professional_summary) &&
               !empty($this->current_organization) &&
               !empty($this->current_position);
    }

    /**
     * Mark profile as complete.
     */
    public function markAsComplete(): void
    {
        $this->update([
            'profile_complete' => true,
            'profile_completed_at' => now(),
        ]);
    }

    /**
     * Get graduation year display.
     */
    public function getGraduationYearDisplay(): string
    {
        return $this->graduation_year ? "Class of {$this->graduation_year}" : 'N/A';
    }

    /**
     * Get GWA (GPA) display.
     */
    public function getGWADisplay(): string
    {
        return $this->gwa ? number_format($this->gwa, 2) : 'N/A';
    }

    /**
     * Get honors display.
     */
    public function getHonorsDisplay(): string
    {
        return $this->honors ?? 'No honors';
    }

    /**
     * Check if alumni has work experience.
     */
    public function hasWorkExperience(): bool
    {
        return $this->user->experiences()
                  ->where('user_type', 'alumni')
                  ->count() > 0;
    }

    /**
     * Check if alumni has projects.
     */
    public function hasProjects(): bool
    {
        return $this->user->projects()
                  ->where('user_type', 'alumni')
                  ->count() > 0;
    }

    /**
     * Check if alumni has resumes uploaded.
     */
    public function hasResumes(): bool
    {
        return !empty($this->resumes) && is_array($this->resumes) && count($this->resumes) > 0;
    }

    /**
     * Check if alumni has certifications uploaded.
     */
    public function hasCertifications(): bool
    {
        return !empty($this->certifications) && is_array($this->certifications) && count($this->certifications) > 0;
    }

    /**
     * Get current/latest work experience.
     */
    public function getCurrentExperience()
    {
        return $this->user->experiences()
                  ->where('user_type', 'alumni')
                  ->where('is_current', true)
                  ->first();
    }

    /**
     * Get technical skills array.
     */
    public function getTechnicalSkillsArray(): array
    {
        if (!$this->technical_skills) {
            return [];
        }

        // Handle both JSON array and comma-separated string
        if (is_array($this->technical_skills)) {
            return $this->technical_skills;
        }

        return array_filter(array_map('trim', explode(',', $this->technical_skills)));
    }

    /**
     * Get soft skills array.
     */
    public function getSoftSkillsArray(): array
    {
        if (!$this->soft_skills) {
            return [];
        }

        // Handle both JSON array and comma-separated string
        if (is_array($this->soft_skills)) {
            return $this->soft_skills;
        }

        return array_filter(array_map('trim', explode(',', $this->soft_skills)));
    }

    /**
     * Get certifications array.
     */
    public function getCertificationsArray(): array
    {
        // Handle JSON array format
        if (is_array($this->certifications)) {
            return $this->certifications;
        }

        if (!$this->certifications) {
            return [];
        }

        // Handle comma-separated string format (legacy)
        return array_filter(array_map('trim', explode(',', $this->certifications)));
    }

    /**
     * Get resumes array.
     */
    public function getResumesArray(): array
    {
        if (!$this->resumes) {
            return [];
        }

        return is_array($this->resumes) ? $this->resumes : [];
    }

    /**
     * Get languages array.
     */
    public function getLanguagesArray(): array
    {
        if (!$this->languages) {
            return [];
        }

        // Handle both JSON array and comma-separated string
        if (is_array($this->languages)) {
            return $this->languages;
        }

        return array_filter(array_map('trim', explode(',', $this->languages)));
    }

    /**
     * Get profile completion percentage.
     */
    public function getProfileCompletionPercentage(): int
    {
        if ($this->is_fresh_grad) {
            // Fresh grad fields (8 fields)
            $fields = [
                'profile_photo',
                'personal_email',
                'phone',
                'current_location',
                'degree_program',
                'graduation_year',
                'technical_skills',
                'resumes',
            ];
        } else {
            // Experienced fields (12 fields)
            $fields = [
                'profile_photo',
                'headline',
                'personal_email',
                'phone',
                'current_location',
                'linkedin_url',
                'degree_program',
                'graduation_year',
                'professional_summary',
                'current_organization',
                'current_position',
                'technical_skills',
            ];
        }

        $filledCount = 0;
        foreach ($fields as $field) {
            if (!empty($this->$field)) {
                $filledCount++;
            }
        }

        return round(($filledCount / count($fields)) * 100);
    }

    // ===== MODEL EVENTS =====

    /**
     * Business logic validation.
     */
    protected static function booted()
    {
        static::saving(function ($profile) {
            // Ensure user is actually alumni
            $user = $profile->user;
            if ($user && $user->role !== 'alumni') {
                throw new \Exception('Alumni profile can only be created for alumni users.');
            }

            // Auto-complete check
            if ($profile->isProfileComplete() && !$profile->profile_complete) {
                $profile->profile_complete = true;
                $profile->profile_completed_at = now();
            }
        });
    }
}
