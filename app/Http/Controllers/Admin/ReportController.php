<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BloodRequest;
use App\Models\DonationResponse;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * F20 - Reports & Blood-Group Statistics.
     * Aggregates data for charts and tables on the admin reports page.
     */
    public function index()
    {
        $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];

        // Donors per blood group
        $donorsByBloodGroup = User::where('role', 'donor')
            ->whereIn('blood_group', $bloodGroups)
            ->select('blood_group', DB::raw('count(*) as total'))
            ->groupBy('blood_group')
            ->pluck('total', 'blood_group')
            ->toArray();

        // Blood requests per blood group
        $requestsByBloodGroup = BloodRequest::whereIn('blood_group', $bloodGroups)
            ->select('blood_group', DB::raw('count(*) as total'))
            ->groupBy('blood_group')
            ->pluck('total', 'blood_group')
            ->toArray();

        // Fulfilled requests per blood group
        $fulfilledByBloodGroup = BloodRequest::where('status', 'fulfilled')
            ->whereIn('blood_group', $bloodGroups)
            ->select('blood_group', DB::raw('count(*) as total'))
            ->groupBy('blood_group')
            ->pluck('total', 'blood_group')
            ->toArray();

        // Ensure all blood groups appear (fill missing with 0)
        $donorCounts     = [];
        $requestCounts   = [];
        $fulfilledCounts = [];
        foreach ($bloodGroups as $bg) {
            $donorCounts[]     = $donorsByBloodGroup[$bg]     ?? 0;
            $requestCounts[]   = $requestsByBloodGroup[$bg]   ?? 0;
            $fulfilledCounts[] = $fulfilledByBloodGroup[$bg]  ?? 0;
        }

        // Overall fulfillment rate
        $totalRequests   = BloodRequest::count();
        $totalFulfilled  = BloodRequest::where('status', 'fulfilled')->count();
        $fulfillmentRate = $totalRequests > 0
            ? round(($totalFulfilled / $totalRequests) * 100, 1)
            : 0;

        // User role breakdown for pie chart
        $userRoleBreakdown = [
            'donors'     => User::where('role', 'donor')->count(),
            'recipients' => User::where('role', 'recipient')->count(),
            'hospitals'  => User::where('role', 'hospital')->count(),
            'admins'     => User::where('role', 'admin')->count(),
        ];

        // Request status breakdown
        $requestStatusBreakdown = [
            'pending'   => BloodRequest::where('status', 'pending')->count(),
            'accepted'  => BloodRequest::where('status', 'accepted')->count(),
            'fulfilled' => BloodRequest::where('status', 'fulfilled')->count(),
            'cancelled' => BloodRequest::where('status', 'cancelled')->count(),
        ];

        // Monthly blood requests for the current year (trend chart - supports SQLite and MySQL)
        if (DB::getDriverName() === 'sqlite') {
            $monthlyRequests = BloodRequest::selectRaw('strftime("%m", created_at) as month, count(*) as total')
                ->whereRaw('strftime("%Y", created_at) = ?', [now()->format('Y')])
                ->groupByRaw('strftime("%m", created_at)')
                ->orderByRaw('strftime("%m", created_at)')
                ->pluck('total', 'month')
                ->toArray();
        } else {
            $monthlyRequests = BloodRequest::selectRaw('DATE_FORMAT(created_at, "%m") as month, count(*) as total')
                ->whereYear('created_at', now()->format('Y'))
                ->groupByRaw('DATE_FORMAT(created_at, "%m")')
                ->orderByRaw('DATE_FORMAT(created_at, "%m")')
                ->pluck('total', 'month')
                ->toArray();
        }

        $monthNames      = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        $monthlyCounts   = [];
        for ($m = 1; $m <= 12; $m++) {
            $key = str_pad($m, 2, '0', STR_PAD_LEFT);
            $monthlyCounts[] = $monthlyRequests[$key] ?? 0;
        }

        // Available donors count
        $availableDonors = User::where('role', 'donor')->where('is_available', true)->count();

        return view('admin.reports', compact(
            'bloodGroups',
            'donorCounts',
            'requestCounts',
            'fulfilledCounts',
            'fulfillmentRate',
            'totalRequests',
            'totalFulfilled',
            'userRoleBreakdown',
            'requestStatusBreakdown',
            'monthNames',
            'monthlyCounts',
            'availableDonors'
        ));
    }
}
