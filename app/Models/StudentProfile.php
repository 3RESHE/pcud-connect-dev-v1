<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentProfile extends Model
{
    protected $fillable = [
        'user_id',
        'student_id',
        'profile_photo',
        'headline',
        'personal_email',
        'phone',
        'emergency_contact',
        'address',
        'date_of_birth',
        'gender',
        'linkedin_url',
        'github_url',
        'portfolio_url',
        'resumes',
        'certifications',
        'technical_skills',
        'soft_skills',
        'languages',
        'hobbies',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'resumes' => 'array',
        'certifications' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get technical skills as array
     */
    public function getTechnicalSkillsArray()
    {
        if (!$this->technical_skills) {
            return [];
        }
        return array_filter(array_map('trim', explode(',', $this->technical_skills)));
    }

    /**
     * Get soft skills as array
     */
    public function getSoftSkillsArray()
    {
        if (!$this->soft_skills) {
            return [];
        }
        return array_filter(array_map('trim', explode(',', $this->soft_skills)));
    }

    /**
     * Get languages as array
     */
    public function getLanguagesArray()
    {
        if (!$this->languages) {
            return [];
        }
        return array_filter(array_map('trim', explode(',', $this->languages)));
    }

    /**
     * Add resume file to array
     */
    public function addResume($filePath)
    {
        $resumes = $this->resumes ?? [];
        if (!in_array($filePath, $resumes)) {
            $resumes[] = $filePath;
            $this->update(['resumes' => $resumes]);
        }
    }

    /**
     * Add certification file to array
     */
    public function addCertification($filePath)
    {
        $certifications = $this->certifications ?? [];
        if (!in_array($filePath, $certifications)) {
            $certifications[] = $filePath;
            $this->update(['certifications' => $certifications]);
        }
    }

    /**
     * Remove resume file from array
     */
    public function removeResume($filePath)
    {
        $resumes = $this->resumes ?? [];
        $resumes = array_filter($resumes, fn($file) => $file !== $filePath);
        $this->update(['resumes' => array_values($resumes)]);
    }

    /**
     * Remove certification file from array
     */
    public function removeCertification($filePath)
    {
        $certifications = $this->certifications ?? [];
        $certifications = array_filter($certifications, fn($file) => $file !== $filePath);
        $this->update(['certifications' => array_values($certifications)]);
    }
}
