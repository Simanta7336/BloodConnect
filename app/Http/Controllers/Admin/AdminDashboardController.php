<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodRequest;
use App\Models\Hospital;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    /**
     * F19 - Admin Dashboard overview.
     * Shows platform-wide stats, pending hospital verifications, and recent blood requests.
     */
    public function index()
    {
        // User counts by role
        $userStats = [
            'total'      => User::count(),
            'donors'     => User::where('role', 'donor')->count(),
            'recipients' => User::where('role', 'recipient')->count(),
            'hospitals'  => User::where('role', 'hospital')->count(),
            'admins'     => User::where('role', 'admin')->count(),
        ];

        // Blood request counts by status
        $requestStats = [
            'total'     => BloodRequest::count(),
            'pending'   => BloodRequest::where('status', 'pending')->count(),
            'accepted'  => BloodRequest::where('status', 'accepted')->count(),
            'fulfilled' => BloodRequest::where('status', 'fulfilled')->count(),
            'cancelled' => BloodRequest::where('status', 'cancelled')->count(),
        ];

        // Hospitals pending verification
        $pendingHospitals = Hospital::with('user')
            ->where('is_verified', false)
            ->latest()
            ->get();

        // All hospitals for management table
        $allHospitals = Hospital::with('user')
            ->latest()
            ->get();

        // Recent 10 blood requests
        $recentRequests = BloodRequest::with(['user', 'hospital'])
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact(
            'userStats',
            'requestStats',
            'pendingHospitals',
            'allHospitals',
            'recentRequests'
        ));
    }

    /**
     * F19 - Verify (approve) a hospital account.
     */
    public function verifyHospital(Request $request, $id)
    {
        $hospital = Hospital::findOrFail($id);
        $hospital->is_verified = true;
        $hospital->save();

        return back()->with('status', 'hospital-verified');
    }

    /**
     * F19 - Reject / Remove a hospital account.
     */
    public function rejectHospital(Request $request, $id)
    {
        $hospital = Hospital::findOrFail($id);
        $user = $hospital->user;

        $hospital->delete();
        if ($user) {
            $user->delete();
        }

        return back()->with('status', 'hospital-rejected');
    }

    /**
     * F19 - Revoke verification for an approved hospital.
     */
    public function revokeHospital(Request $request, $id)
    {
        $hospital = Hospital::findOrFail($id);
        $hospital->is_verified = false;
        $hospital->save();

        return back()->with('status', 'hospital-revoked');
    }
}
