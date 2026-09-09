<?php

namespace App\Http\Controllers;

use App\Models\BloodRequest;
use App\Models\DonationResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DonationResponseController extends Controller
{
    /**
     * Display the specified blood request details.
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();
        $bloodRequest = BloodRequest::with(['user', 'responses.donor', 'acceptedResponse.donor'])->findOrFail($id);

        // Security / Authorization check
        if ($user->role === 'donor') {
            if (!$this->isDonorAuthorized($user, $bloodRequest)) {
                abort(403, 'You are not authorized to view this donation request.');
            }

            // Auto-mark the donor's related notification as read when viewing
            $this->markDonorNotificationAsRead($user, $bloodRequest);
        } elseif ($user->role === 'recipient') {
            if ($bloodRequest->user_id !== $user->id) {
                abort(403, 'You are not authorized to view this donation request.');
            }
        }

        $donorResponse = $bloodRequest->responseForDonor($user->id);

        return view('blood_requests.show', compact('bloodRequest', 'donorResponse'));
    }

    /**
     * Accept a blood donation request.
     */
    public function accept(Request $request, $id)
    {
        $user = $request->user();

        // 1. Role verification
        if ($user->role !== 'donor') {
            abort(403, 'Only registered donors can accept donation requests.');
        }

        $bloodRequest = BloodRequest::findOrFail($id);

        // 2. Authorization check (must have received notification for this request)
        if (!$this->isDonorAuthorized($user, $bloodRequest)) {
            abort(403, 'You are not authorized to respond to this blood donation request.');
        }

        // 3. Prevent duplicate response
        if (DonationResponse::where('blood_request_id', $bloodRequest->id)->where('donor_id', $user->id)->exists()) {
            return redirect()->route('blood-requests.show', $bloodRequest->id)
                ->with('error', 'You have already submitted a response to this donation request.');
        }

        // 4. Concurrency-safe atomic acceptance
        try {
            DB::transaction(function () use ($bloodRequest, $user) {
                $lockedRequest = BloodRequest::where('id', $bloodRequest->id)->lockForUpdate()->firstOrFail();

                if ($lockedRequest->status !== 'pending') {
                    throw new \Exception('This blood request is no longer available for acceptance (already accepted or closed).');
                }

                DonationResponse::create([
                    'blood_request_id' => $lockedRequest->id,
                    'donor_id'         => $user->id,
                    'status'           => 'accepted',
                ]);

                $lockedRequest->update(['status' => 'accepted']);
            });
        } catch (\Exception $e) {
            return redirect()->route('blood-requests.show', $bloodRequest->id)
                ->with('error', $e->getMessage());
        }

        // 5. Mark related notification as read
        $this->markDonorNotificationAsRead($user, $bloodRequest);

        return redirect()->route('blood-requests.show', $bloodRequest->id)
            ->with('status', 'donation-accepted');
    }

    /**
     * Reject a blood donation request.
     */
    public function reject(Request $request, $id)
    {
        $user = $request->user();

        // 1. Role verification
        if ($user->role !== 'donor') {
            abort(403, 'Only registered donors can reject donation requests.');
        }

        $bloodRequest = BloodRequest::findOrFail($id);

        // 2. Authorization check
        if (!$this->isDonorAuthorized($user, $bloodRequest)) {
            abort(403, 'You are not authorized to respond to this blood donation request.');
        }

        // 3. Prevent duplicate response
        if (DonationResponse::where('blood_request_id', $bloodRequest->id)->where('donor_id', $user->id)->exists()) {
            return redirect()->route('blood-requests.show', $bloodRequest->id)
                ->with('error', 'You have already submitted a response to this donation request.');
        }

        // 4. Check if request is active
        if (in_array($bloodRequest->status, ['cancelled', 'fulfilled'])) {
            return redirect()->route('blood-requests.show', $bloodRequest->id)
                ->with('error', 'This blood request is no longer active.');
        }

        // 5. Record rejection (does not close the overall request)
        DonationResponse::create([
            'blood_request_id' => $bloodRequest->id,
            'donor_id'         => $user->id,
            'status'           => 'rejected',
        ]);

        // 6. Mark related notification as read
        $this->markDonorNotificationAsRead($user, $bloodRequest);

        return redirect()->route('blood-requests.show', $bloodRequest->id)
            ->with('status', 'donation-rejected');
    }

    /**
     * Verify if the given donor was notified about the blood request.
     */
    protected function isDonorAuthorized(User $donor, BloodRequest $bloodRequest): bool
    {
        if ($donor->role !== 'donor') {
            return false;
        }

        return $donor->notifications()
            ->where(function ($q) use ($bloodRequest) {
                $q->where('data', 'like', '%"blood_request_id":' . $bloodRequest->id . '%')
                  ->orWhere('data', 'like', '%"blood_request_id":"' . $bloodRequest->id . '"%');
            })
            ->exists();
    }

    /**
     * Helper to mark the donor's related notification as read.
     */
    protected function markDonorNotificationAsRead(User $donor, BloodRequest $bloodRequest): void
    {
        $donor->unreadNotifications()
            ->where(function ($q) use ($bloodRequest) {
                $q->where('data', 'like', '%"blood_request_id":' . $bloodRequest->id . '%')
                  ->orWhere('data', 'like', '%"blood_request_id":"' . $bloodRequest->id . '"%');
            })
            ->update(['read_at' => now()]);
    }
}
