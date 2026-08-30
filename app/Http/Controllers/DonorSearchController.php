<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DonorSearchController extends Controller
{
    public function search(Request $request)
    {
        $bloodGroup = $request->input('blood_group');
        $location = $request->input('location');
        $donors = collect();

        // Only search if at least one parameter is provided
        if ($bloodGroup || $location) {
            $query = User::where('role', 'donor')
                         ->where('is_available', true);

            if ($bloodGroup) {
                $query->where('blood_group', $bloodGroup);
            }

            if ($location) {
                $query->where('location', 'like', '%' . $location . '%');
            }

            $donors = $query->get();
        }

        return view('donors.search', [
            'donors' => $donors,
            'bloodGroup' => $bloodGroup,
            'location' => $location,
        ]);
    }
}