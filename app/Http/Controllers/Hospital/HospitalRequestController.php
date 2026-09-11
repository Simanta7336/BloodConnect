<?php

namespace App\Http\Controllers\Hospital;

use App\Http\Controllers\Controller;
use App\Models\BloodRequest;
use App\Models\Hospital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HospitalRequestController extends Controller
{
    /**
     * F16 — List all blood requests visible to this hospital.
     * Hospitals can filter by status, blood group, and priority.
     */
    public function index(Request $request)
    {
        $hospital = Auth::user()->hospital;

        $query = BloodRequest::with(['user', 'hospital', 'acceptedResponse.donor'])
            ->latest();

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Filter by blood group
        if ($request->filled('blood_group') && $request->blood_group !== 'all') {
            $query->where('blood_group', $request->blood_group);
        }

        // Filter by priority
        if ($request->filled('priority') && $request->priority !== 'all') {
            $query->where('priority', $request->priority);
        }

        // Filter to show only requests assigned to this hospital
        if ($request->filled('mine') && $request->mine === '1') {
            $query->where('hospital_id', $hospital->id);
        }

        $bloodRequests = $query->paginate(12)->withQueryString();

        // Stats for the hospital dashboard
        $stats = [
            'total'    => BloodRequest::count(),
            'pending'  => BloodRequest::where('status', 'pending')->count(),
            'accepted' => BloodRequest::where('status', 'accepted')->count(),
            'mine'     => BloodRequest::where('hospital_id', $hospital->id)->count(),
        ];

        return view('hospital.requests.index', compact('bloodRequests', 'hospital', 'stats'));
    }

    /**
     * F16 — Show a single blood request in detail (hospital view).
     */
    public function show($id)
    {
        $hospital    = Auth::user()->hospital;
        $bloodRequest = BloodRequest::with([
            'user',
            'hospital',
            'responses.donor',
            'acceptedResponse.donor',
            'acceptedResponse.confirmedByUser',
            'appointment.donor',
            'appointment.recipient',
        ])->findOrFail($id);

        return view('hospital.requests.show', compact('bloodRequest', 'hospital'));
    }

    /**
     * F16 — Assign this hospital to manage a blood request.
     */
    public function assign(Request $request, $id)
    {
        $hospital    = Auth::user()->hospital;
        $bloodRequest = BloodRequest::findOrFail($id);

        // Only assign if not already managed by another hospital
        if ($bloodRequest->hospital_id && $bloodRequest->hospital_id !== $hospital->id) {
            return back()->with('error', 'This request is already being managed by another hospital.');
        }

        $bloodRequest->hospital_id = $hospital->id;
        $bloodRequest->save();

        return back()->with('status', 'request-assigned');
    }

    /**
     * F16 — Unassign this hospital from a blood request.
     */
    public function unassign(Request $request, $id)
    {
        $hospital    = Auth::user()->hospital;
        $bloodRequest = BloodRequest::findOrFail($id);

        // Only the managing hospital can unassign
        if ($bloodRequest->hospital_id !== $hospital->id) {
            return back()->with('error', 'You are not managing this request.');
        }

        $bloodRequest->hospital_id = null;
        $bloodRequest->save();

        return back()->with('status', 'request-unassigned');
    }

    /**
     * F16 — Update the status of a blood request (hospital can mark as fulfilled/cancelled).
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => ['required', 'string', 'in:pending,accepted,fulfilled,cancelled'],
        ]);

        $hospital    = Auth::user()->hospital;
        $bloodRequest = BloodRequest::findOrFail($id);

        // Only the managing hospital can update status
        if ($bloodRequest->hospital_id !== $hospital->id) {
            return back()->with('error', 'You are not managing this request.');
        }

        $bloodRequest->status = $request->status;
        $bloodRequest->save();

        return back()->with('status', 'request-status-updated');
    }
}
