<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'middle_name',
        'name_suffix',
        'email',
        'password',
        'role',
        'department_id',
        'is_active',
        'password_changed_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password_changed_at' => 'datetime',
            'is_active' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    // ===== RELATIONSHIPS =====

    /**
     * Get the department this user belongs to (students only).
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the admin profile (if user is admin).
     */
    public function adminProfile(): HasOne
    {
        return $this->hasOne(AdminProfile::class);
    }

    /**
     * Get the staff profile (if user is staff).
     */
    public function staffProfile(): HasOne
    {
        return $this->hasOne(StaffProfile::class);
    }

    /**
     * Get the partner profile (if user is partner).
     */
    public function partnerProfile(): HasOne
    {
        return $this->hasOne(PartnerProfile::class);
    }

    /**
     * Get the student profile (if user is student).
     */
    public function studentProfile(): HasOne
    {
        return $this->hasOne(StudentProfile::class);
    }

    /**
     * Get the alumni profile (if user is alumni).
     */
    public function alumniProfile(): HasOne
    {
        return $this->hasOne(AlumniProfile::class);
    }

    /**
     * Get the user's experiences.
     */
    public function experiences(): HasMany
    {
        return $this->hasMany(Experience::class);
    }

    /**
     * Get the user's projects.
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Get job postings created by this partner.
     */
    public function jobPostings(): HasMany
    {
        return $this->hasMany(JobPosting::class, 'partner_id');
    }

    /**
     * Get job applications from this user.
     */
    public function jobApplications(): HasMany
    {
        return $this->hasMany(JobApplication::class, 'applicant_id');
    }

    /**
     * Get events created by this staff.
     */
    public function eventsCreated(): HasMany
    {
        return $this->hasMany(Event::class, 'created_by');
    }

    /**
     * Get event registrations for this user.
     */
    public function eventRegistrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class);
    }

    /**
     * Get news articles created by this staff.
     */
    public function newsArticles(): HasMany
    {
        return $this->hasMany(NewsArticle::class, 'created_by');
    }

    /**
     * Get partnership submissions from this partner.
     */
    public function partnerships(): HasMany
    {
        return $this->hasMany(Partnership::class, 'partner_id');
    }

    /**
     * Get notifications for this user.
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get activity logs for this user.
     */
    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    // ===== SCOPES =====

    /**
     * Scope: Get only active users.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Get only inactive users.
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Scope: Get users by role.
     */
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Scope: Get users who haven't changed password.
     */
    public function scopeNeverChangedPassword($query)
    {
        return $query->whereNull('password_changed_at');
    }

    /**
     * Scope: Get users who must change password (first login).
     */
    public function scopeMustChangePassword($query)
    {
        return $query->whereNull('password_changed_at');
    }

    // ===== HELPER METHODS =====

    /**
     * Get user's full name.
     */
    public function getFullNameAttribute(): string
    {
        $name = "{$this->first_name} {$this->last_name}";
        if ($this->middle_name) {
            $name = "{$this->first_name} {$this->middle_name} {$this->last_name}";
        }
        if ($this->name_suffix) {
            $name .= " {$this->name_suffix}";
        }
        return $name;
    }

    /**
     * Check if user must change password (first login).
     */
    public function mustChangePassword(): bool
    {
        return is_null($this->password_changed_at);
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is staff.
     */
    public function isStaff(): bool
    {
        return $this->role === 'staff';
    }

    /**
     * Check if user is partner.
     */
    public function isPartner(): bool
    {
        return $this->role === 'partner';
    }

    /**
     * Check if user is student.
     */
    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    /**
     * Check if user is alumni.
     */
    public function isAlumni(): bool
    {
        return $this->role === 'alumni';
    }

    /**
     * Get user's profile based on role.
     */
    public function getProfile()
    {
        return match($this->role) {
            'admin' => $this->adminProfile,
            'staff' => $this->staffProfile,
            'partner' => $this->partnerProfile,
            'student' => $this->studentProfile,
            'alumni' => $this->alumniProfile,
            default => null,
        };
    }

    // ===== MODEL EVENTS =====

    /**
     * Enforce business rules on save.
     */
    protected static function booted()
    {
        static::saving(function ($user) {
            // Students MUST have department
            if ($user->role === 'student' && is_null($user->department_id)) {
                throw new \Exception('Students must be assigned to a department.');
            }

            // Non-students MUST NOT have department
            if ($user->role !== 'student' && !is_null($user->department_id)) {
                throw new \Exception('Only students can be assigned to a department.');
            }
        });
    }
}
