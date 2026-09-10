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
            'last_donation_date' => 'date',
        ];
    }

    /**
     * F15 — Calculate next eligible donation date.
     * strictly based on last_donation_date + 90 days
     */
    public function nextEligibleDonationDate()
    {
        if (!$this->last_donation_date) {
            return now()->subDay(); // Eligible immediately
        }
        return $this->last_donation_date->copy()->addDays(90);
    }

    /**
     * F15 — Check if they are eligible based on time elapsed
     */
    public function isEligibleToDonate(): bool
    {
        return now()->greaterThanOrEqualTo($this->nextEligibleDonationDate());
    }

    /**
     * Check if the donor already has an accepted/pending commitment that prevents them from taking new ones
     */
    public function hasActiveDonationCommitment(): bool
    {
        if (!method_exists($this, 'donationResponses')) return false;

        return $this->donationResponses()
            ->whereIn('status', ['accepted'])
            ->exists();
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

    /**
     * Sprint 4 — Hospital profile linked to this user (if role='hospital').
     */
    public function hospital()
    {
        return $this->hasOne(Hospital::class);
    }

    /**
     * Sprint 4 — Role helper methods.
     */
    public function isDonor(): bool
    {
        return $this->role === 'donor';
    }

    public function isRecipient(): bool
    {
        return $this->role === 'recipient';
    }

    public function isHospital(): bool
    {
        return $this->role === 'hospital';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
