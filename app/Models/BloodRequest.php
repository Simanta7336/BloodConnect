<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
