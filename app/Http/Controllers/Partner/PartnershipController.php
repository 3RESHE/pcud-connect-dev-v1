<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\Partnership;
use App\Helpers\ActivityLogger;
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
        $validated = $request->validate([
            'activity_type' => 'required|in:feedingprogram,brigadaeskwela,communitycleanup,treeplanting,donationdrive,other',
            'custom_activity_type' => 'required_if:activity_type,other|max:255',
            'organization_name' => 'required|string|max:255',
            'organization_background' => 'required|string|min:50|max:2000',
            'organization_website' => 'nullable|url|max:255',
            'organization_phone' => 'required|string|max:20',
            'activity_title' => 'required|string|max:255',
            'activity_description' => 'required|string|min:50',
            'activity_date' => 'required|date|after:today',
            'activity_time' => 'required|date_format:H:i',
            'venue_address' => 'required|string|min:10',
            'activity_objectives' => 'required|string|min:50',
            'expected_impact' => 'required|string|min:50',
            'contact_name' => 'required|string|max:255',
            'contact_position' => 'required|string|max:255',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|max:20',
            'previous_experience' => 'nullable|string',
            'additional_notes' => 'nullable|string',
        ]);

        $partnership = Partnership::create([
            ...$validated,
            'partner_id' => auth()->id(),
            'status' => 'submitted',
        ]);

        ActivityLogger::log(
            'partnership_submitted',
            Partnership::class,
            $partnership->id,
            $validated,
            "Partner submitted partnership proposal: {$validated['activity_title']}"
        );

        return redirect()->route('partner.partnerships.index')
            ->with('success', '✅ Partnership proposal submitted successfully! Admins will review it shortly.');
    }

    /**
     * Show partnership
     */
    public function show(Partnership $partnership)
    {
        // Middleware already checks if user is partner and authenticated
        // Only show partnerships belonging to the current partner
        if ($partnership->partner_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this partnership.');
        }

        return view('users.partner.partnerships.show', compact('partnership'));
    }

    /**
     * Show edit form
     */
    public function edit(Partnership $partnership)
    {
        // Middleware already checks if user is partner and authenticated
        // Only allow editing partnerships belonging to the current partner
        if ($partnership->partner_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this partnership.');
        }

        // Only allow editing if not approved or completed
        if (in_array($partnership->status, ['approved', 'completed', 'rejected'])) {
            return redirect()->route('partner.partnerships.show', $partnership)
                ->with('error', '❌ You cannot edit a ' . $partnership->status . ' partnership.');
        }

        return view('users.partner.partnerships.edit', compact('partnership'));
    }

    /**
     * Update partnership
     */
    public function update(Request $request, Partnership $partnership)
    {
        // Middleware already checks if user is partner and authenticated
        // Only allow updating partnerships belonging to the current partner
        if ($partnership->partner_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this partnership.');
        }

        // Prevent updating completed or approved partnerships
        if (in_array($partnership->status, ['approved', 'completed'])) {
            return redirect()->route('partner.partnerships.index')
                ->with('error', '❌ Cannot update ' . $partnership->status . ' partnerships.');
        }

        $validated = $request->validate([
            'activity_type' => 'required|in:feedingprogram,brigadaeskwela,communitycleanup,treeplanting,donationdrive,other',
            'custom_activity_type' => 'required_if:activity_type,other|max:255',
            'organization_name' => 'required|string|max:255',
            'organization_background' => 'required|string|min:50|max:2000',
            'organization_website' => 'nullable|url|max:255',
            'organization_phone' => 'required|string|max:20',
            'activity_title' => 'required|string|max:255',
            'activity_description' => 'required|string|min:50',
            'activity_date' => 'required|date|after:today',
            'activity_time' => 'required|date_format:H:i',
            'venue_address' => 'required|string|min:10',
            'activity_objectives' => 'required|string|min:50',
            'expected_impact' => 'required|string|min:50',
            'contact_name' => 'required|string|max:255',
            'contact_position' => 'required|string|max:255',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|max:20',
            'previous_experience' => 'nullable|string',
            'additional_notes' => 'nullable|string',
        ]);

        $partnership->update($validated);

        ActivityLogger::log(
            'partnership_updated',
            Partnership::class,
            $partnership->id,
            $validated,
            "Partner updated partnership proposal: {$validated['activity_title']}"
        );

        return redirect()->route('partner.partnerships.index')
            ->with('success', '✅ Partnership proposal updated successfully!');
    }

    /**
     * Mark partnership as complete
     */
    public function complete(Partnership $partnership)
    {
        // Middleware already checks if user is partner and authenticated
        // Only allow completing partnerships belonging to the current partner
        if ($partnership->partner_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this partnership.');
        }

        // Only approved partnerships can be marked as complete
        if ($partnership->status !== 'approved') {
            return redirect()->back()
                ->with('error', '❌ Only approved partnerships can be marked as complete.');
        }

        $partnership->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        ActivityLogger::log(
            'partnership_completed',
            Partnership::class,
            $partnership->id,
            [],
            "Partner marked partnership as complete: {$partnership->activity_title}"
        );

        return redirect()->route('partner.partnerships.index')
            ->with('success', '✅ Partnership marked as complete!');
    }

    /**
     * Delete partnership
     */
    public function destroy(Partnership $partnership)
    {
        // Middleware already checks if user is partner and authenticated
        // Only allow deleting partnerships belonging to the current partner
        if ($partnership->partner_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this partnership.');
        }

        // Only allow deleting if submitted or rejected
        if (!in_array($partnership->status, ['submitted', 'rejected'])) {
            return redirect()->route('partner.partnerships.index')
                ->with('error', '❌ You can only delete submitted or rejected partnerships.');
        }

        $partnership->delete();

        ActivityLogger::log(
            'partnership_deleted',
            Partnership::class,
            $partnership->id,
            [],
            "Partner deleted partnership proposal: {$partnership->activity_title}"
        );

        return redirect()->route('partner.partnerships.index')
            ->with('success', '✅ Partnership deleted successfully!');
    }
}
