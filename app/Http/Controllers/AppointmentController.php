<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\BloodRequest;
use App\Notifications\AppointmentScheduledNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    /**
     * Show the appointment scheduling form.
     * Accessible only to the recipient who created the accepted blood request.
     */
    public function create($bloodRequestId)
    {
        $bloodRequest = BloodRequest::with([
            'acceptedResponse.donor',
            'user',
            'appointment'
        ])->findOrFail($bloodRequestId);

        $user = Auth::user();

        // Only the recipient who created the request can schedule
        if ($user->role !== 'recipient' || (int) $bloodRequest->user_id !== (int) $user->id) {
            abort(403, 'Only the recipient who submitted this blood request can schedule an appointment.');
        }

        // Blood request must be accepted
        if ($bloodRequest->status !== 'accepted') {
            return redirect()
                ->route('blood-requests.show', $bloodRequest->id)
                ->with('error', 'An appointment can only be scheduled once a donor has accepted the blood request.');
        }

        // Must have an accepted donor
        if (!$bloodRequest->acceptedResponse || !$bloodRequest->acceptedResponse->donor) {
            return redirect()
                ->route('blood-requests.show', $bloodRequest->id)
                ->with('error', 'No accepted donor found for this blood request.');
        }

        // If an appointment already exists, redirect to it
        if ($bloodRequest->appointment) {
            return redirect()
                ->route('appointments.show', $bloodRequest->appointment->id);
        }

        return view('appointments.create', compact('bloodRequest'));
    }

    /**
     * Store a newly created appointment.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'blood_request_id' => ['required', 'exists:blood_requests,id'],
            'appointment_date' => ['required', 'date', 'after_or_equal:today'],
            'appointment_time' => ['required'],
            'location'         => ['required', 'string', 'max:255'],
            'notes'            => ['nullable', 'string', 'max:1000'],
        ]);

        $bloodRequest = BloodRequest::with(['acceptedResponse.donor'])
            ->findOrFail($validated['blood_request_id']);

        $user = Auth::user();

        // Ownership and role check
        if ($user->role !== 'recipient' || (int) $bloodRequest->user_id !== (int) $user->id) {
            abort(403, 'Only the recipient who submitted this blood request can schedule an appointment.');
        }

        // Status must be accepted
        if ($bloodRequest->status !== 'accepted') {
            return redirect()
                ->route('blood-requests.show', $bloodRequest->id)
                ->with('error', 'An appointment can only be scheduled for an accepted blood request.');
        }

        // Must have an accepted donor
        if (!$bloodRequest->acceptedResponse || !$bloodRequest->acceptedResponse->donor_id) {
            return redirect()
                ->route('blood-requests.show', $bloodRequest->id)
                ->with('error', 'No accepted donor found for this blood request.');
        }

        // Prevent duplicate appointments for the same request
        if ($bloodRequest->appointment) {
            return redirect()
                ->route('appointments.show', $bloodRequest->appointment->id);
        }

        // Create the appointment
        $appointment = Appointment::create([
            'blood_request_id' => $bloodRequest->id,
            'recipient_id'     => $bloodRequest->user_id,
            'donor_id'         => $bloodRequest->acceptedResponse->donor_id,
            'appointment_date' => $validated['appointment_date'],
            'appointment_time' => $validated['appointment_time'],
            'location'         => $validated['location'],
            'notes'            => $validated['notes'] ?? null,
            'status'           => 'confirmed',
        ]);

        // Notify the donor
        $donor = $bloodRequest->acceptedResponse->donor;
        if ($donor) {
            $donor->notify(new AppointmentScheduledNotification($appointment));
        }

        return redirect()
            ->route('appointments.show', $appointment->id)
            ->with('status', 'appointment-created');
    }

    /**
     * Display the specified appointment details.
     * Accessible to both the recipient and the donor involved.
     */
    public function show(Appointment $appointment)
    {
        $appointment->load([
            'bloodRequest.user',
            'recipient',
            'donor',
        ]);

        $userId = Auth::id();

        // Only the involved recipient or donor can view
        if ((int) $appointment->recipient_id !== (int) $userId && (int) $appointment->donor_id !== (int) $userId) {
            abort(403, 'You are not authorized to view this appointment.');
        }

        return view('appointments.show', compact('appointment'));
    }

    /**
     * Mark the appointment and blood donation as completed.
     */
    public function complete(Appointment $appointment)
    {
        $userId = Auth::id();

        // Only involved parties can complete
        if ((int) $appointment->recipient_id !== (int) $userId && (int) $appointment->donor_id !== (int) $userId) {
            abort(403, 'You are not authorized to update this appointment.');
        }

        $appointment->update(['status' => 'completed']);

        // Mark blood request as fulfilled
        if ($appointment->bloodRequest) {
            $appointment->bloodRequest->update(['status' => 'fulfilled']);
        }

        // Update donor's last donation date to today
        if ($appointment->donor) {
            $appointment->donor->update([
                'last_donation_date' => now()->toDateString(),
            ]);
        }

        return redirect()
            ->route('appointments.show', $appointment->id)
            ->with('status', 'appointment-completed');
    }

    /**
     * Cancel the appointment.
     */
    public function cancel(Appointment $appointment)
    {
        $userId = Auth::id();

        // Only involved parties can cancel
        if ((int) $appointment->recipient_id !== (int) $userId && (int) $appointment->donor_id !== (int) $userId) {
            abort(403, 'You are not authorized to cancel this appointment.');
        }

        $appointment->update(['status' => 'cancelled']);

        return redirect()
            ->route('appointments.show', $appointment->id)
            ->with('status', 'appointment-cancelled');
    }
}

