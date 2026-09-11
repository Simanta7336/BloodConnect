<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CampaignController extends Controller
{
    /**
     * Helper to verify current user is a system administrator.
     */
    protected function authorizeAdmin(): void
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized. Only administrators can manage blood donation campaigns.');
        }
    }

    /**
     * Display a listing of blood donation campaigns.
     * Open to all authenticated users.
     */
    public function index()
    {
        $campaigns = Campaign::orderBy('campaign_date', 'asc')->get();

        return view('campaigns.index', compact('campaigns'));
    }

    /**
     * Show the form for creating a new campaign.
     * Restricted to administrators.
     */
    public function create()
    {
        $this->authorizeAdmin();

        return view('campaigns.create');
    }

    /**
     * Store a newly created campaign in storage.
     * Restricted to administrators.
     */
    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'title'         => ['required', 'string', 'max:255'],
            'description'   => ['required', 'string'],
            'campaign_date' => ['required', 'date'],
            'start_time'    => ['required', 'date_format:H:i,H:i:s'],
            'end_time'      => ['required', 'date_format:H:i,H:i:s', 'after:start_time'],
            'location'      => ['required', 'string', 'max:255'],
            'organizer'     => ['required', 'string', 'max:255'],
            'contact'       => ['required', 'string', 'max:255'],
            'status'        => ['required', 'in:upcoming,ongoing,completed,cancelled'],
        ]);

        Campaign::create($validated);

        return redirect()
            ->route('campaigns.index')
            ->with('status', 'campaign-created');
    }

    /**
     * Display the specified campaign.
     * Open to all authenticated users.
     */
    public function show(Campaign $campaign)
    {
        return view('campaigns.show', compact('campaign'));
    }

    /**
     * Show the form for editing the specified campaign.
     * Restricted to administrators.
     */
    public function edit(Campaign $campaign)
    {
        $this->authorizeAdmin();

        return view('campaigns.edit', compact('campaign'));
    }

    /**
     * Update the specified campaign in storage.
     * Restricted to administrators.
     */
    public function update(Request $request, Campaign $campaign)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'title'         => ['required', 'string', 'max:255'],
            'description'   => ['required', 'string'],
            'campaign_date' => ['required', 'date'],
            'start_time'    => ['required', 'date_format:H:i,H:i:s'],
            'end_time'      => ['required', 'date_format:H:i,H:i:s', 'after:start_time'],
            'location'      => ['required', 'string', 'max:255'],
            'organizer'     => ['required', 'string', 'max:255'],
            'contact'       => ['required', 'string', 'max:255'],
            'status'        => ['required', 'in:upcoming,ongoing,completed,cancelled'],
        ]);

        $campaign->update($validated);

        return redirect()
            ->route('campaigns.show', $campaign->id)
            ->with('status', 'campaign-updated');
    }

    /**
     * Remove the specified campaign from storage.
     * Restricted to administrators.
     */
    public function destroy(Campaign $campaign)
    {
        $this->authorizeAdmin();

        $campaign->delete();

        return redirect()
            ->route('campaigns.index')
            ->with('status', 'campaign-deleted');
    }
}