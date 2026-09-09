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

    /**
     * F11 — Blood group compatibility map.
     * Key = recipient blood group, Value = list of donor blood groups that can donate to them.
     */
    public const COMPATIBILITY = [
        'A+'  => ['A+', 'A-', 'O+', 'O-'],
        'A-'  => ['A-', 'O-'],
        'B+'  => ['B+', 'B-', 'O+', 'O-'],
        'B-'  => ['B-', 'O-'],
        'AB+' => ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'],
        'AB-' => ['A-', 'B-', 'AB-', 'O-'],
        'O+'  => ['O+', 'O-'],
        'O-'  => ['O-'],
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

    /**
     * F11 — Return all available donors compatible with this request's blood group,
     * ranked by: (1) same location as request, (2) exact blood group match, (3) compatible.
     *
     * @return \Illuminate\Support\Collection
     */
    public function matchedDonors(): \Illuminate\Support\Collection
    {
        $compatibleGroups = self::COMPATIBILITY[$this->blood_group] ?? [$this->blood_group];
        $requestLocation  = strtolower(trim($this->location ?? ''));

        return User::where('role', 'donor')
            ->where('is_available', true)
            ->whereIn('blood_group', $compatibleGroups)
            ->get()
            ->map(function (User $donor) use ($requestLocation) {
                // Score: location match (2 pts) + exact blood group (1 pt)
                $score = 0;
                if ($requestLocation && str_contains(strtolower($donor->location ?? ''), $requestLocation)) {
                    $score += 2;
                }
                if ($donor->blood_group === $this->blood_group) {
                    $score += 1;
                }
                $donor->match_score     = $score;
                $donor->is_exact_match  = ($donor->blood_group === $this->blood_group);
                return $donor;
            })
            ->sortByDesc('match_score')
            ->values();
    }
}
