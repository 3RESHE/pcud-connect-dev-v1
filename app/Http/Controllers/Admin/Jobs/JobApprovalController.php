<?php

namespace App\Http\Controllers\Admin\Jobs;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class JobApprovalController extends Controller
{
    /**
     * Show pending jobs for approval
     */
    public function index()
    {
        $jobs = JobPosting::where('status', 'pending')
            ->with('partner')
            ->paginate(20);

        $data = [
            'jobs' => $jobs,
            'pending_count' => JobPosting::where('status', 'pending')->count(),
            'approved_today' => JobPosting::where('status', 'approved')
                ->whereDate('updated_at', today())
                ->count(),
            'active_count' => JobPosting::where('status', 'published')->count(),
            'rejected_count' => JobPosting::where('status', 'rejected')->count(),
        ];

        return view('users.admin.approvals.jobs.index', $data);
    }

    /**
     * Approve job posting (API endpoint)
     */
    public function approve($id)
    {
        // Blank implementation - will implement later
        return response()->json(['message' => 'Job approval not implemented yet'], 501);
    }

    /**
     * Reject job posting (API endpoint)
     */
    public function reject(Request $request, $id)
    {
        // Blank implementation - will implement later
        return response()->json(['message' => 'Job rejection not implemented yet'], 501);
    }
}
