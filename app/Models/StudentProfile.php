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
        'resume_path',
        'technical_skills',
        'soft_skills',
        'certifications',
        'languages',
        'hobbies',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
