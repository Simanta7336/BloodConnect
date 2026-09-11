<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    protected $fillable = [
        'title',
        'description',
        'campaign_date',
        'start_time',
        'end_time',
        'location',
        'organizer',
        'contact',
        'status',
    ];

    protected $casts = [
        'campaign_date' => 'date',
    ];
}