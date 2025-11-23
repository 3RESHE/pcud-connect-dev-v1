<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\NewsArticle;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StaffDashboardController extends Controller
{
    /**
     * Display the enhanced staff dashboard with comprehensive analytics
     */
    public function dashboard()
    {
        $user = auth()->user();
        $userId = $user->id;

        // ===== QUICK STATS OVERVIEW =====
        $quickStats = $this->getQuickStats($userId);

        // ===== NEWS STATISTICS (Detailed) =====
        $newsStats = $this->getNewsStatistics($userId);

        // ===== EVENTS STATISTICS (Detailed) =====
        $eventsStats = $this->getEventsStatistics($userId);

        // ===== PERFORMANCE INSIGHTS (30 Days) =====
        $performanceInsights = $this->getPerformanceInsights($userId);

        // ===== COMPARISON METRICS (This Month vs Last Month) =====
        $comparisonMetrics = $this->getComparisonMetrics($userId);

        // ===== CONTENT HEALTH METRICS =====
        $contentHealth = $this->getContentHealthMetrics($userId);

        // ===== ENGAGEMENT ANALYTICS =====
        $engagementAnalytics = $this->getEngagementAnalytics($userId);

        // ===== UPCOMING EVENTS (Next 7 days) =====
        $upcomingEvents = Event::where('created_by', $userId)
            ->whereIn('status', ['approved', 'published'])
            ->whereBetween('event_date', [now(), now()->addDays(7)])
            ->orderBy('event_date', 'asc')
            ->limit(5)
            ->get();

        // ===== ONGOING EVENTS WITH LIVE STATS =====
        $ongoingEvents = Event::where('created_by', $userId)
            ->where('status', 'ongoing')
            ->withCount([
                'registrations',
                'registrations as attended_count' => function ($query) {
                    $query->where('attendance_status', 'attended');
                },
                'registrations as registered_count' => function ($query) {
                    $query->where('attendance_status', 'registered');
                }
            ])
            ->with(['registrations' => function ($query) {
                $query->latest('created_at')->limit(5);
            }])
            ->get()
            ->map(function ($event) {
                $event->attendance_rate = $event->registrations_count > 0
                    ? round(($event->attended_count / $event->registrations_count) * 100, 1)
                    : 0;
                return $event;
            });

        // ===== PENDING ITEMS (Requires Action) =====
        $pendingNews = NewsArticle::where('created_by', $userId)
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        $pendingEvents = Event::where('created_by', $userId)
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        // ===== RECENTLY PUBLISHED (Last 5) =====
        $recentlyPublished = NewsArticle::where('created_by', $userId)
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->limit(5)
            ->get();

        // ===== RECENT ACTIVITY LOG (Last 10) =====
        $recentActivity = ActivityLog::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($log) {
                $log->icon = $this->getActivityIcon($log->action);
                $log->color = $this->getActivityColor($log->action);
                return $log;
            });

        // ===== RECENT ARTICLES (Last 5 for table) =====
        $recentArticles = NewsArticle::where('created_by', $userId)
            ->with('tags:id,name')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // ===== TREND DATA FOR CHARTS (Last 30 Days) =====
        $articlesTrend = $this->getArticlesTrendData($userId, 30);
        $eventsTrend = $this->getEventsTrendData($userId, 30);

        return view('users.staff.dashboard.index', compact(
            'user',
            'quickStats',
            'newsStats',
            'eventsStats',
            'performanceInsights',
            'comparisonMetrics',
            'contentHealth',
            'engagementAnalytics',
            'upcomingEvents',
            'ongoingEvents',
            'pendingNews',
            'pendingEvents',
            'recentlyPublished',
            'recentActivity',
            'recentArticles',
            'articlesTrend',
            'eventsTrend'
        ));
    }

    /**
     * Get quick overview statistics
     */
    private function getQuickStats($userId)
    {
        return [
            'total_content' => NewsArticle::where('created_by', $userId)->count()
                + Event::where('created_by', $userId)->count(),
            'total_published' => NewsArticle::where('created_by', $userId)->where('status', 'published')->count()
                + Event::where('created_by', $userId)->where('status', 'published')->count(),
            'total_pending' => NewsArticle::where('created_by', $userId)->where('status', 'pending')->count()
                + Event::where('created_by', $userId)->where('status', 'pending')->count(),
            'total_registrations' => EventRegistration::whereHas('event', function ($query) use ($userId) {
                $query->where('created_by', $userId);
            })->count(),
        ];
    }

    /**
     * Get detailed news statistics
     */
    private function getNewsStatistics($userId)
    {
        // Single query to get all counts
        $statusCounts = NewsArticle::where('created_by', $userId)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        return [
            'total_articles' => NewsArticle::where('created_by', $userId)->count(),
            'published_count' => $statusCounts->get('published', 0),
            'pending_count' => $statusCounts->get('pending', 0),
            'draft_count' => $statusCounts->get('draft', 0),
            'approved_count' => $statusCounts->get('approved', 0),
            'rejected_count' => $statusCounts->get('rejected', 0),
            'featured_count' => NewsArticle::where('created_by', $userId)->where('is_featured', true)->count(),
        ];
    }

    /**
     * Get detailed events statistics
     */
    private function getEventsStatistics($userId)
    {
        // Single query to get all counts
        $statusCounts = Event::where('created_by', $userId)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        return [
            'total_events' => Event::where('created_by', $userId)->count(),
            'published_count' => $statusCounts->get('published', 0),
            'pending_count' => $statusCounts->get('pending', 0),
            'draft_count' => $statusCounts->get('draft', 0),
            'approved_count' => $statusCounts->get('approved', 0),
            'rejected_count' => $statusCounts->get('rejected', 0),
            'ongoing_count' => $statusCounts->get('ongoing', 0),
            'completed_count' => $statusCounts->get('completed', 0),
            'featured_count' => Event::where('created_by', $userId)->where('is_featured', true)->count(),
        ];
    }

    /**
     * Get performance insights for last 30 days
     */
    private function getPerformanceInsights($userId)
    {
        $thirtyDaysAgo = Carbon::now()->subDays(30);

        // Articles published in last 30 days
        $articlesPublishedLast30Days = NewsArticle::where('created_by', $userId)
            ->where('status', 'published')
            ->where('published_at', '>=', $thirtyDaysAgo)
            ->count();

        // Events published in last 30 days
        $eventsPublishedLast30Days = Event::where('created_by', $userId)
            ->whereIn('status', ['published', 'ongoing', 'completed'])
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->count();

        // Total registrations for events in last 30 days
        $registrationsLast30Days = EventRegistration::whereHas('event', function ($query) use ($userId) {
                $query->where('created_by', $userId);
            })
            ->where('created_at', '>=', $thirtyDaysAgo)
            ->count();

        // Average time from creation to approval (using activity logs)
        // Calculate based on activity logs where action is 'approved'
        $avgApprovalTime = ActivityLog::where('activity_logs.user_id', $userId)
            ->where('activity_logs.action', 'approved')
            ->where('activity_logs.subject_type', 'App\\Models\\NewsArticle')
            ->where('activity_logs.created_at', '>=', $thirtyDaysAgo)
            ->join('news_articles', function ($join) use ($userId) {
                $join->on('activity_logs.subject_id', '=', 'news_articles.id')
                     ->where('news_articles.created_by', '=', $userId);
            })
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, news_articles.created_at, activity_logs.created_at)) as avg_hours')
            ->value('avg_hours');

        return [
            'articles_published_30d' => $articlesPublishedLast30Days,
            'events_published_30d' => $eventsPublishedLast30Days,
            'registrations_30d' => $registrationsLast30Days,
            'avg_approval_hours' => $avgApprovalTime ? round($avgApprovalTime, 1) : 0,
        ];
    }

    /**
     * Get comparison metrics (This Month vs Last Month)
     */
    private function getComparisonMetrics($userId)
    {
        $thisMonthStart = Carbon::now()->startOfMonth();
        $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        // Articles This Month vs Last Month
        $articlesThisMonth = NewsArticle::where('created_by', $userId)
            ->where('status', 'published')
            ->where('published_at', '>=', $thisMonthStart)
            ->count();

        $articlesLastMonth = NewsArticle::where('created_by', $userId)
            ->where('status', 'published')
            ->whereBetween('published_at', [$lastMonthStart, $lastMonthEnd])
            ->count();

        // Events This Month vs Last Month
        $eventsThisMonth = Event::where('created_by', $userId)
            ->whereIn('status', ['published', 'ongoing', 'completed'])
            ->where('created_at', '>=', $thisMonthStart)
            ->count();

        $eventsLastMonth = Event::where('created_by', $userId)
            ->whereIn('status', ['published', 'ongoing', 'completed'])
            ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
            ->count();

        // Registrations This Month vs Last Month
        $registrationsThisMonth = EventRegistration::whereHas('event', function ($query) use ($userId) {
                $query->where('created_by', $userId);
            })
            ->where('created_at', '>=', $thisMonthStart)
            ->count();

        $registrationsLastMonth = EventRegistration::whereHas('event', function ($query) use ($userId) {
                $query->where('created_by', $userId);
            })
            ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
            ->count();

        return [
            'articles_this_month' => $articlesThisMonth,
            'articles_last_month' => $articlesLastMonth,
            'articles_change' => $this->calculatePercentageChange($articlesLastMonth, $articlesThisMonth),
            'events_this_month' => $eventsThisMonth,
            'events_last_month' => $eventsLastMonth,
            'events_change' => $this->calculatePercentageChange($eventsLastMonth, $eventsThisMonth),
            'registrations_this_month' => $registrationsThisMonth,
            'registrations_last_month' => $registrationsLastMonth,
            'registrations_change' => $this->calculatePercentageChange($registrationsLastMonth, $registrationsThisMonth),
        ];
    }

    /**
     * Calculate percentage change between two values
     */
    private function calculatePercentageChange($oldValue, $newValue)
    {
        if ($oldValue == 0) {
            return $newValue > 0 ? 100 : 0;
        }
        return round((($newValue - $oldValue) / $oldValue) * 100, 1);
    }

    /**
     * Get content health metrics
     */
    private function getContentHealthMetrics($userId)
    {
        $totalArticles = NewsArticle::where('created_by', $userId)->count();
        $publishedArticles = NewsArticle::where('created_by', $userId)->where('status', 'published')->count();
        $rejectedArticles = NewsArticle::where('created_by', $userId)->where('status', 'rejected')->count();

        $publishRate = $totalArticles > 0 ? round(($publishedArticles / $totalArticles) * 100, 1) : 0;
        $rejectionRate = $totalArticles > 0 ? round(($rejectedArticles / $totalArticles) * 100, 1) : 0;

        return [
            'publish_rate' => $publishRate,
            'rejection_rate' => $rejectionRate,
            'health_score' => max(0, 100 - ($rejectionRate * 2)), // Penalize rejections more
        ];
    }

    /**
     * Get engagement analytics
     */
    private function getEngagementAnalytics($userId)
    {
        // Get events with registrations
        $eventsWithRegistrations = Event::where('created_by', $userId)
            ->whereIn('status', ['published', 'ongoing', 'completed'])
            ->withCount('registrations')
            ->get();

        $totalEvents = $eventsWithRegistrations->count();
        $totalRegistrations = $eventsWithRegistrations->sum('registrations_count');
        $avgRegistrationsPerEvent = $totalEvents > 0 ? round($totalRegistrations / $totalEvents, 1) : 0;

        // Get attendance rate for completed events
        $completedEvents = Event::where('created_by', $userId)
            ->where('status', 'completed')
            ->withCount([
                'registrations',
                'registrations as attended_count' => function ($query) {
                    $query->where('attendance_status', 'attended');
                }
            ])
            ->get();

        $totalCompletedRegistrations = $completedEvents->sum('registrations_count');
        $totalAttended = $completedEvents->sum('attended_count');
        $overallAttendanceRate = $totalCompletedRegistrations > 0
            ? round(($totalAttended / $totalCompletedRegistrations) * 100, 1)
            : 0;

        return [
            'avg_registrations_per_event' => $avgRegistrationsPerEvent,
            'overall_attendance_rate' => $overallAttendanceRate,
            'total_registrations' => $totalRegistrations,
            'total_attended' => $totalAttended,
        ];
    }

    /**
     * Get articles trend data for charts (last N days)
     */
    private function getArticlesTrendData($userId, $days = 30)
    {
        $startDate = Carbon::now()->subDays($days)->startOfDay();

        $data = NewsArticle::where('created_by', $userId)
            ->where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Fill in missing dates with 0
        $filledData = [];
        for ($i = $days; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $count = $data->firstWhere('date', $date)?->count ?? 0;
            $filledData[] = [
                'date' => $date,
                'count' => $count
            ];
        }

        return $filledData;
    }

    /**
     * Get events trend data for charts (last N days)
     */
    private function getEventsTrendData($userId, $days = 30)
    {
        $startDate = Carbon::now()->subDays($days)->startOfDay();

        $data = Event::where('created_by', $userId)
            ->where('created_at', '>=', $startDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Fill in missing dates with 0
        $filledData = [];
        for ($i = $days; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $count = $data->firstWhere('date', $date)?->count ?? 0;
            $filledData[] = [
                'date' => $date,
                'count' => $count
            ];
        }

        return $filledData;
    }

    /**
     * Get activity icon based on action
     */
    private function getActivityIcon($action)
    {
        return match($action) {
            'created' => '➕',
            'updated' => '✏️',
            'deleted' => '🗑️',
            'published' => '📢',
            'approved' => '✅',
            'rejected' => '❌',
            'exported' => '📥',
            default => '📌',
        };
    }

    /**
     * Get activity color based on action
     */
    private function getActivityColor($action)
    {
        return match($action) {
            'created' => 'blue',
            'updated' => 'yellow',
            'deleted' => 'red',
            'published' => 'green',
            'approved' => 'green',
            'rejected' => 'red',
            'exported' => 'purple',
            default => 'gray',
        };
    }

    /**
     * AJAX: Get chart data for articles over time (for dynamic loading)
     */
    public function getArticlesChartData(Request $request)
    {
        $userId = auth()->id();
        $days = $request->get('days', 30);

        $data = $this->getArticlesTrendData($userId, $days);

        return response()->json($data);
    }

    /**
     * AJAX: Get chart data for events over time (for dynamic loading)
     */
    public function getEventsChartData(Request $request)
    {
        $userId = auth()->id();
        $days = $request->get('days', 30);

        $data = $this->getEventsTrendData($userId, $days);

        return response()->json($data);
    }

    /**
     * AJAX: Get event registrations timeline for specific event
     */
    public function getEventRegistrationsTimeline(Request $request)
    {
        $userId = auth()->id();
        $eventId = $request->get('event_id');

        if (!$eventId) {
            return response()->json(['error' => 'Event ID required'], 400);
        }

        $event = Event::where('id', $eventId)
            ->where('created_by', $userId)
            ->firstOrFail();

        $registrations = $event->registrations()
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        return response()->json($registrations);
    }
}
