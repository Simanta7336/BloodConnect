<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'blood_group',
        'phone',
        'location',
        'is_available',
        'last_donation_date',
        // Recipient-specific fields (PB04)
        'date_of_birth',
        'gender',
        'division',
        'district',
        'hospital_name',
        'blood_units_needed',
        'required_by_date',
        'medical_condition',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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
            'password' => 'hashed',
        ];
    }

    /**
     * Blood requests created by this user (if recipient).
     */
    public function bloodRequests()
    {
        return $this->hasMany(BloodRequest::class);
    }

    /**
     * Donation responses submitted by this user (if donor).
     */
    public function donationResponses()
    {
        return $this->hasMany(DonationResponse::class, 'donor_id');
    }
}
