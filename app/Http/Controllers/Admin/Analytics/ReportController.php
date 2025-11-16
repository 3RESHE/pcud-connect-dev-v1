<?php

namespace App\Http\Controllers\Admin\Analytics;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\JobPosting;
use App\Models\Event;
use App\Models\NewsArticle;
use App\Models\Partnership;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ReportController extends Controller
{
    /**
     * Display reports dashboard
     */
    public function index(Request $request)
    {
        $dateRange = $request->query('range', 'week');

        $data = [
            // User Statistics
            'total_users' => User::count() ?? 0,
            'students_count' => User::where('role', 'student')->count() ?? 0,
            'alumni_count' => User::where('role', 'alumni')->count() ?? 0,
            'partners_count' => User::where('role', 'partner')->count() ?? 0,
            'staff_count' => User::where('role', 'staff')->count() ?? 0,
            'admins_count' => User::where('role', 'admin')->count() ?? 0,

            // Job Statistics
            'active_jobs' => JobPosting::where('status', 'approved')->count() ?? 0,
            'pending_jobs' => JobPosting::where('status', 'pending')->count() ?? 0,
            'rejected_jobs' => JobPosting::where('status', 'rejected')->count() ?? 0,
            'total_jobs' => JobPosting::count() ?? 0,
            'published_jobs' => JobPosting::where('status', 'approved')->count() ?? 0,
            'featured_jobs' => JobPosting::where('is_featured', true)->count() ?? 0,

            // Event Statistics
            'total_events' => Event::count() ?? 0,
            'published_events' => Event::where('status', 'published')->count() ?? 0,
            'pending_events' => Event::where('status', 'pending')->count() ?? 0,
            'completed_events' => Event::where('status', 'completed')->count() ?? 0,
            'upcoming_events' => Event::where('status', 'published')
                ->where('event_date', '>=', now()->toDateString())
                ->count() ?? 0,
            'registered_total' => $this->getTotalEventRegistrations(),

            // News Statistics
            'total_news' => NewsArticle::count() ?? 0,
            'published_news' => NewsArticle::where('status', 'published')->count() ?? 0,
            'pending_news' => NewsArticle::where('status', 'pending')->count() ?? 0,
            'featured_news' => NewsArticle::where('is_featured', true)->count() ?? 0,

            // Partnership Statistics
            'total_partnerships' => Partnership::count() ?? 0,
            'active_partnerships' => Partnership::whereIn('status', ['approved', 'discussion'])->count() ?? 0,
            'pending_partnerships' => Partnership::whereIn('status', ['submitted', 'under_review'])->count() ?? 0,
            'completed_partnerships' => Partnership::where('status', 'completed')->count() ?? 0,

            // Today's Activity
            'today_approvals' => ActivityLog::where('action', 'approved')
                ->whereDate('created_at', today())
                ->count() ?? 0,
            'today_registrations' => User::whereDate('created_at', today())->count() ?? 0,
            'today_jobs_posted' => JobPosting::whereDate('created_at', today())->count() ?? 0,
            'today_events_started' => Event::where('status', 'published')
                ->whereDate('event_date', today())
                ->count() ?? 0,

            // Activity Logs
            'total_logs' => ActivityLog::count() ?? 0,
            'mostActiveUser' => $this->getMostActiveUser(),

            // Date range info
            'dateRange' => $dateRange,
        ];

        return view('users.admin.reports', $data);
    }

    /**
     * Export comprehensive report to Excel
     */
    public function exportExcel(Request $request)
    {
        try {
            $reportType = $request->query('type', 'comprehensive');
            $fileName = "report-{$reportType}-" . now()->format('Y-m-d-His') . ".xlsx";

            return Excel::download(
                new ComprehensiveExcelExport($reportType),
                $fileName
            );
        } catch (\Exception $e) {
            \Log::error('Excel export failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to export report: ' . $e->getMessage());
        }
    }

    /**
     * Get user growth data for chart (Last 30 days)
     */
    public function getUserGrowth(Request $request)
    {
        try {
            $days = $request->query('days', 30);
            $labels = [];
            $data = [];
            $cumulativeCount = 0;

            for ($i = $days - 1; $i >= 0; $i--) {
                $date = now()->subDays($i)->toDateString();
                $labels[] = now()->subDays($i)->format('M d');

                $dayCount = User::whereDate('created_at', $date)->count() ?? 0;
                $cumulativeCount += $dayCount;
                $data[] = $cumulativeCount;
            }

            return response()->json([
                'success' => true,
                'labels' => $labels,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            \Log::error('User growth data error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load data'], 500);
        }
    }

    /**
     * Get approval statistics for chart
     */
    public function getApprovalStats(Request $request)
    {
        try {
            $stats = [
                'pending_jobs' => JobPosting::where('status', 'pending')->count() ?? 0,
                'pending_events' => Event::where('status', 'pending')->count() ?? 0,
                'pending_articles' => NewsArticle::where('status', 'pending')->count() ?? 0,
                'pending_partnerships' => Partnership::whereIn('status', ['submitted', 'under_review'])->count() ?? 0,
            ];

            return response()->json([
                'success' => true,
                'labels' => ['Jobs', 'Events', 'News', 'Partnerships'],
                'data' => [
                    $stats['pending_jobs'],
                    $stats['pending_events'],
                    $stats['pending_articles'],
                    $stats['pending_partnerships'],
                ],
                'backgroundColor' => [
                    'rgba(255, 107, 107, 0.5)',
                    'rgba(255, 165, 0, 0.5)',
                    'rgba(255, 206, 86, 0.5)',
                    'rgba(75, 192, 192, 0.5)',
                ],
                'borderColor' => [
                    'rgba(255, 107, 107, 1)',
                    'rgba(255, 165, 0, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                ],
            ]);
        } catch (\Exception $e) {
            \Log::error('Approval stats error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load statistics'], 500);
        }
    }

    /**
     * Get activity heatmap data (Last 7 days)
     */
    public function getActivityHeatmap(Request $request)
    {
        try {
            $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            $heatmapData = [];

            for ($day = 0; $day < 7; $day++) {
                $dateStart = now()->subDays(6 - $day)->startOfDay();
                $dateEnd = now()->subDays(6 - $day)->endOfDay();

                $count = ActivityLog::whereBetween('created_at', [$dateStart, $dateEnd])
                    ->count() ?? 0;

                $heatmapData[] = [
                    'day' => $days[now()->subDays(6 - $day)->dayOfWeek],
                    'count' => $count,
                ];
            }

            return response()->json([
                'success' => true,
                'labels' => array_column($heatmapData, 'day'),
                'data' => array_column($heatmapData, 'count'),
            ]);
        } catch (\Exception $e) {
            \Log::error('Activity heatmap error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load heatmap'], 500);
        }
    }

    /**
     * Get content distribution data for chart
     */
    public function getContentDistribution(Request $request)
    {
        try {
            $data = [
                'jobs' => JobPosting::count() ?? 0,
                'events' => Event::count() ?? 0,
                'news' => NewsArticle::count() ?? 0,
                'partnerships' => Partnership::count() ?? 0,
            ];

            return response()->json([
                'success' => true,
                'labels' => ['Job Postings', 'Events', 'News Articles', 'Partnerships'],
                'data' => array_values($data),
                'backgroundColor' => [
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(153, 102, 255, 0.6)',
                ],
                'borderColor' => [
                    'rgba(54, 162, 235, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(153, 102, 255, 1)',
                ],
            ]);
        } catch (\Exception $e) {
            \Log::error('Content distribution error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load data'], 500);
        }
    }

    /**
     * Get total event registrations
     */
    private function getTotalEventRegistrations()
    {
        try {
            if (class_exists('App\Models\EventRegistration')) {
                return \App\Models\EventRegistration::count() ?? 0;
            }
            return 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Get most active user
     */
    private function getMostActiveUser()
    {
        try {
            return ActivityLog::selectRaw('user_id, count(*) as log_count')
                ->groupBy('user_id')
                ->with('user')
                ->orderBy('log_count', 'desc')
                ->first()?->user?->first_name ?? 'Admin';
        } catch (\Exception $e) {
            return 'Admin';
        }
    }
}

/**
 * Excel Export Class for Comprehensive Reports
 */
class ComprehensiveExcelExport implements FromArray, WithHeadings, ShouldAutoSize
{
    private $reportType;

    public function __construct($reportType = 'comprehensive')
    {
        $this->reportType = $reportType;
    }

    public function array(): array
    {
        return match ($this->reportType) {
            'comprehensive' => $this->getComprehensiveData(),
            'users' => $this->getUsersData(),
            'jobs' => $this->getJobsData(),
            'events' => $this->getEventsData(),
            'news' => $this->getNewsData(),
            'partnerships' => $this->getPartnershipsData(),
            default => $this->getComprehensiveData(),
        };
    }

    public function headings(): array
    {
        return match ($this->reportType) {
            'users' => ['First Name', 'Last Name', 'Email', 'Role', 'Status', 'Created At'],
            'jobs' => ['Job Title', 'Company', 'Status', 'Featured', 'Posted By', 'Created At'],
            'events' => ['Event Title', 'Status', 'Format', 'Created By', 'Created At'],
            'news' => ['Article Title', 'Status', 'Featured', 'Created By', 'Views', 'Created At'],
            'partnerships' => ['Activity Title', 'Partner', 'Status', 'Created At'], // ✅ Removed dates
            default => ['Category', 'Count'],
        };
    }


    private function getComprehensiveData(): array
    {
        $data = [];

        // User Statistics
        $data[] = ['USER STATISTICS'];
        $data[] = ['Total Users', User::count() ?? 0];
        $data[] = ['Students', User::where('role', 'student')->count() ?? 0];
        $data[] = ['Alumni', User::where('role', 'alumni')->count() ?? 0];
        $data[] = ['Partners', User::where('role', 'partner')->count() ?? 0];
        $data[] = ['Staff', User::where('role', 'staff')->count() ?? 0];
        $data[] = ['Admins', User::where('role', 'admin')->count() ?? 0];
        $data[] = ['Active Users', User::where('is_active', true)->count() ?? 0];
        $data[] = [];

        // Job Statistics
        $data[] = ['JOB STATISTICS'];
        $data[] = ['Total Job Postings', JobPosting::count() ?? 0];
        $data[] = ['Approved Jobs', JobPosting::where('status', 'approved')->count() ?? 0];
        $data[] = ['Pending Approval', JobPosting::where('status', 'pending')->count() ?? 0];
        $data[] = ['Rejected Jobs', JobPosting::where('status', 'rejected')->count() ?? 0];
        $data[] = ['Featured Jobs', JobPosting::where('is_featured', true)->count() ?? 0];
        $data[] = [];

        // Event Statistics
        $data[] = ['EVENT STATISTICS'];
        $data[] = ['Total Events', Event::count() ?? 0];
        $data[] = ['Published Events', Event::where('status', 'published')->count() ?? 0];
        $data[] = ['Pending Approval', Event::where('status', 'pending')->count() ?? 0];
        $data[] = ['Completed Events', Event::where('status', 'completed')->count() ?? 0];
        $data[] = ['Upcoming Events', Event::where('status', 'published')->where('event_date', '>=', now()->toDateString())->count() ?? 0];
        $data[] = [];

        // News Statistics
        $data[] = ['NEWS STATISTICS'];
        $data[] = ['Total Articles', NewsArticle::count() ?? 0];
        $data[] = ['Published Articles', NewsArticle::where('status', 'published')->count() ?? 0];
        $data[] = ['Pending Approval', NewsArticle::where('status', 'pending')->count() ?? 0];
        $data[] = ['Featured Articles', NewsArticle::where('is_featured', true)->count() ?? 0];
        $data[] = [];

        // Partnership Statistics
        $data[] = ['PARTNERSHIP STATISTICS'];
        $data[] = ['Total Partnerships', Partnership::count() ?? 0];
        $data[] = ['Active Partnerships', Partnership::whereIn('status', ['approved', 'discussion'])->count() ?? 0];
        $data[] = ['Pending Review', Partnership::whereIn('status', ['submitted', 'under_review'])->count() ?? 0];
        $data[] = ['Completed', Partnership::where('status', 'completed')->count() ?? 0];
        $data[] = [];

        // Today's Activity
        $data[] = ['TODAY\'S ACTIVITY'];
        $data[] = ['Approvals', ActivityLog::where('action', 'approved')->whereDate('created_at', today())->count() ?? 0];
        $data[] = ['New Registrations', User::whereDate('created_at', today())->count() ?? 0];
        $data[] = ['New Jobs Posted', JobPosting::whereDate('created_at', today())->count() ?? 0];

        return $data;
    }

    private function getUsersData(): array
    {
        $data = [];
        $users = User::select('first_name', 'last_name', 'email', 'role', 'is_active', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($users as $user) {
            $data[] = [
                $user->first_name,
                $user->last_name,
                $user->email,
                ucfirst($user->role),
                $user->is_active ? 'Active' : 'Inactive',
                $user->created_at->format('M d, Y H:i'),
            ];
        }

        return $data;
    }

    private function getJobsData(): array
    {
        $data = [];
        $jobs = JobPosting::with('partner')
            ->select('title', 'status', 'is_featured', 'partner_id', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($jobs as $job) {
            $data[] = [
                $job->title,
                $job->partner?->partner_profiles?->first()?->company_name ?? 'N/A',
                ucfirst($job->status),
                $job->is_featured ? 'Yes' : 'No',
                $job->partner?->first_name . ' ' . $job->partner?->last_name,
                $job->created_at->format('M d, Y H:i'),
            ];
        }

        return $data;
    }

    private function getEventsData(): array
    {
        $data = [];
        $events = Event::with('creator')
            ->select('title', 'status', 'event_format', 'created_by', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($events as $event) {
            $data[] = [
                $event->title,
                ucfirst($event->status),
                ucfirst($event->event_format),
                $event->creator?->first_name . ' ' . $event->creator?->last_name,
                $event->created_at->format('M d, Y H:i'),
            ];
        }

        return $data;
    }

    private function getNewsData(): array
    {
        $data = [];
        $articles = NewsArticle::with('creator')
            ->select('title', 'status', 'is_featured', 'created_by', 'views_count', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($articles as $article) {
            $data[] = [
                $article->title,
                ucfirst($article->status),
                $article->is_featured ? 'Yes' : 'No',
                $article->creator?->first_name . ' ' . $article->creator?->last_name,
                $article->views_count ?? 0,
                $article->created_at->format('M d, Y H:i'),
            ];
        }

        return $data;
    }

    private function getPartnershipsData(): array
    {
        $data = [];
        $partnerships = Partnership::with('partner')
            ->select('activity_title', 'status', 'partner_id', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($partnerships as $partnership) {
            $data[] = [
                $partnership->activity_title,
                $partnership->partner?->first_name . ' ' . $partnership->partner?->last_name,
                ucfirst($partnership->status),
                $partnership->created_at->format('M d, Y H:i'),
            ];
        }

        return $data;
    }
}
