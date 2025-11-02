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

class ReportController extends Controller
{
    /**
     * Display reports dashboard
     */
    public function index(Request $request)
    {
        $dateRange = $request->query('range', 'week');

        // Calculate date filters
        $startDate = match($dateRange) {
            'today' => now()->startOfDay(),
            'week' => now()->startOfWeek(),
            'month' => now()->startOfMonth(),
            'quarter' => now()->startOfQuarter(),
            'year' => now()->startOfYear(),
            default => null,
        };

        // KPI Statistics
        $data = [
            // User Statistics
            'total_users' => User::count(),
            'students_count' => User::where('role', 'student')->count(),
            'alumni_count' => User::where('role', 'alumni')->count(),
            'partners_count' => User::where('role', 'partner')->count(),
            'staff_count' => User::where('role', 'staff')->count(),

            // Job Statistics
            'active_jobs' => JobPosting::where('status', 'published')->count(),
            'pending_jobs' => JobPosting::where('status', 'pending')->count(),
            'total_jobs' => JobPosting::count(),
            'published_jobs' => JobPosting::where('status', 'published')->count(),

            // Event Statistics
            'total_events' => Event::count(),
            'upcoming_events' => Event::where('status', 'published')
                ->where('event_date', '>=', now())
                ->count(),
            // Count total registrations via event_registrations table
            'registered_total' => $this->getTotalEventRegistrations(),

            // News Statistics
            'total_news' => NewsArticle::count(),
            'published_news' => NewsArticle::where('status', 'published')->count(),

            // Partnership Statistics
            'total_partnerships' => Partnership::count(),
            'active_partnerships' => Partnership::where('status', 'approved')->count(),

            // Today's Activity
            'today_approvals' => ActivityLog::where('action', 'approved')
                ->whereDate('created_at', today())
                ->count(),
            'today_registrations' => User::whereDate('created_at', today())->count(),
            'today_jobs_posted' => JobPosting::whereDate('created_at', today())->count(),
            'today_events_started' => Event::where('status', 'published')
                ->whereDate('event_date', today())
                ->count(),

            // Activity Logs
            'total_logs' => ActivityLog::count(),
            'mostActiveUser' => $this->getMostActiveUser(),
        ];

        return view('users.admin.reports', $data);
    }

    /**
     * Get total event registrations from event_registrations table
     */
    private function getTotalEventRegistrations()
    {
        try {
            // If you have an EventRegistration model
            if (class_exists('App\Models\EventRegistration')) {
                return \App\Models\EventRegistration::count();
            }

            // If registrations are stored in a pivot table
            // return DB::table('event_registrations')->count();

            // Fallback: return 0 if table doesn't exist
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

    /**
     * Generate and export report
     */
    public function export(Request $request)
    {
        // Blank implementation - will implement later
        return response()->json(['message' => 'Report export not implemented yet'], 501);
    }

    /**
     * Get user growth data for chart
     */
    public function getUserGrowth(Request $request)
    {
        // Blank implementation - will implement later
        return response()->json(['message' => 'User growth data not implemented yet'], 501);
    }

    /**
     * Get approval statistics
     */
    public function getApprovalStats(Request $request)
    {
        // Blank implementation - will implement later
        return response()->json(['message' => 'Approval stats not implemented yet'], 501);
    }

    /**
     * Get activity heatmap data
     */
    public function getActivityHeatmap(Request $request)
    {
        // Blank implementation - will implement later
        return response()->json(['message' => 'Activity heatmap not implemented yet'], 501);
    }
}
