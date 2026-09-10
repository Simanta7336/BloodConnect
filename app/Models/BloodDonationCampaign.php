<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BloodDonationCampaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'hospital_id',
        'title',
        'description',
        'campaign_date',
        'start_time',
        'end_time',
        'location',
        'target_units',
        'collected_units',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'campaign_date' => 'date',
            'start_time' => 'datetime:H:i',
            'end_time' => 'datetime:H:i',
        ];
    }

    /**
     * The hospital that organized this campaign.
     */
    public function hospital(): BelongsTo
    {
        return $this->belongsTo(Hospital::class);
    }
}
