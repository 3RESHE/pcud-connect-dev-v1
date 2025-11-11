<?php

namespace App\Http\Controllers\Admin\Events;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventApprovalController extends Controller
{
    /**
     * Display all events with filtering capability
     * Admin can see all events from all staff members
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'all');
        $search = $request->get('search');

        // Base query - get ALL events (not just pending)
        // ✅ FIX: Don't specify columns in with() - let it load all columns
        $query = Event::with('creator');

        // Filter by status
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Search functionality
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('venue_name', 'like', "%{$search}%")
                    ->orWhere('event_tags', 'like', "%{$search}%")
                    ->orWhereHas('creator', function ($q) use ($search) {
                        $q->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // Sort by latest
        $events = $query->latest('created_at')->paginate(15);

        // Get counts for all statuses
        $data = [
            'events' => $events,
            'current_status' => $status,
            'search_query' => $search,

            // Statistics
            'total_count' => Event::count(),
            'draft_count' => Event::where('status', 'draft')->count(),
            'pending_count' => Event::where('status', 'pending')->count(),
            'approved_count' => Event::where('status', 'approved')->count(),
            'published_count' => Event::where('status', 'published')->count(),
            'ongoing_count' => Event::where('status', 'ongoing')->count(),
            'completed_count' => Event::where('status', 'completed')->count(),
            'rejected_count' => Event::where('status', 'rejected')->count(),
        ];

        return view('users.admin.approvals.events.index', $data);
    }


    /**
     * Show detailed view of a specific event
     * For admin to review before approval/rejection
     */
    public function show($id)
    {
        $event = Event::with(['creator', 'registrations.user'])->findOrFail($id);

        $data = [
            'event' => $event,
            'total_registrations' => $event->registrations()->count(),
            'confirmed_registrations' => $event->registrations()->where('status', 'confirmed')->count(),
            'pending_registrations' => $event->registrations()->where('status', 'pending')->count(),
            'cancelled_registrations' => $event->registrations()->where('status', 'cancelled')->count(),
        ];

        return view('users.admin.approvals.events.show', $data);
    }

    /**
     * Approve an event
     * Changes status from 'pending' to 'approved'
     */
    public function approve(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $event = Event::findOrFail($id);

            // Check if event is in pending status
            if ($event->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only pending events can be approved.'
                ], 400);
            }

            // Update event status
            $event->update([
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => auth()->id(),
            ]);

            // Log activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'approved',
                'description' => 'Approved event: ' . $event->title,
                'model_type' => Event::class,
                'model_id' => $event->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Event approved successfully!',
                'event' => $event
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Event approval failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to approve event: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject an event with reason
     * Changes status from 'pending' to 'rejected'
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|min:10|max:500'
        ]);

        try {
            DB::beginTransaction();

            $event = Event::findOrFail($id);

            // Check if event is in pending status
            if ($event->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only pending events can be rejected.'
                ], 400);
            }

            // Update event status
            $event->update([
                'status' => 'rejected',
                'rejection_reason' => $request->rejection_reason,
                'rejected_at' => now(),
                'rejected_by' => auth()->id(),
            ]);

            // Log activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'rejected',
                'description' => 'Rejected event: ' . $event->title . ' - Reason: ' . $request->rejection_reason,
                'model_type' => Event::class,
                'model_id' => $event->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Event rejected successfully.',
                'event' => $event
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Event rejection failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to reject event: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Unpublish a published event
     * Admin can unpublish events if needed
     */
    public function unpublish(Request $request, $id)
    {
        $request->validate([
            'unpublish_reason' => 'required|string|min:10|max:500'
        ]);

        try {
            DB::beginTransaction();

            $event = Event::findOrFail($id);

            // Check if event is published
            if ($event->status !== 'published') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only published events can be unpublished.'
                ], 400);
            }

            // Update event status back to approved
            $event->update([
                'status' => 'approved',
                'unpublish_reason' => $request->unpublish_reason,
            ]);

            // Log activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'unpublished',
                'description' => 'Unpublished event: ' . $event->title . ' - Reason: ' . $request->unpublish_reason,
                'model_type' => Event::class,
                'model_id' => $event->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Event unpublished successfully.',
                'event' => $event
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Event unpublish failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to unpublish event: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete an event (hard delete)
     * Admin can delete events permanently
     */
    public function destroy(Request $request, $id)
    {
        $request->validate([
            'delete_reason' => 'required|string|min:10|max:500'
        ]);

        try {
            DB::beginTransaction();

            $event = Event::findOrFail($id);

            // Only allow deletion of draft, rejected, or completed events
            if (!in_array($event->status, ['draft', 'rejected', 'completed'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only draft, rejected, or completed events can be deleted.'
                ], 400);
            }

            $eventTitle = $event->title;

            // Log activity before deletion
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'deleted',
                'description' => 'Deleted event: ' . $eventTitle . ' - Reason: ' . $request->delete_reason,
                'model_type' => Event::class,
                'model_id' => $event->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Delete event
            $event->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Event deleted successfully.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Event deletion failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete event: ' . $e->getMessage()
            ], 500);
        }
    }
}
