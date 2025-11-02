<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartnerProfile extends Model
{
    protected $fillable = [
        'user_id',
        'company_name',
        'company_logo',
        'industry',
        'company_size',
        'founded_year',
        'description',
        'contact_person',
        'contact_title',
        'phone',
        'address',
        'website',
        'linkedin_url',
    ];

    protected $casts = [
        'founded_year' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
