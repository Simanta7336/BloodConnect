<?php

namespace App\Http\Controllers;

use App\Models\BloodRequest;
use App\Models\User;
use App\Notifications\BloodRequestAlertNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class BloodRequestController extends Controller
{
    /**
     * Show the form to create a new blood request (F07).
     */
    public function create()
    {
        return view('blood_requests.create');
    }

    /**
     * Store a newly created blood request in the database (F07).
     */
    public function store(Request $request)
    {
        $request->validate([
            'patient_name'   => ['required', 'string', 'max:255'],
            'blood_group'    => ['required', 'string', 'in:A+,A-,B+,B-,AB+,AB-,O+,O-'],
            'location'       => ['required', 'string', 'max:255'],
            'units_required' => ['required', 'integer', 'min:1', 'max:20'],
            'needed_by_date' => ['required', 'date', 'after:today'],
            'priority'       => ['required', 'string', 'in:normal,urgent,emergency'],
            'notes'          => ['nullable', 'string', 'max:1000'],
        ]);

        $bloodRequest = BloodRequest::create([
            'user_id'        => Auth::id(),
            'patient_name'   => $request->patient_name,
            'blood_group'    => $request->blood_group,
            'location'       => $request->location,
            'units_required' => $request->units_required,
            'needed_by_date' => $request->needed_by_date,
            'priority'       => $request->input('priority', 'normal'),
            'notes'          => $request->notes,
            'status'         => 'pending',
        ]);

        // F12: Find suitable donors (available donors with matching blood group)
        $suitableDonors = User::where('role', 'donor')
            ->where('is_available', true)
            ->where('blood_group', $bloodRequest->blood_group)
            ->get();

        if ($suitableDonors->isNotEmpty()) {
            Notification::send($suitableDonors, new BloodRequestAlertNotification($bloodRequest));
        }

        return redirect()->route('dashboard')->with('status', 'blood-request-created');
    }
}
