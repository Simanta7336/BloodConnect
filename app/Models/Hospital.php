<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hospital extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'hospital_name',
        'license_number',
        'address',
        'division',
        'district',
        'contact_phone',
        'contact_email',
        'is_verified',
    ];

    protected function casts(): array
    {
        return [
            'is_verified' => 'boolean',
        ];
    }

    /**
     * The user account that owns this hospital profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Blood requests managed by this hospital (F16).
     */
    public function bloodRequests(): HasMany
    {
        return $this->hasMany(BloodRequest::class);
    }

    /**
     * Blood donation campaigns organized by this hospital (F18).
     */
    public function campaigns(): HasMany
    {
        return $this->hasMany(BloodDonationCampaign::class);
    }
}
