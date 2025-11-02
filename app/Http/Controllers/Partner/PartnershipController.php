<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\Partnership;
use Illuminate\Http\Request;

class PartnershipController extends Controller
{
    /**
     * Display all partnerships for partner
     */
    public function index()
    {
        $partner = auth()->user();

        $partnerships = Partnership::where('partner_id', $partner->id)
            ->latest('created_at')
            ->paginate(20);

        $data = [
            'partnerships' => $partnerships,
            'pending_count' => Partnership::where('partner_id', $partner->id)
                ->where('status', 'submitted')
                ->count(),
            'discussion_count' => Partnership::where('partner_id', $partner->id)
                ->where('status', 'under_review')
                ->count(),
            'approved_count' => Partnership::where('partner_id', $partner->id)
                ->where('status', 'approved')
                ->count(),
            'rejected_count' => Partnership::where('partner_id', $partner->id)
                ->where('status', 'rejected')
                ->count(),
            'completed_count' => Partnership::where('partner_id', $partner->id)
                ->where('status', 'completed')
                ->count(),
            'total_partnerships' => Partnership::where('partner_id', $partner->id)->count(),
        ];

        return view('users.partner.partnerships.index', $data);
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('users.partner.partnerships.create');
    }

    /**
     * Store partnership
     */
    public function store(Request $request)
    {
        // Blank implementation - will implement later
        return redirect()->route('partner.partnerships.index')
            ->with('success', 'Partnership proposal submitted successfully!');
    }

    /**
     * Show partnership
     */
    public function show(Partnership $partnership)
    {
        // Blank implementation - will implement later
        return view('users.partner.partnerships.show', compact('partnership'));
    }

    /**
     * Show edit form
     */
    public function edit(Partnership $partnership)
    {
        // Blank implementation - will implement later
        return view('users.partner.partnerships.edit', compact('partnership'));
    }

    /**
     * Update partnership
     */
    public function update(Request $request, Partnership $partnership)
    {
        // Blank implementation - will implement later
        return redirect()->route('partner.partnerships.index')
            ->with('success', 'Partnership proposal updated successfully!');
    }

    /**
     * Mark partnership as complete
     */
    public function complete(Partnership $partnership)
    {
        $partnership->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
        return redirect()->back()->with('success', 'Partnership marked as complete!');
    }

    /**
     * Delete partnership
     */
    public function destroy(Partnership $partnership)
    {
        // Blank implementation - will implement later
        return redirect()->back()->with('success', 'Partnership deleted!');
    }
}
