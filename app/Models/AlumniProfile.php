<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlumniProfile extends Model
{
    protected $fillable = [
        'user_id',
        'profile_photo',
        'headline',
        'email',
        'phone',
        'current_location',
        'linkedin_url',
        'github_url',
        'portfolio_url',
        'resume_path',
        'professional_summary',
        'degree_program',
        'graduation_year',
        'gwa',
        'honors',
        'thesis_title',
        'organizations',
        'technical_skills',
        'soft_skills',
        'certifications',
        'languages',
    ];

    protected $casts = [
        'graduation_year' => 'integer',
        'gwa' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
