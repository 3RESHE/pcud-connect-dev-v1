<?php

namespace App\Http\Controllers\Admin\Partnerships;

use App\Http\Controllers\Controller;
use App\Models\Partnership;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class PartnershipApprovalController extends Controller
{
    /**
     * Show pending partnerships for approval
     */
    public function index()
    {
        $partnerships = Partnership::whereIn('status', ['submitted', 'under_review'])
            ->with('partner')
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
     * Approve partnership (API endpoint)
     */
    public function approve(Request $request, $id)
    {
        // Blank implementation - will implement later
        return response()->json(['message' => 'Partnership approval not implemented yet'], 501);
    }

    /**
     * Reject partnership (API endpoint)
     */
    public function reject(Request $request, $id)
    {
        // Blank implementation - will implement later
        return response()->json(['message' => 'Partnership rejection not implemented yet'], 501);
    }
}
