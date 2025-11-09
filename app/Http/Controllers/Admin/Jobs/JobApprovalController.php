<?php

namespace App\Http\Controllers\Admin\Jobs;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JobApprovalController extends Controller
{
    /**
     * Display job postings pending approval
     */
    public function index(Request $request)
    {
        $query = JobPosting::with(['partner']);

        // Filter by status if provided
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('department', 'like', "%{$search}%")
                    ->orWhereHas('partner', function ($partnerQuery) use ($search) {
                        $partnerQuery->where('company_name', 'like', "%{$search}%");
                    });
            });
        }

        // Order by created date (newest first)
        $jobPostings = $query->orderBy('created_at', 'desc')->paginate(10);

        // Get statistics
        $stats = [
            'pending' => JobPosting::where('status', 'pending')->count(),
            'approved_today' => JobPosting::where('status', 'approved')
                ->whereDate('updated_at', today())
                ->count(),
            'active' => JobPosting::where('status', 'approved')
                ->where('sub_status', 'active')
                ->count(),
            'rejected' => JobPosting::where('status', 'rejected')->count(),
        ];

        return view('users.admin.approvals.jobs.index', compact('jobPostings', 'stats'));
    }

    /**
     * Approve a job posting
     */
    public function approve($id)
    {
        try {
            DB::beginTransaction();

            $jobPosting = JobPosting::findOrFail($id);

            // Check if already approved
            if ($jobPosting->status === 'approved') {
                return redirect()->back()->with('warning', 'Job posting is already approved.');
            }

            // Update job posting status
            $jobPosting->update([
                'status' => 'approved',
                'sub_status' => 'active',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'rejection_reason' => null,
            ]);

            // Log activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'approved',
                'description' => "Approved job posting: {$jobPosting->title}",
                'subject_type' => JobPosting::class,
                'subject_id' => $jobPosting->id,
                'properties' => json_encode([
                    'job_title' => $jobPosting->title,
                    'partner_id' => $jobPosting->partner_id,
                    'approved_at' => now(),
                ]),
            ]);

            // TODO: Send notification to partner
            // Notification::send($jobPosting->partner, new JobApproved($jobPosting));

            DB::commit();

            return redirect()->back()->with('success', 'Job posting approved and published successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to approve job posting: ' . $e->getMessage());
        }
    }


    /**
     * Reject a job posting
     */
    /**
     * Reject a job posting
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $jobPosting = JobPosting::findOrFail($id);

            // Check if already rejected
            if ($jobPosting->status === 'rejected') {
                return redirect()->back()->with('warning', 'Job posting is already rejected.');
            }

            // Update job posting status
            $jobPosting->update([
                'status' => 'rejected',
                'sub_status' => null,  // ✅ NOW THIS WILL WORK!
                'approved_by' => null,
                'rejection_reason' => $request->rejection_reason,
                'rejected_at' => now(),
                'rejected_by' => auth()->id(),
                'approved_at' => null,
            ]);

            // Log activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'rejected',
                'description' => "Rejected job posting: {$jobPosting->title}",
                'subject_type' => JobPosting::class,
                'subject_id' => $jobPosting->id,
                'properties' => json_encode([
                    'job_title' => $jobPosting->title,
                    'partner_id' => $jobPosting->partner_id,
                    'rejection_reason' => $request->rejection_reason,
                    'rejected_at' => now(),
                ]),
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Job posting rejected successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to reject job posting: ' . $e->getMessage());
        }
    }





    public function show($id)
    {
        $jobPosting = JobPosting::with('partner')->findOrFail($id);
        return view('users.admin.approvals.jobs.show', compact('jobPosting'));
    }
}
