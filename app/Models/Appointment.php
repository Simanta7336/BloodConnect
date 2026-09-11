<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'blood_request_id',
        'recipient_id',
        'donor_id',
        'appointment_date',
        'appointment_time',
        'location',
        'status',
    ];

    protected $casts = [
        'appointment_date' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Blood Request
    |--------------------------------------------------------------------------
    */

    public function bloodRequest()
    {
        return $this->belongsTo(BloodRequest::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Recipient
    |--------------------------------------------------------------------------
    */

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Donor
    |--------------------------------------------------------------------------
    */

    public function donor()
    {
        return $this->belongsTo(User::class, 'donor_id');
    }
}

