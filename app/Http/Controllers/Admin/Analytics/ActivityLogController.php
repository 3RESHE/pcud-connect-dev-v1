<?php

namespace App\Http\Controllers\Admin\Analytics;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\Event;
use App\Models\News;
use App\Models\Partnership;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    /**
     * Display activity logs
     */
    public function index(Request $request): View
    {
        try {
            $query = ActivityLog::with('user')
                ->orderBy('created_at', 'desc');

            // Filter by action
            if ($request->filled('action')) {
                $query->where('action', $request->action);
            }

            // Filter by date range
            if ($request->filled('start_date')) {
                $query->whereDate('created_at', '>=', $request->start_date);
            }

            if ($request->filled('end_date')) {
                $query->whereDate('created_at', '<=', $request->end_date);
            }

            // Filter by subject type
            if ($request->filled('subject_type')) {
                $query->where('subject_type', $request->subject_type);
            }

            $logs = $query->paginate(25);

            return view('users.admin.analytics.activity-logs.index', [
                'logs' => $logs,
                'actions' => [
                    'created' => 'Created',
                    'updated' => 'Updated',
                    'deleted' => 'Deleted',
                    'approved' => 'Approved',
                    'rejected' => 'Rejected',
                    'published' => 'Published',
                    'archived' => 'Archived',
                    'restored' => 'Restored',
                    'closed' => 'Closed',
                    'applied' => 'Applied',  // ✅ ADDED
                ],
                'subject_types' => [
                    'Event',
                    'News',
                    'JobPosting',
                    'JobApplication',  // ✅ ADDED
                    'User',
                    'Partnership',
                ],
            ]);

        } catch (\Exception $e) {
            \Log::error('Activity logs index error: ' . $e->getMessage());
            abort(500, 'Failed to load activity logs');
        }
    }

    /**
     * Get log details (AJAX)
     */
    public function show($id)
    {
        try {
            $log = ActivityLog::with('user')->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $log->id,
                    'user' => $log->user ? $log->user->first_name . ' ' . $log->user->last_name : 'Unknown User',
                    'action' => $this->getActionDisplay($log->action),
                    'description' => $log->description ?? 'No description provided',
                    'subject_type' => $this->getSubjectTypeDisplay($log->subject_type),
                    'subject' => $this->getSubjectDisplay($log),
                    'created_at' => $log->created_at->format('M d, Y h:i A'),
                    'created_at_relative' => $log->created_at->diffForHumans(),
                    'ip_address' => $log->ip_address ?? 'N/A',
                    'browser' => $this->getBrowserInfo($log),
                    'properties' => json_decode($log->properties ?? '[]', true),
                ],
            ]);

        } catch (\Exception $e) {
            \Log::error('Activity log show error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load log details',
            ], 500);
        }
    }

    /**
     * Export logs to CSV
     */
    public function export(Request $request)
    {
        try {
            $query = ActivityLog::with('user')
                ->orderBy('created_at', 'desc');

            if ($request->filled('action')) {
                $query->where('action', $request->action);
            }

            if ($request->filled('start_date')) {
                $query->whereDate('created_at', '>=', $request->start_date);
            }

            if ($request->filled('end_date')) {
                $query->whereDate('created_at', '<=', $request->end_date);
            }

            if ($request->filled('subject_type')) {
                $query->where('subject_type', $request->subject_type);
            }

            $logs = $query->get();

            $csv = fopen('php://output', 'w');
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="activity-logs-' . now()->format('Y-m-d-His') . '.csv"');

            // CSV Headers
            fputcsv($csv, [
                'Date & Time',
                'User',
                'Action',
                'Subject Type',
                'Description',
                'Subject Name',
                'IP Address',
                'Browser',
            ]);

            // CSV Data
            foreach ($logs as $log) {
                fputcsv($csv, [
                    $log->created_at->format('M d, Y h:i A'),
                    ($log->user?->first_name ?? 'Unknown') . ' ' . ($log->user?->last_name ?? 'User'),
                    $this->getActionDisplay($log->action),
                    $this->getSubjectTypeDisplay($log->subject_type),
                    $log->description ?? 'N/A',
                    $this->getSubjectDisplay($log),
                    $log->ip_address ?? 'N/A',
                    $this->getBrowserInfo($log),
                ]);
            }

            fclose($csv);
            exit;

        } catch (\Exception $e) {
            \Log::error('Activity log export error: ' . $e->getMessage());
            abort(500, 'Failed to export logs');
        }
    }

    /**
     * Delete logs older than specified days
     */
    public function clearOldLogs(Request $request)
    {
        try {
            $validated = $request->validate([
                'days' => 'required|integer|min:1|max:365',
            ]);

            $deleted = ActivityLog::where('created_at', '<', now()->subDays($validated['days']))
                ->delete();

            return redirect()->back()
                ->with('success', "Deleted {$deleted} activity logs older than {$validated['days']} days");

        } catch (\Exception $e) {
            \Log::error('Clear old logs error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to clear old logs');
        }
    }

    /**
     * Helper: Get action display name
     */
    private function getActionDisplay($action): string
    {
        return match($action) {
            'created' => 'Created',
            'updated' => 'Updated',
            'deleted' => 'Deleted',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'published' => 'Published',
            'paused' => 'Paused',
            'resumed' => 'Resumed',
            'closed' => 'Closed',
            'archived' => 'Archived',
            'restored' => 'Restored',
            'completed' => 'Completed',
            'checked_in' => 'Checked In',
            'applied' => 'Applied',  // ✅ ADDED
            default => ucfirst($action),
        };
    }

    /**
     * Helper: Get subject type display name
     */
    private function getSubjectTypeDisplay($subjectType): string
    {
        $basename = class_basename($subjectType ?? '');

        return match($basename) {
            'JobPosting' => 'Job Posting',
            'JobApplication' => 'Job Application',  // ✅ ADDED
            'Event' => 'Event',
            'EventRegistration' => 'Event Registration',
            'News' => 'News',
            'NewsArticle' => 'News Article',
            'Partnership' => 'Partnership',
            'User' => 'User',
            'Application' => 'Application',
            default => $basename ?: 'Unknown',
        };
    }

    /**
     * Helper: Get subject display (handle deleted records)
     */
    private function getSubjectDisplay($log): string
    {
        // Try to get the actual subject
        $subject = $this->getSubject($log);

        if ($subject) {
            if (isset($subject->title)) {
                return $subject->title;
            }
            if (isset($subject->name)) {
                return $subject->name;
            }
            if (method_exists($subject, 'getDisplayName')) {
                return $subject->getDisplayName();
            }
        }

        // Fallback for deleted records or missing data
        $properties = json_decode($log->properties ?? '{}', true);

        if (isset($properties['job_title'])) {
            return $properties['job_title'];
        }

        if (is_array($properties) && isset($properties[0]['title'])) {
            return $properties[0]['title'];
        }

        // Generic fallback
        return match($log->subject_type) {
            'App\Models\JobApplication' => 'Job Application #' . $log->subject_id,
            'App\Models\JobPosting' => 'Job Posting #' . $log->subject_id,
            'App\Models\Event' => 'Event #' . $log->subject_id,
            'App\Models\News' => 'News #' . $log->subject_id,
            'App\Models\User' => 'User #' . $log->subject_id,
            'App\Models\Partnership' => 'Partnership #' . $log->subject_id,
            default => 'Record #' . $log->subject_id,
        };
    }

    /**
     * Helper: Get subject (polymorphic)
     */
    private function getSubject($log)
    {
        if (!$log->subject_type || !$log->subject_id) {
            return null;
        }

        try {
            $modelClass = $log->subject_type;

            if (class_exists($modelClass)) {
                return $modelClass::find($log->subject_id);
            }
        } catch (\Exception $e) {
            \Log::warning('Failed to get subject: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Helper: Get browser info
     */
    private function getBrowserInfo($log): string
    {
        if (!$log->user_agent) {
            return 'Unknown';
        }

        if (stripos($log->user_agent, 'Chrome') !== false) {
            return 'Chrome';
        } elseif (stripos($log->user_agent, 'Firefox') !== false) {
            return 'Firefox';
        } elseif (stripos($log->user_agent, 'Safari') !== false) {
            return 'Safari';
        } elseif (stripos($log->user_agent, 'Edge') !== false) {
            return 'Edge';
        } elseif (stripos($log->user_agent, 'Opera') !== false) {
            return 'Opera';
        }

        return 'Other';
    }

    /**
     * Helper: Get changed fields
     */
    private function getChangedFields($log): array
    {
        $properties = json_decode($log->properties ?? '{}', true);
        $oldValues = $properties['old'] ?? [];
        $newValues = $properties['new'] ?? [];

        $changed = [];

        foreach ($newValues as $key => $newValue) {
            $oldValue = $oldValues[$key] ?? null;
            if ($oldValue !== $newValue) {
                $changed[] = [
                    'field' => $this->formatFieldName($key),
                    'old' => $oldValue,
                    'new' => $newValue,
                ];
            }
        }

        return $changed;
    }

    /**
     * Helper: Format field name for display
     */
    private function formatFieldName($field): string
    {
        return ucwords(str_replace('_', ' ', $field));
    }

    /**
     * Get action color for badges
     */
    private function getActionColor($action): string
    {
        return match($action) {
            'created' => 'success',
            'updated' => 'info',
            'deleted' => 'danger',
            'approved' => 'success',
            'rejected' => 'danger',
            'published' => 'success',
            'archived' => 'warning',
            'restored' => 'info',
            'completed' => 'success',
            'checked_in' => 'success',
            'applied' => 'indigo',  // ✅ ADDED
            'paused' => 'warning',
            'resumed' => 'success',
            'closed' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Get logs count by action
     */
    public function getStatistics()
    {
        try {
            $stats = [
                'total_logs' => ActivityLog::count(),
                'today_logs' => ActivityLog::whereDate('created_at', now()->toDateString())->count(),
                'week_logs' => ActivityLog::where('created_at', '>=', now()->subWeek())->count(),
                'by_action' => ActivityLog::selectRaw('action, COUNT(*) as count')
                    ->groupBy('action')
                    ->get()
                    ->pluck('count', 'action'),
            ];

            return response()->json($stats);

        } catch (\Exception $e) {
            \Log::error('Statistics error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load statistics'], 500);
        }
    }
}
