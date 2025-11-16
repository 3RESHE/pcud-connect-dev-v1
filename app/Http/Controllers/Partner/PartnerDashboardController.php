<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use App\Models\JobApplication;
use App\Models\Partnership;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class PartnerDashboardController extends Controller
{
    /**
     * Display partner dashboard with comprehensive analytics
     */
    public function index()
    {
        $partner = auth()->user();

        // ✅ SECTION 1: KEY STATISTICS (Real Data)
        $activeJobs = JobPosting::where('partner_id', $partner->id)
            ->where('status', 'approved')
            ->where('sub_status', 'active')
            ->count();

        $totalApplications = JobPosting::where('partner_id', $partner->id)
            ->withCount('applications')
            ->get()
            ->sum('applications_count');

        $approvedApplications = JobApplication::whereHas('jobPosting', fn($q) =>
            $q->where('partner_id', $partner->id)
        )->where('status', 'approved')->count();

        $pendingApplications = JobApplication::whereHas('jobPosting', fn($q) =>
            $q->where('partner_id', $partner->id)
        )->where('status', 'pending')->count();

        $rejectedApplications = JobApplication::whereHas('jobPosting', fn($q) =>
            $q->where('partner_id', $partner->id)
        )->where('status', 'rejected')->count();

        // ✅ SECTION 2: APPLICATION METRICS
        $approvalRate = $totalApplications > 0
            ? round(($approvedApplications / $totalApplications) * 100, 1)
            : 0;

        // ✅ SECTION 3: JOB PERFORMANCE ANALYTICS
        $jobPerformance = JobPosting::where('partner_id', $partner->id)
            ->with(['applications' => function($query) {
                $query->selectRaw('job_posting_id, status')
                    ->orderBy('created_at', 'desc');
            }])
            ->where('status', 'approved')
            ->get()
            ->map(function($job) {
                $applications = $job->applications;
                return [
                    'id' => $job->id,
                    'title' => $job->title,
                    'total_applications' => $applications->count(),
                    'approved' => $applications->where('status', 'approved')->count(),
                    'pending' => $applications->where('status', 'pending')->count(),
                    'contacted' => $applications->where('status', 'contacted')->count(),
                    'rejected' => $applications->where('status', 'rejected')->count(),
                    'approval_rate' => $applications->count() > 0
                        ? round(($applications->where('status', 'approved')->count() / $applications->count()) * 100, 1)
                        : 0,
                    'views_count' => $job->views_count ?? 0,
                ];
            })
            ->sortByDesc('total_applications')
            ->take(5);

        // ✅ SECTION 4: PENDING REVIEWS & APPROVALS
        $jobsPendingApproval = JobPosting::where('partner_id', $partner->id)
            ->where('status', 'pending')
            ->count();

        $partnershipsPendingApproval = Partnership::where('partner_id', $partner->id)
            ->whereIn('status', ['submitted', 'under_review'])
            ->count();

        $applicationsPendingReview = JobApplication::whereHas('jobPosting', fn($q) =>
            $q->where('partner_id', $partner->id)
        )->where('status', 'pending')->count();

        // ✅ SECTION 5: RECENT ACTIVITY FEED
        $recentApplications = JobApplication::whereHas('jobPosting', fn($q) =>
            $q->where('partner_id', $partner->id)
        )
            ->with(['applicant', 'jobPosting'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $recentApprovals = JobApplication::whereHas('jobPosting', fn($q) =>
            $q->where('partner_id', $partner->id)
        )
            ->where('status', 'approved')
            ->with(['applicant', 'jobPosting'])
            ->orderBy('reviewed_at', 'desc')
            ->take(5)
            ->get();

        // ✅ SECTION 6: JOB POSTINGS DATA
        $recentJobs = JobPosting::where('partner_id', $partner->id)
            ->with('applications')
            ->latest('created_at')
            ->take(5)
            ->get()
            ->map(function($job) {
                return [
                    'id' => $job->id,
                    'title' => $job->title,
                    'status' => $job->status,
                    'applications_count' => $job->applications->count(),
                    'approved_count' => $job->applications->where('status', 'approved')->count(),
                    'created_at' => $job->created_at,
                ];
            });

        // ✅ SECTION 7: PARTNERSHIP DATA
        $activePartnerships = Partnership::where('partner_id', $partner->id)
            ->where('status', 'approved')
            ->count();

        $recentPartnerships = Partnership::where('partner_id', $partner->id)
            ->latest('created_at')
            ->take(3)
            ->get();

        // ✅ SECTION 8: ALERTS & NOTIFICATIONS
        $alerts = [
            'jobs_pending_approval' => $jobsPendingApproval,
            'partnerships_pending_approval' => $partnershipsPendingApproval,
            'applications_pending_review' => $applicationsPendingReview,
            'applications_nearing_deadline' => JobPosting::where('partner_id', $partner->id)
                ->where('application_deadline', '<=', now()->addDays(7))
                ->where('application_deadline', '>', now())
                ->count(),
        ];

        return view('users.partner.dashboard', [
            'partner' => $partner,

            // Statistics
            'active_jobs' => $activeJobs,
            'total_applications' => $totalApplications,
            'approved_applications' => $approvedApplications,
            'pending_applications' => $pendingApplications,
            'rejected_applications' => $rejectedApplications,
            'approval_rate' => $approvalRate,

            // Job Performance
            'job_performance' => $jobPerformance,
            'top_job' => $jobPerformance->first(),

            // Pending Reviews
            'jobs_pending_approval' => $jobsPendingApproval,
            'partnerships_pending_approval' => $partnershipsPendingApproval,
            'applications_pending_review' => $applicationsPendingReview,

            // Recent Activity
            'recent_applications' => $recentApplications,
            'recent_approvals' => $recentApprovals,
            'recent_jobs' => $recentJobs,
            'active_partnerships' => $activePartnerships,
            'recent_partnerships' => $recentPartnerships,

            // Alerts
            'alerts' => $alerts,
        ]);
    }

    /**
     * Get job performance statistics (AJAX)
     */
    public function jobStats(Request $request)
    {
        $partner = auth()->user();

        $stats = JobPosting::where('partner_id', $partner->id)
            ->where('status', 'approved')
            ->selectRaw('
                id,
                title,
                views_count,
                (SELECT COUNT(*) FROM job_applications WHERE job_posting_id = job_postings.id) as total_applications,
                (SELECT COUNT(*) FROM job_applications WHERE job_posting_id = job_postings.id AND status = "approved") as approved,
                (SELECT COUNT(*) FROM job_applications WHERE job_posting_id = job_postings.id AND status = "pending") as pending,
                (SELECT COUNT(*) FROM job_applications WHERE job_posting_id = job_postings.id AND status = "rejected") as rejected
            ')
            ->get()
            ->map(function($job) {
                $total = $job->total_applications;
                return [
                    'title' => $job->title,
                    'views' => $job->views_count ?? 0,
                    'applications' => $total,
                    'approved' => $job->approved ?? 0,
                    'pending' => $job->pending ?? 0,
                    'rejected' => $job->rejected ?? 0,
                    'approval_rate' => $total > 0 ? round(($job->approved / $total) * 100, 1) : 0,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }

    /**
     * Get application status breakdown (AJAX)
     */
    public function applicationStats(Request $request)
    {
        $partner = auth()->user();

        $stats = [
            'pending' => JobApplication::whereHas('jobPosting', fn($q) =>
                $q->where('partner_id', $partner->id)
            )->where('status', 'pending')->count(),

            'contacted' => JobApplication::whereHas('jobPosting', fn($q) =>
                $q->where('partner_id', $partner->id)
            )->where('status', 'contacted')->count(),

            'approved' => JobApplication::whereHas('jobPosting', fn($q) =>
                $q->where('partner_id', $partner->id)
            )->where('status', 'approved')->count(),

            'rejected' => JobApplication::whereHas('jobPosting', fn($q) =>
                $q->where('partner_id', $partner->id)
            )->where('status', 'rejected')->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }

    /**
     * Get recent activity feed (AJAX)
     */
    public function activityFeed(Request $request)
    {
        $partner = auth()->user();

        $activities = JobApplication::whereHas('jobPosting', fn($q) =>
            $q->where('partner_id', $partner->id)
        )
            ->with(['applicant', 'jobPosting'])
            ->latest('updated_at')
            ->take(10)
            ->get()
            ->map(function($app) {
                return [
                    'applicant_name' => $app->applicant->name,
                    'job_title' => $app->jobPosting->title,
                    'status' => $app->status,
                    'timestamp' => $app->updated_at->diffForHumans(),
                    'icon' => $this->getStatusIcon($app->status),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $activities,
        ]);
    }

    /**
     * Helper: Get status icon
     */
    private function getStatusIcon($status)
    {
        return match($status) {
            'approved' => '✅',
            'rejected' => '❌',
            'contacted' => '📧',
            'pending' => '⏱️',
            default => '•',
        };
    }
}
