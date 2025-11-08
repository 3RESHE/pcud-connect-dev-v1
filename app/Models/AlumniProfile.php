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
        'profile_photo',
        'headline',
        'personal_email',
        'phone',
        'current_location',
        'linkedin_url',
        'github_url',
        'portfolio_url',
        'resume_path',
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
        'certifications',
        'languages',
        'profile_complete',
        'profile_completed_at',
        'is_public',
    ];

    protected $casts = [
        'graduation_year' => 'integer',
        'gwa' => 'decimal:2',
        'willing_to_relocate' => 'boolean',
        'profile_complete' => 'boolean',
        'is_public' => 'boolean',
        'profile_completed_at' => 'datetime',
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
     * Check if profile is complete (all required fields filled).
     */
    public function isProfileComplete(): bool
    {
        return !empty($this->headline) &&
               !empty($this->personal_email) &&
               !empty($this->phone) &&
               !empty($this->current_location) &&
               !empty($this->degree_program) &&
               !empty($this->graduation_year) &&
               !empty($this->professional_summary);
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
     * Get formatted skills array.
     */
    public function getTechnicalSkillsArray(): array
    {
        if (!$this->technical_skills) {
            return [];
        }
        return array_map('trim', explode(',', $this->technical_skills));
    }

    /**
     * Get formatted soft skills array.
     */
    public function getSoftSkillsArray(): array
    {
        if (!$this->soft_skills) {
            return [];
        }
        return array_map('trim', explode(',', $this->soft_skills));
    }

    /**
     * Get formatted certifications array.
     */
    public function getCertificationsArray(): array
    {
        if (!$this->certifications) {
            return [];
        }
        return array_map('trim', explode(',', $this->certifications));
    }

    /**
     * Get formatted languages array.
     */
    public function getLanguagesArray(): array
    {
        if (!$this->languages) {
            return [];
        }
        return array_map('trim', explode(',', $this->languages));
    }

    /**
     * Get profile completion percentage.
     */
    public function getProfileCompletionPercentage(): int
    {
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
