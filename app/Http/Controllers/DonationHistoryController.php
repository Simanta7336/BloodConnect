<?php

namespace App\Http\Controllers;

use App\Models\DonationResponse;
use App\Models\BloodRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DonationHistoryController extends Controller
{
    /**
     * Display a listing of the donation history (F10).
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->role === 'donor') {
            $history = DonationResponse::with(['bloodRequest.user'])
                ->where('donor_id', $user->id)
                ->whereIn('status', ['accepted', 'completed', 'fulfilled'])
                ->latest()
                ->get();
        } else {
            $history = BloodRequest::with(['acceptedResponse.donor'])
                ->where('user_id', $user->id)
                ->whereIn('status', ['accepted', 'fulfilled', 'completed'])
                ->latest()
                ->get();
        }

        return view('donation_history.index', compact('history', 'user'));
    }
}
