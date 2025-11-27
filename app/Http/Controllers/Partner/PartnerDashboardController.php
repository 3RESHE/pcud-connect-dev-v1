<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use App\Models\JobApplication;
use App\Models\Partnership;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

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

        $contactedApplications = JobApplication::whereHas('jobPosting', fn($q) =>
            $q->where('partner_id', $partner->id)
        )->where('status', 'contacted')->count();

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

        $jobsRejected = JobPosting::where('partner_id', $partner->id)
            ->where('status', 'rejected')
            ->count();

        $jobsCompleted = JobPosting::where('partner_id', $partner->id)
            ->where('status', 'completed')
            ->count();

        $partnershipsPendingApproval = Partnership::where('partner_id', $partner->id)
            ->whereIn('status', ['submitted', 'under_review'])
            ->count();

        $partnershipsApproved = Partnership::where('partner_id', $partner->id)
            ->where('status', 'approved')
            ->count();

        $partnershipsCompleted = Partnership::where('partner_id', $partner->id)
            ->where('status', 'completed')
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
            'jobs_rejected' => $jobsRejected,
            'partnerships_pending_approval' => $partnershipsPendingApproval,
            'applications_pending_review' => $applicationsPendingReview,
            'applications_nearing_deadline' => JobPosting::where('partner_id', $partner->id)
                ->where('application_deadline', '<=', now()->addDays(7))
                ->where('application_deadline', '>', now())
                ->count(),
        ];

        // ✅ SECTION 9: JOB STATUS BREAKDOWN
        $jobStatusStats = [
            'pending' => $jobsPendingApproval,
            'approved' => JobPosting::where('partner_id', $partner->id)
                ->where('status', 'approved')
                ->count(),
            'rejected' => $jobsRejected,
            'completed' => $jobsCompleted,
        ];

        // ✅ SECTION 10: PARTNERSHIP STATUS BREAKDOWN
        $partnershipStatusStats = [
            'submitted' => Partnership::where('partner_id', $partner->id)
                ->where('status', 'submitted')
                ->count(),
            'under_review' => Partnership::where('partner_id', $partner->id)
                ->where('status', 'under_review')
                ->count(),
            'approved' => $partnershipsApproved,
            'completed' => $partnershipsCompleted,
        ];

        return view('users.partner.dashboard', [
            'partner' => $partner,

            // Statistics
            'active_jobs' => $activeJobs,
            'total_applications' => $totalApplications,
            'approved_applications' => $approvedApplications,
            'pending_applications' => $pendingApplications,
            'contacted_applications' => $contactedApplications,
            'rejected_applications' => $rejectedApplications,
            'approval_rate' => $approvalRate,

            // Job Performance
            'job_performance' => $jobPerformance,
            'top_job' => $jobPerformance->first(),

            // Pending Reviews
            'jobs_pending_approval' => $jobsPendingApproval,
            'partnerships_pending_approval' => $partnershipsPendingApproval,
            'applications_pending_review' => $applicationsPendingReview,

            // Status Breakdowns
            'job_status_stats' => $jobStatusStats,
            'partnership_status_stats' => $partnershipStatusStats,

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
     * Export dashboard report to Excel
     * Supports multiple report types: comprehensive, jobs, partnerships, applications
     */
    public function exportExcel(Request $request)
    {
        try {
            $partner = auth()->user();
            $reportType = $request->query('type', 'comprehensive');

            $fileName = "partner-report-{$reportType}-" . now()->format('Y-m-d-His') . ".xlsx";

            return Excel::download(
                new PartnerDashboardExport($partner->id, $reportType),
                $fileName
            );
        } catch (\Exception $e) {
            \Log::error('Partner Excel export failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to export report: ' . $e->getMessage());
        }
    }

    /**
     * Export job postings to Excel with detailed statistics
     */
    public function exportJobs(Request $request)
    {
        try {
            $partner = auth()->user();
            $status = $request->query('status', 'all');

            $fileName = "jobs-" . ($status !== 'all' ? $status . '-' : '') . now()->format('Y-m-d-His') . ".xlsx";

            return Excel::download(
                new JobPostingsExport($partner->id, $status),
                $fileName
            );
        } catch (\Exception $e) {
            \Log::error('Jobs Excel export failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to export jobs: ' . $e->getMessage());
        }
    }

    /**
     * Export partnerships to Excel
     */
    public function exportPartnerships(Request $request)
    {
        try {
            $partner = auth()->user();
            $status = $request->query('status', 'all');

            $fileName = "partnerships-" . ($status !== 'all' ? $status . '-' : '') . now()->format('Y-m-d-His') . ".xlsx";

            return Excel::download(
                new PartnershipsExport($partner->id, $status),
                $fileName
            );
        } catch (\Exception $e) {
            \Log::error('Partnerships Excel export failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to export partnerships: ' . $e->getMessage());
        }
    }

    /**
     * Export applications to Excel with detailed status breakdown
     */
    public function exportApplications(Request $request)
    {
        try {
            $partner = auth()->user();
            $status = $request->query('status', 'all');

            $fileName = "applications-" . ($status !== 'all' ? $status . '-' : '') . now()->format('Y-m-d-His') . ".xlsx";

            return Excel::download(
                new ApplicationsExport($partner->id, $status),
                $fileName
            );
        } catch (\Exception $e) {
            \Log::error('Applications Excel export failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to export applications: ' . $e->getMessage());
        }
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
                (SELECT COUNT(*) FROM job_applications WHERE job_posting_id = job_postings.id AND status = "contacted") as contacted,
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
                    'contacted' => $job->contacted ?? 0,
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

/**
 * Partner Dashboard Comprehensive Excel Export
 */
class PartnerDashboardExport implements FromArray, WithHeadings, ShouldAutoSize
{
    private $partnerId;
    private $reportType;

    public function __construct($partnerId, $reportType = 'comprehensive')
    {
        $this->partnerId = $partnerId;
        $this->reportType = $reportType;
    }

    public function array(): array
    {
        return match ($this->reportType) {
            'comprehensive' => $this->getComprehensiveData(),
            'jobs' => $this->getJobsData(),
            'partnerships' => $this->getPartnershipsData(),
            'applications' => $this->getApplicationsData(),
            default => $this->getComprehensiveData(),
        };
    }

    public function headings(): array
    {
        return match ($this->reportType) {
            'jobs' => ['Job Title', 'Status', 'Featured', 'Applications', 'Approved', 'Pending', 'Contacted', 'Rejected', 'Approval Rate', 'Created At'],
            'partnerships' => ['Activity Title', 'Organization', 'Status', 'Activity Date', 'Contact Person', 'Created At'],
            'applications' => ['Applicant Name', 'Job Title', 'Status', 'Applied Date', 'Reviewed Date', 'Last Contacted'],
            default => ['Category', 'Count'],
        };
    }

    private function getComprehensiveData(): array
    {
        $data = [];

        // JOB STATISTICS
        $data[] = ['JOB POSTINGS STATISTICS'];
        $data[] = ['Total Job Postings', JobPosting::where('partner_id', $this->partnerId)->count() ?? 0];
        $data[] = ['Active Postings', JobPosting::where('partner_id', $this->partnerId)->where('status', 'approved')->where('sub_status', 'active')->count() ?? 0];
        $data[] = ['Pending Approval', JobPosting::where('partner_id', $this->partnerId)->where('status', 'pending')->count() ?? 0];
        $data[] = ['Rejected', JobPosting::where('partner_id', $this->partnerId)->where('status', 'rejected')->count() ?? 0];
        $data[] = ['Completed', JobPosting::where('partner_id', $this->partnerId)->where('status', 'completed')->count() ?? 0];
        $data[] = ['Featured', JobPosting::where('partner_id', $this->partnerId)->where('is_featured', true)->count() ?? 0];
        $data[] = [];

        // APPLICATION STATISTICS
        $data[] = ['APPLICATION STATISTICS'];
        $data[] = ['Total Applications', JobApplication::whereHas('jobPosting', fn($q) => $q->where('partner_id', $this->partnerId))->count() ?? 0];
        $data[] = ['Pending Review', JobApplication::whereHas('jobPosting', fn($q) => $q->where('partner_id', $this->partnerId))->where('status', 'pending')->count() ?? 0];
        $data[] = ['Contacted', JobApplication::whereHas('jobPosting', fn($q) => $q->where('partner_id', $this->partnerId))->where('status', 'contacted')->count() ?? 0];
        $data[] = ['Approved', JobApplication::whereHas('jobPosting', fn($q) => $q->where('partner_id', $this->partnerId))->where('status', 'approved')->count() ?? 0];
        $data[] = ['Rejected', JobApplication::whereHas('jobPosting', fn($q) => $q->where('partner_id', $this->partnerId))->where('status', 'rejected')->count() ?? 0];
        $data[] = [];

        // PARTNERSHIP STATISTICS
        $data[] = ['PARTNERSHIP STATISTICS'];
        $data[] = ['Total Partnerships', Partnership::where('partner_id', $this->partnerId)->count() ?? 0];
        $data[] = ['Submitted', Partnership::where('partner_id', $this->partnerId)->where('status', 'submitted')->count() ?? 0];
        $data[] = ['Under Review', Partnership::where('partner_id', $this->partnerId)->where('status', 'under_review')->count() ?? 0];
        $data[] = ['Approved', Partnership::where('partner_id', $this->partnerId)->where('status', 'approved')->count() ?? 0];
        $data[] = ['Completed', Partnership::where('partner_id', $this->partnerId)->where('status', 'completed')->count() ?? 0];
        $data[] = [];

        return $data;
    }

    private function getJobsData(): array
    {
        $data = [];
        $jobs = JobPosting::where('partner_id', $this->partnerId)
            ->with('applications')
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($jobs as $job) {
            $applications = $job->applications;
            $totalApps = $applications->count();
            $approvalRate = $totalApps > 0
                ? round(($applications->where('status', 'approved')->count() / $totalApps) * 100, 1)
                : 0;

            $data[] = [
                $job->title,
                ucfirst($job->status),
                $job->is_featured ? 'Yes' : 'No',
                $totalApps,
                $applications->where('status', 'approved')->count(),
                $applications->where('status', 'pending')->count(),
                $applications->where('status', 'contacted')->count(),
                $applications->where('status', 'rejected')->count(),
                $approvalRate . '%',
                $job->created_at->format('M d, Y H:i'),
            ];
        }

        return $data;
    }

    private function getPartnershipsData(): array
    {
        $data = [];
        $partnerships = Partnership::where('partner_id', $this->partnerId)
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($partnerships as $partnership) {
            $data[] = [
                $partnership->activity_title,
                $partnership->organization_name,
                ucfirst(str_replace('_', ' ', $partnership->status)),
                $partnership->activity_date->format('M d, Y'),
                $partnership->contact_name,
                $partnership->created_at->format('M d, Y H:i'),
            ];
        }

        return $data;
    }

    private function getApplicationsData(): array
    {
        $data = [];
        $applications = JobApplication::whereHas('jobPosting', fn($q) =>
            $q->where('partner_id', $this->partnerId)
        )
            ->with(['applicant', 'jobPosting'])
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($applications as $app) {
            $data[] = [
                $app->applicant->name,
                $app->jobPosting->title,
                ucfirst(str_replace('_', ' ', $app->status)),
                $app->created_at->format('M d, Y H:i'),
                $app->reviewed_at?->format('M d, Y H:i') ?? 'Pending',
                $app->last_contacted_at?->format('M d, Y H:i') ?? 'Not contacted',
            ];
        }

        return $data;
    }
}

/**
 * Job Postings Excel Export
 */
class JobPostingsExport implements FromArray, WithHeadings, ShouldAutoSize
{
    private $partnerId;
    private $status;

    public function __construct($partnerId, $status = 'all')
    {
        $this->partnerId = $partnerId;
        $this->status = $status;
    }

    public function array(): array
    {
        $data = [];
        $query = JobPosting::where('partner_id', $this->partnerId)
            ->with('applications')
            ->orderBy('created_at', 'desc');

        if ($this->status !== 'all') {
            $query->where('status', $this->status);
        }

        $jobs = $query->get();

        foreach ($jobs as $job) {
            $applications = $job->applications;
            $totalApps = $applications->count();
            $approvalRate = $totalApps > 0
                ? round(($applications->where('status', 'approved')->count() / $totalApps) * 100, 1)
                : 0;

            $data[] = [
                $job->title,
                ucfirst($job->status),
                $job->is_featured ? 'Yes' : 'No',
                $totalApps,
                $applications->where('status', 'approved')->count(),
                $applications->where('status', 'pending')->count(),
                $applications->where('status', 'contacted')->count(),
                $applications->where('status', 'rejected')->count(),
                $approvalRate . '%',
                $job->created_at->format('M d, Y H:i'),
            ];
        }

        return $data;
    }

    public function headings(): array
    {
        return ['Job Title', 'Status', 'Featured', 'Applications', 'Approved', 'Pending', 'Contacted', 'Rejected', 'Approval Rate', 'Created At'];
    }
}

/**
 * Partnerships Excel Export
 */
class PartnershipsExport implements FromArray, WithHeadings, ShouldAutoSize
{
    private $partnerId;
    private $status;

    public function __construct($partnerId, $status = 'all')
    {
        $this->partnerId = $partnerId;
        $this->status = $status;
    }

    public function array(): array
    {
        $data = [];
        $query = Partnership::where('partner_id', $this->partnerId)
            ->orderBy('created_at', 'desc');

        if ($this->status !== 'all') {
            $query->where('status', $this->status);
        }

        $partnerships = $query->get();

        foreach ($partnerships as $partnership) {
            $data[] = [
                $partnership->activity_title,
                $partnership->organization_name,
                ucfirst(str_replace('_', ' ', $partnership->status)),
                $partnership->activity_date->format('M d, Y'),
                $partnership->contact_name,
                $partnership->created_at->format('M d, Y H:i'),
            ];
        }

        return $data;
    }

    public function headings(): array
    {
        return ['Activity Title', 'Organization', 'Status', 'Activity Date', 'Contact Person', 'Created At'];
    }
}

/**
 * Applications Excel Export
 */
class ApplicationsExport implements FromArray, WithHeadings, ShouldAutoSize
{
    private $partnerId;
    private $status;

    public function __construct($partnerId, $status = 'all')
    {
        $this->partnerId = $partnerId;
        $this->status = $status;
    }

    public function array(): array
    {
        $data = [];
        $query = JobApplication::whereHas('jobPosting', fn($q) =>
            $q->where('partner_id', $this->partnerId)
        )
            ->with(['applicant', 'jobPosting'])
            ->orderBy('created_at', 'desc');

        if ($this->status !== 'all') {
            $query->where('status', $this->status);
        }

        $applications = $query->get();

        foreach ($applications as $app) {
            $data[] = [
                $app->applicant->first_name . ' ' . $app->applicant->last_name,
                $app->jobPosting->title,
                ucfirst(str_replace('_', ' ', $app->status)),
                $app->created_at->format('M d, Y H:i'),
                $app->reviewed_at?->format('M d, Y H:i') ?? 'Pending',
                $app->last_contacted_at?->format('M d, Y H:i') ?? 'Not contacted',
            ];
        }

        return $data;
    }

    public function headings(): array
    {
        return ['Applicant Name', 'Job Title', 'Status', 'Applied Date', 'Reviewed Date', 'Last Contacted'];
    }
}
