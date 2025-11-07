<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    protected $fillable = [
        'title',
        'code',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ===== RELATIONSHIPS =====

    /**
     * Get all students in this department
     */
    public function students(): HasMany
    {
        return $this->hasMany(User::class, 'department_id')
            ->where('role', 'student');
    }

    /**
     * Get all users in this department
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'department_id');
    }

    // ===== SCOPES =====

    /**
     * Get departments ordered by title
     */
    public function scopeOrderByTitle($query)
    {
        return $query->orderBy('title');
    }

    /**
     * Search departments by title or code
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('title', 'like', "%{$search}%")
            ->orWhere('code', 'like', "%{$search}%");
    }

    // ===== HELPER METHODS =====

    /**
     * Get student count
     */
    public function getStudentCountAttribute()
    {
        return $this->students()->count();
    }

    /**
     * Get formatted code
     */
    public function getFormattedCodeAttribute()
    {
        return strtoupper($this->code);
    }
}
