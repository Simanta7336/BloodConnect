<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DonorProfileController extends Controller
{
    public function edit()
    {
        return view('donor.profile', [
            'user' => auth()->user(),
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'blood_group' => 'nullable|string|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'phone' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
            'is_available' => 'nullable|boolean',
            'last_donation_date' => 'nullable|date',
        ]);

        $validated['is_available'] = $request->boolean('is_available');

        $request->user()->update($validated);

        return redirect()->route('donor.profile.edit')->with('status', 'profile-updated');
    }
}
