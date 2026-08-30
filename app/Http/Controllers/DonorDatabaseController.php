<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DonorDatabaseController extends Controller
{
    public function index(Request $request)
    {
        $sortBy = $request->query('sort_by', 'is_available');
        $sortDir = $request->query('sort_dir', 'desc');
        $availability = $request->query('availability', 'all');

        // Validate sort parameters
        $allowedSortColumns = ['name', 'blood_group', 'location', 'phone', 'last_donation_date', 'is_available'];
        if (!in_array($sortBy, $allowedSortColumns)) {
            $sortBy = 'is_available';
        }
        $sortDir = in_array($sortDir, ['asc', 'desc']) ? $sortDir : 'desc';

        // Build query — fetch donors only
        $query = User::where('role', 'donor');

        // Apply availability filter
        if ($availability === 'available') {
            $query->where('is_available', true);
        } elseif ($availability === 'unavailable') {
            $query->where('is_available', false);
        }

        // Apply sorting and paginate
        $donors = $query->orderBy($sortBy, $sortDir)->paginate(15)->withQueryString();

        return view('donors.index', [
            'donors' => $donors,
            'sortBy' => $sortBy,
            'sortDir' => $sortDir,
            'availability' => $availability,
        ]);
    }
}
