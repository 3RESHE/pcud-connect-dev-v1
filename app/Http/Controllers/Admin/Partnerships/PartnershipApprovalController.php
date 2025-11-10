<?php

namespace App\Http\Controllers\Admin\Partnerships;

use App\Http\Controllers\Controller;
use App\Models\Partnership;
use App\Models\ActivityLog;
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
     * Move partnership to "Under Discussion" status
     */
    public function moveToDiscussion(Request $request, $id)
    {
        $partnership = Partnership::findOrFail($id);

        // Validate that it's currently submitted
        if ($partnership->status !== 'submitted') {
            return response()->json([
                'message' => 'Only pending proposals can be moved to discussion',
            ], 400);
        }

        // Update status
        $partnership->update([
            'status' => 'under_review',
        ]);

        // Send email to partner
        $this->sendEmailToPartner(
            $partnership,
            'under_review',
            $request->input('admin_notes') ?? 'We are currently reviewing your proposal. Please check your email for any clarifications or additional information needed.'
        );

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'moved_to_discussion',
            'description' => "Admin moved partnership '{$partnership->activity_title}' to under discussion",
            'model_type' => 'Partnership',
            'model_id' => $partnership->id,
        ]);

        return response()->json([
            'message' => 'Partnership moved to under discussion. Email sent to partner.',
            'partnership' => $partnership,
        ]);
    }

    /**
     * Approve partnership proposal
     */
    public function approve(Request $request, $id)
    {
        $partnership = Partnership::findOrFail($id);

        // Validate that it's either submitted or under review
        if (!in_array($partnership->status, ['submitted', 'under_review'])) {
            return response()->json([
                'message' => 'Only pending or under review proposals can be approved',
            ], 400);
        }

        $validated = $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        // Update partnership
        $partnership->update([
            'status' => 'approved',
            'admin_notes' => $validated['admin_notes'] ?? null,
        ]);

        // Send approval email to partner
        $this->sendEmailToPartner(
            $partnership,
            'approved',
            $validated['admin_notes'] ?? 'Congratulations! Your partnership proposal has been approved. Please check your email for coordination details and next steps.'
        );

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'approved',
            'description' => "Admin approved partnership '{$partnership->activity_title}'",
            'model_type' => 'Partnership',
            'model_id' => $partnership->id,
        ]);

        return response()->json([
            'message' => 'Partnership approved successfully. Email sent to partner.',
            'partnership' => $partnership,
        ]);
    }

    /**
     * Reject partnership proposal
     */
    public function reject(Request $request, $id)
    {
        $partnership = Partnership::findOrFail($id);

        // Validate that it's either submitted or under review
        if (!in_array($partnership->status, ['submitted', 'under_review'])) {
            return response()->json([
                'message' => 'Only pending or under review proposals can be rejected',
            ], 400);
        }

        $validated = $request->validate([
            'admin_notes' => 'required|string|max:1000',
        ]);

        // Update partnership
        $partnership->update([
            'status' => 'rejected',
            'admin_notes' => $validated['admin_notes'],
        ]);

        // Send rejection email to partner
        $this->sendEmailToPartner(
            $partnership,
            'rejected',
            "We appreciate your submission. However, your partnership proposal was not approved at this time. Here's our feedback: " . $validated['admin_notes'] . "\n\nYou are welcome to revise and resubmit your proposal."
        );

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'rejected',
            'description' => "Admin rejected partnership '{$partnership->activity_title}'",
            'model_type' => 'Partnership',
            'model_id' => $partnership->id,
        ]);

        return response()->json([
            'message' => 'Partnership rejected. Email sent to partner.',
            'partnership' => $partnership,
        ]);
    }

    /**
     * Mark partnership as completed
     */
    public function markComplete(Request $request, $id)
    {
        $partnership = Partnership::findOrFail($id);

        // Validate that it's approved
        if ($partnership->status !== 'approved') {
            return response()->json([
                'message' => 'Only approved partnerships can be marked as complete',
            ], 400);
        }

        $validated = $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        // Update partnership
        $partnership->update([
            'status' => 'completed',
            'completed_at' => now(),
            'admin_notes' => $validated['admin_notes'] ?? null,
        ]);

        // Send completion email to partner
        $this->sendEmailToPartner(
            $partnership,
            'completed',
            'Your partnership activity has been successfully marked as complete. Thank you for partnering with PCU-DASMA!'
        );

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'completed',
            'description' => "Admin marked partnership '{$partnership->activity_title}' as completed",
            'model_type' => 'Partnership',
            'model_id' => $partnership->id,
        ]);

        return response()->json([
            'message' => 'Partnership marked as complete. Email sent to partner.',
            'partnership' => $partnership,
        ]);
    }

    /**
     * Send email to partner based on status
     */
    private function sendEmailToPartner(Partnership $partnership, $status, $message)
    {
        try {
            $partner = $partnership->partner;

            $emailData = [
                'partner_name' => $partner->name ?? 'Partner',
                'activity_title' => $partnership->activity_title,
                'status' => $status,
                'message' => $message,
                'partnership_id' => $partnership->id,
            ];

            // Determine email subject
            $subjects = [
                'under_review' => 'Partnership Proposal Under Discussion - ' . $partnership->activity_title,
                'approved' => 'Partnership Proposal Approved! - ' . $partnership->activity_title,
                'rejected' => 'Partnership Proposal Review - ' . $partnership->activity_title,
                'completed' => 'Partnership Successfully Completed - ' . $partnership->activity_title,
            ];

            $subject = $subjects[$status] ?? 'Partnership Update';

            // Send email
            Mail::send('emails.partnership-status-update', $emailData, function ($message) use ($partner, $subject) {
                $message->to($partner->email)
                    ->subject($subject);
            });

        } catch (\Exception $e) {
            // Log error but don't fail the operation
            \Log::error('Failed to send partnership email: ' . $e->getMessage());
        }
    }
}
