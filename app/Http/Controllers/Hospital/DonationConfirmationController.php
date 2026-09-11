<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\BloodRequest;
use App\Models\DonationResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DonationConfirmationController extends Controller
{
    /**
     * F17 — Confirm a blood donation as completed.
     * Accessible by Hospital staff and Admins.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int|string  $id  DonationResponse ID or BloodRequest ID
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirm(Request $request, $id)
    {
        $user = Auth::user();

        // 1. Role validation
        if (!$user || (!$user->isHospital() && !$user->isAdmin())) {
            abort(403, 'Unauthorized. Only hospital staff and administrators can confirm donations.');
        }

        // 2. Locate the DonationResponse (either by direct ID or via BloodRequest ID)
        $donationResponse = DonationResponse::with(['bloodRequest.appointment', 'donor'])->find($id);

        if (!$donationResponse) {
            $bloodRequest = BloodRequest::with(['acceptedResponse.donor', 'appointment'])->find($id);
            if ($bloodRequest && $bloodRequest->acceptedResponse) {
                $donationResponse = $bloodRequest->acceptedResponse;
            }
        }

        if (!$donationResponse) {
            abort(404, 'Donation record not found.');
        }

        $bloodRequest = $donationResponse->bloodRequest;
        $appointment  = $bloodRequest ? $bloodRequest->appointment : null;
        $donor        = $donationResponse->donor;

        // 3. Authorization check for hospital users
        if ($user->isHospital()) {
            $hospital = $user->hospital;
            if (!$hospital) {
                abort(403, 'Hospital profile not found.');
            }

            // If the blood request is already assigned to a different hospital, block confirmation
            if ($bloodRequest && $bloodRequest->hospital_id && (int) $bloodRequest->hospital_id !== (int) $hospital->id) {
                abort(403, 'You are not authorized to confirm a donation managed by another hospital.');
            }
        }

        // 4. State validation & Prevention checks
        // a) Prevent double confirmation
        if ($donationResponse->status === 'completed' || $donationResponse->completed_at !== null) {
            return back()->with('error', 'This donation has already been confirmed as completed.');
        }

        // b) Prevent rejected donations
        if ($donationResponse->status === 'rejected') {
            return back()->with('error', 'Cannot confirm a rejected donation request.');
        }

        // c) Prevent completed/cancelled blood request issues
        if ($bloodRequest && $bloodRequest->status === 'cancelled') {
            return back()->with('error', 'Cannot confirm a donation for a cancelled blood request.');
        }

        // d) Prevent cancelled appointment issues
        if ($appointment && $appointment->status === 'cancelled') {
            return back()->with('error', 'Cannot confirm a donation for a cancelled appointment.');
        }

        // e) Verify the donation response is in accepted state
        if ($donationResponse->status !== 'accepted') {
            return back()->with('error', 'Only accepted donations can be confirmed as completed.');
        }

        // Validate optional input
        $validated = $request->validate([
            'confirmation_notes' => ['nullable', 'string', 'max:1000'],
            'notes'              => ['nullable', 'string', 'max:1000'],
        ]);

        $notes = $validated['confirmation_notes'] ?? $validated['notes'] ?? null;

        // 5. Atomic database transaction
        DB::transaction(function () use ($donationResponse, $bloodRequest, $appointment, $donor, $user, $notes) {
            // Update DonationResponse
            $donationResponse->update([
                'status'             => 'completed',
                'completed_at'       => now(),
                'confirmed_by'       => $user->id,
                'confirmation_notes' => $notes,
            ]);

            // Update linked BloodRequest to fulfilled (preserving existing architecture status)
            if ($bloodRequest && $bloodRequest->status !== 'fulfilled') {
                $bloodRequest->update([
                    'status' => 'fulfilled',
                ]);
            }

            // Update linked Appointment to completed (if exists)
            if ($appointment && $appointment->status !== 'completed') {
                $appointment->update([
                    'status' => 'completed',
                ]);
            }

            // Update donor last_donation_date to today
            if ($donor) {
                $donor->update([
                    'last_donation_date' => now()->toDateString(),
                ]);
            }
        });

        return back()->with('status', 'donation-confirmed');
    }
}
