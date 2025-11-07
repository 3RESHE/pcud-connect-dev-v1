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
                'action' => $log->getActionDisplay(),
                'description' => $log->description,
                'subject_type' => $log->getSubjectTypeDisplay(),
                'subject' => $log->getSubject()?->title ?? 'Deleted Record',
                'created_at' => $log->created_at->format('M d, Y h:i A'),
                'ip_address' => $log->ip_address,
                'browser' => $log->getBrowserInfo(),
                'old_values' => $log->getOldValues(),
                'new_values' => $log->getNewValues(),
                'changed_fields' => $log->getChangedFields(),
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
        fputcsv($csv, ['Date & Time', 'User', 'Action', 'Subject', 'Description', 'IP Address', 'Browser']);

        // Data
        foreach ($logs as $log) {
            fputcsv($csv, [
                $log->created_at->format('M d, Y h:i A'),
                $log->user?->first_name . ' ' . $log->user?->last_name,
                $log->getActionDisplay(),
                $log->getSubjectTypeDisplay(),
                $log->description,
                $log->ip_address,
                $log->getBrowserInfo(),
            ]);
        }

        fclose($csv);
        exit;
    }
}
