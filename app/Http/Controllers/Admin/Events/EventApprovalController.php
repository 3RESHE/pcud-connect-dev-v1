<?php

namespace App\Http\Controllers\Admin\Events;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class EventApprovalController extends Controller
{
    /**
     * Show pending events for approval
     */
    public function index()
    {
        $events = Event::where('status', 'pending')
            ->with('creator')
            ->paginate(20);

        $data = [
            'events' => $events,
            'total_count' => Event::count(),
            'pending_count' => Event::where('status', 'pending')->count(),
            'approved_count' => Event::where('status', 'approved')->count(),
            'published_count' => Event::where('status', 'published')->count(),
            'rejected_count' => Event::where('status', 'rejected')->count(),
            'completed_count' => Event::where('status', 'completed')->count(),
        ];

        return view('users.admin.approvals.events.index', $data);
    }

    /**
     * Approve event (API endpoint)
     */
    public function approve(Request $request, $id)
    {
        // Blank implementation - will implement later
        return response()->json(['message' => 'Event approval not implemented yet'], 501);
    }

    /**
     * Reject event (API endpoint)
     */
    public function reject(Request $request, $id)
    {
        // Blank implementation - will implement later
        return response()->json(['message' => 'Event rejection not implemented yet'], 501);
    }
}
