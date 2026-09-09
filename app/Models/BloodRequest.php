<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BloodRequest extends Model
{
    protected $fillable = [
        'user_id',
        'patient_name',
        'blood_group',
        'location',
        'units_required',
        'needed_by_date',
        'notes',
        'priority',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'needed_by_date' => 'date',
        ];
    }

    /**
     * The recipient user who created this request.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * All donor responses for this blood request.
     */
    public function responses(): HasMany
    {
        return $this->hasMany(DonationResponse::class);
    }

    /**
     * The accepted donation response for this request (if any).
     */
    public function acceptedResponse(): HasOne
    {
        return $this->hasOne(DonationResponse::class)->where('status', 'accepted');
    }

    /**
     * Check if a specific donor has already responded to this request.
     */
    public function responseForDonor(?int $donorId): ?DonationResponse
    {
        if (!$donorId) {
            return null;
        }

        return $this->responses->firstWhere('donor_id', $donorId);
    }
}
