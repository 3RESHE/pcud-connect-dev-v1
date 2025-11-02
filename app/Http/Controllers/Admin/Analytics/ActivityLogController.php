<?php

namespace App\Http\Controllers\Admin\Analytics;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of activity logs.
     */
    public function index(Request $request)
    {
        $logs = ActivityLog::with('user')
            ->when($request->action, function ($query, $action) {
                return $query->where('action', $action);
            })
            ->when($request->user, function ($query, $userId) {
                return $query->where('user_id', $userId);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Get stats for the view
        $mostActiveUser = ActivityLog::select('user_id', \DB::raw('count(*) as log_count'))
            ->groupBy('user_id')
            ->with('user')
            ->orderBy('log_count', 'desc')
            ->first()?->user?->first_name ?? 'Admin';

        $data = [
            'logs' => $logs,
            'total_logs' => ActivityLog::count(),
            'mostActiveUser' => $mostActiveUser,
            'actions_count' => ActivityLog::selectRaw('action, count(*) as count')
                ->groupBy('action')
                ->pluck('count', 'action'),
        ];

        return view('users.admin.activity-logs', $data);
    }

    /**
     * Export activity logs
     */
    public function export(Request $request)
    {
        // Blank implementation - will implement later
        return response()->json(['message' => 'Export not implemented yet'], 501);
    }
}
