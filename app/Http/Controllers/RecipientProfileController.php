<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RecipientProfileController extends Controller
{
    /**
     * Display the recipient's profile edit form.
     * Mirrors DonorProfileController::edit() exactly.
     */
    public function edit()
    {
        return view('recipient.profile', [
            'user' => auth()->user(),
        ]);
    }

    /**
     * Update the recipient's profile information.
     * Mirrors DonorProfileController::update() in structure.
     * Reuses shared fields (blood_group, phone, location) and
     * validates the new recipient-specific fields added in PB04.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'blood_group'        => 'nullable|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'phone'              => 'nullable|string|max:20',
            'location'           => 'nullable|string|max:255',
            'date_of_birth'      => 'nullable|date',
            'gender'             => 'nullable|in:male,female,other',
            'division'           => 'nullable|string|max:100',
            'district'           => 'nullable|string|max:100',
            'hospital_name'      => 'nullable|string|max:255',
            'blood_units_needed' => 'nullable|integer|min:1|max:50',
            'required_by_date'   => 'nullable|date',
            'medical_condition'  => 'nullable|string|max:1000',
        ]);

        $request->user()->update($validated);

        return redirect()->route('recipient.profile.edit')->with('status', 'profile-updated');
    }
}
