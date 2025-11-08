<?php

namespace App\Http\Controllers\Admin\Analytics;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    /**
     * Display activity logs
     */
    public function index(Request $request): View
    {
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
            'actions' => ['created', 'updated', 'deleted', 'approved', 'rejected', 'published'],
            'subject_types' => [
                'Event',
                'News',
                'JobPosting',      // ✅ NEW
                'User',
                'Partnership',
                'Application',     // ✅ NEW
            ],
        ]);
    }

    /**
     * Get log details (AJAX)
     */
    public function show($id)
    {
        $log = ActivityLog::with('user')->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $log->id,
                'user' => $log->user ? $log->user->first_name . ' ' . $log->user->last_name : 'Unknown',
                'action' => $this->getActionDisplay($log->action),
                'description' => $log->description,
                'subject_type' => $this->getSubjectTypeDisplay($log->subject_type),
                'subject' => $this->getSubjectDisplay($log),
                'created_at' => $log->created_at->format('M d, Y h:i A'),
                'ip_address' => $log->ip_address,
                'browser' => $this->getBrowserInfo($log),
                'old_values' => json_decode($log->old_values, true) ?? [],
                'new_values' => json_decode($log->new_values, true) ?? [],
                'changed_fields' => $this->getChangedFields($log),
            ],
        ]);
    }

    /**
     * Export logs
     */
    public function export(Request $request)
    {
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

        $logs = $query->get();

        $csv = fopen('php://output', 'w');
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="activity-logs-' . now()->format('Y-m-d') . '.csv"');

        // Headers
        fputcsv($csv, ['Date & Time', 'User', 'Action', 'Subject Type', 'Description', 'IP Address', 'Browser']);

        // Data
        foreach ($logs as $log) {
            fputcsv($csv, [
                $log->created_at->format('M d, Y h:i A'),
                ($log->user?->first_name ?? '') . ' ' . ($log->user?->last_name ?? ''),
                $this->getActionDisplay($log->action),
                $this->getSubjectTypeDisplay($log->subject_type),
                $log->description,
                $log->ip_address,
                $this->getBrowserInfo($log),
            ]);
        }

        fclose($csv);
        exit;
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
            default => ucfirst($action),
        };
    }

    /**
     * Helper: Get subject type display name
     */
    private function getSubjectTypeDisplay($subjectType): string
    {
        return match($subjectType) {
            'Event' => 'Event',
            'News' => 'News',
            'JobPosting' => 'Job Posting',
            'User' => 'User',
            'Partnership' => 'Partnership',
            'Application' => 'Application',
            default => $subjectType,
        };
    }

    /**
     * Helper: Get subject display (handle deleted records)
     */
    private function getSubjectDisplay($log): string
    {
        if ($log->subject_type === 'JobPosting') {
            $oldValues = json_decode($log->old_values, true);
            return $oldValues['title'] ?? 'Deleted Job Posting';
        }

        $subject = match($log->subject_type) {
            'Event' => \App\Models\Event::find($log->subject_id),
            'News' => \App\Models\News::find($log->subject_id),
            'JobPosting' => \App\Models\JobPosting::find($log->subject_id),
            'Partnership' => \App\Models\Partnership::find($log->subject_id),
            'Application' => \App\Models\Application::find($log->subject_id),
            default => null,
        };

        if ($subject && method_exists($subject, 'getDisplayName')) {
            return $subject->getDisplayName();
        }

        if ($subject && isset($subject->title)) {
            return $subject->title;
        }

        if ($subject && isset($subject->name)) {
            return $subject->name;
        }

        $oldValues = json_decode($log->old_values, true);
        return $oldValues['title'] ?? $oldValues['name'] ?? 'Deleted Record';
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
        } elseif (stripos($log->user_agent, 'Safari') !== false) {
            return 'Safari';
        } elseif (stripos($log->user_agent, 'Firefox') !== false) {
            return 'Firefox';
        } elseif (stripos($log->user_agent, 'Edge') !== false) {
            return 'Edge';
        }

        return 'Other';
    }

    /**
     * Helper: Get changed fields
     */
    private function getChangedFields($log): array
    {
        $oldValues = json_decode($log->old_values, true) ?? [];
        $newValues = json_decode($log->new_values, true) ?? [];

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
}
