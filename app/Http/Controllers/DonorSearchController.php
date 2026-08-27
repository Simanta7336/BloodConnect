<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DonorSearchController extends Controller
{
    public function search(Request $request)
    {
        $bloodGroup = $request->input('blood_group');
        $donors = collect();

        $donors = User::where('role', 'donor')
            ->where('blood_group', $bloodGroup)
            ->where('is_available', true)
            ->get();

        return view('donors.search', [
            'donors' => $donors,
            'bloodGroup' => $bloodGroup,
        ]);
    }
}