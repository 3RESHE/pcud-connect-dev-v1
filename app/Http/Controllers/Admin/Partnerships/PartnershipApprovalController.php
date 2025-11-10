<?php

namespace App\Http\Controllers\Admin\Partnerships;

use App\Http\Controllers\Controller;
use App\Models\Partnership;
use App\Models\ActivityLog;
use App\Mail\PartnershipDiscussionMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PartnershipApprovalController extends Controller
{
    /**
     * Show pending partnerships for approval (Admin Dashboard)
     */
    public function index()
    {
        $partnerships = Partnership::with('partner')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $data = [
            'partnerships' => $partnerships,
            'total_count' => Partnership::count(),
            'pending_count' => Partnership::where('status', 'submitted')->count(),
            'discussion_count' => Partnership::where('status', 'under_review')->count(),
            'approved_count' => Partnership::where('status', 'approved')->count(),
            'rejected_count' => Partnership::where('status', 'rejected')->count(),
            'completed_count' => Partnership::where('status', 'completed')->count(),
        ];

        return view('users.admin.approvals.partnerships.index', $data);
    }

    /**
     * Show partnership details for admin review
     */
    public function show($id)
    {
        $partnership = Partnership::with('partner')->findOrFail($id);

        return view('users.admin.approvals.partnerships.show', [
            'partnership' => $partnership,
        ]);
    }

    /**
     * Move partnership to "Under Discussion" status and send email to partner
     * THIS IS THE ONLY METHOD THAT SENDS EMAIL
     */
    public function moveToDiscussion(Request $request, $id)
    {
        $partnership = Partnership::findOrFail($id);

        // Validate that it's currently submitted
        if ($partnership->status !== 'submitted') {
            return redirect()->back()->with('error', 'Only pending proposals can be moved to discussion');
        }

        // Validate the message from the form
        $validated = $request->validate([
            'admin_notes' => 'required|string|min:10|max:2000',
        ]);

        try {
            // Send email to partner with the admin's message
            Mail::send(new PartnershipDiscussionMail(
                $partnership,
                $validated['admin_notes'],
                auth()->user()->name
            ));

            // Update partnership status
            $partnership->update([
                'status' => 'under_review',
                'admin_notes' => $validated['admin_notes'],
            ]);

            // Log activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'moved_to_discussion',
                'description' => "Admin moved partnership '{$partnership->activity_title}' to under discussion and sent email to partner",
                'model_type' => 'Partnership',
                'model_id' => $partnership->id,
            ]);

            return redirect()->back()->with('success', 'Partnership moved to discussion and email sent to partner successfully!');

        } catch (\Exception $e) {
            \Log::error('Failed to send partnership discussion email: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Email failed to send. Please try again.');
        }
    }

    /**
     * Approve partnership proposal (NO EMAIL)
     */
    public function approve(Request $request, $id)
    {
        $partnership = Partnership::findOrFail($id);

        if (!in_array($partnership->status, ['submitted', 'under_review'])) {
            return redirect()->back()->with('error', 'Only pending or under review proposals can be approved');
        }

        $validated = $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $partnership->update([
            'status' => 'approved',
            'admin_notes' => $validated['admin_notes'] ?? null,
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'approved',
            'description' => "Admin approved partnership '{$partnership->activity_title}'",
            'model_type' => 'Partnership',
            'model_id' => $partnership->id,
        ]);

        return redirect()->back()->with('success', 'Partnership approved successfully!');
    }

    /**
     * Reject partnership proposal (NO EMAIL)
     */
    public function reject(Request $request, $id)
    {
        $partnership = Partnership::findOrFail($id);

        if (!in_array($partnership->status, ['submitted', 'under_review'])) {
            return redirect()->back()->with('error', 'Only pending or under review proposals can be rejected');
        }

        $validated = $request->validate([
            'admin_notes' => 'required|string|max:1000',
        ]);

        $partnership->update([
            'status' => 'rejected',
            'admin_notes' => $validated['admin_notes'],
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'rejected',
            'description' => "Admin rejected partnership '{$partnership->activity_title}'",
            'model_type' => 'Partnership',
            'model_id' => $partnership->id,
        ]);

        return redirect()->back()->with('success', 'Partnership rejected successfully!');
    }

    /**
     * Mark partnership as completed (NO EMAIL)
     */
    public function markComplete(Request $request, $id)
    {
        $partnership = Partnership::findOrFail($id);

        if ($partnership->status !== 'approved') {
            return redirect()->back()->with('error', 'Only approved partnerships can be marked as complete');
        }

        $validated = $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $partnership->update([
            'status' => 'completed',
            'completed_at' => now(),
            'admin_notes' => $validated['admin_notes'] ?? null,
        ]);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'completed',
            'description' => "Admin marked partnership '{$partnership->activity_title}' as completed",
            'model_type' => 'Partnership',
            'model_id' => $partnership->id,
        ]);

        return redirect()->back()->with('success', 'Partnership marked as complete!');
    }
}
