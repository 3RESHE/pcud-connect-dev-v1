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
        $query = JobPosting::with(['partner', 'partnerProfile']);

        // Filter by status if provided
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // ✅ ENHANCED SEARCH with multiple filters
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('job_postings.title', 'like', "%{$search}%")
                    ->orWhere('job_postings.department', 'like', "%{$search}%")
                    ->orWhereHas('partnerProfile', function ($profileQuery) use ($search) {
                        $profileQuery->where('company_name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('partner', function ($partnerQuery) use ($search) {
                        $partnerQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // ✅ Filter by job type
        if ($request->has('job_type') && $request->job_type) {
            $query->where('job_type', $request->job_type);
        }

        // ✅ Filter by experience level
        if ($request->has('experience_level') && $request->experience_level) {
            $query->where('experience_level', $request->experience_level);
        }

        // ✅ Filter by work setup
        if ($request->has('work_setup') && $request->work_setup) {
            $query->where('work_setup', $request->work_setup);
        }

        // ✅ Filter by salary range
        if ($request->has('salary_min') && $request->salary_min) {
            $query->where('salary_min', '>=', $request->salary_min);
        }
        if ($request->has('salary_max') && $request->salary_max) {
            $query->where('salary_max', '<=', $request->salary_max);
        }

        // ✅ Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
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

            if ($jobPosting->status === 'approved') {
                return redirect()->back()->with('warning', 'Job posting is already approved.');
            }

            $jobPosting->update([
                'status' => 'approved',
                'sub_status' => 'active',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'rejection_reason' => null,
            ]);

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
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $jobPosting = JobPosting::findOrFail($id);

            if ($jobPosting->status === 'rejected') {
                return redirect()->back()->with('warning', 'Job posting is already rejected.');
            }

            $jobPosting->update([
                'status' => 'rejected',
                'sub_status' => null,
                'approved_by' => null,
                'rejection_reason' => $request->rejection_reason,
                'rejected_at' => now(),
                'rejected_by' => auth()->id(),
                'approved_at' => null,
            ]);

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

    /**
     * Show job posting details
     */
    public function show($id)
    {
        $jobPosting = JobPosting::with(['partner', 'partnerProfile'])->findOrFail($id);
        return view('users.admin.approvals.jobs.show', compact('jobPosting'));
    }
}
