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
     * Assuming 90 days interval for blood donation.
     * Takes into account the profile's last_donation_date AND any accepted blood requests.
     */
    public function nextEligibleDonationDate()
    {
        $dates = collect();

        if ($this->last_donation_date) {
            $dates->push($this->last_donation_date);
        }

        // Also check their accepted donation responses to prevent multiple bookings
        if (method_exists($this, 'donationResponses')) {
            $latestResponse = $this->donationResponses()
                ->whereIn('status', ['accepted', 'completed'])
                ->with('bloodRequest')
                ->get()
                ->pluck('bloodRequest.needed_by_date')
                ->filter()
                ->max();

            if ($latestResponse) {
                $dates->push(\Carbon\Carbon::parse($latestResponse));
            }
        }

        if ($dates->isEmpty()) {
            return now()->subDays(1); // Eligible immediately
        }

        return $dates->max()->copy()->addDays(90);
    }

    /**
     * F15 — Check if the donor is currently eligible to donate based on last donation date.
     */
    public function isEligibleToDonate(): bool
    {
        return now()->greaterThanOrEqualTo($this->nextEligibleDonationDate());
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
