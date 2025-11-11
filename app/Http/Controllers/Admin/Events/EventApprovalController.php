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
     * Show event details
     */
    public function show($id)
    {
        $event = Event::findOrFail($id);

        return view('users.admin.approvals.events.show', compact('event'));
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
                'approved_by' => auth()->id(),
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
     * Change event status (publish, ongoing, completed)
     * Handles status transitions after approval
     */
    public function changeStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:published,ongoing,completed'
        ]);

        try {
            DB::beginTransaction();

            $event = Event::findOrFail($id);
            $newStatus = $request->input('status');

            // Validate status transitions
            $validTransitions = [
                'approved' => ['published'],
                'published' => ['ongoing'],
                'ongoing' => ['completed'],
            ];

            if (
                !isset($validTransitions[$event->status]) ||
                !in_array($newStatus, $validTransitions[$event->status])
            ) {
                return response()->json([
                    'success' => false,
                    'message' => "Cannot transition from '{$event->status}' to '{$newStatus}'"
                ], 400);
            }

            // Update event status
            $update = [
                'status' => $newStatus,
            ];

            // Add published_at timestamp when publishing
            if ($newStatus === 'published') {
                $update['published_at'] = now();
            }

            $event->update($update);

            // Log activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => $newStatus === 'published' ? 'published' : $newStatus,
                'description' => ucfirst($newStatus) . ' event: ' . $event->title,
                'model_type' => Event::class,
                'model_id' => $event->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Event status updated to ' . ucfirst($newStatus) . ' successfully!',
                'event' => $event
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Event status change failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to change event status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Unpublish a published event and reject it
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

            // Update event status to REJECTED
            $event->update([
                'status' => 'rejected',
                'rejection_reason' => $request->unpublish_reason,
                'approved_by' => auth()->id(),
            ]);

            // Log activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'unpublished',
                'description' => 'Unpublished and rejected event: ' . $event->title . ' - Reason: ' . $request->unpublish_reason,
                'model_type' => Event::class,
                'model_id' => $event->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Event unpublished and rejected successfully.',
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
     * Feature an event
     * Only published events can be featured
     */
    public function feature(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $event = Event::findOrFail($id);

            // Only published events can be featured
            if ($event->status !== 'published') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only published events can be featured.'
                ], 400);
            }

            // Check if already featured
            if ($event->is_featured) {
                return response()->json([
                    'success' => false,
                    'message' => 'This event is already featured.'
                ], 400);
            }

            // Feature the event
            $event->feature(auth()->id());

            // Log activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'featured',
                'description' => 'Featured event: ' . $event->title,
                'model_type' => Event::class,
                'model_id' => $event->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Event featured successfully!',
                'event' => $event
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Event feature failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to feature event: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Unfeature an event
     */
    public function unfeature(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $event = Event::findOrFail($id);

            // Check if event is featured
            if (!$event->is_featured) {
                return response()->json([
                    'success' => false,
                    'message' => 'This event is not featured.'
                ], 400);
            }

            // Unfeature the event
            $event->unfeature();

            // Log activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'unfeatured',
                'description' => 'Unfeatured event: ' . $event->title,
                'model_type' => Event::class,
                'model_id' => $event->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Event unfeatured successfully!',
                'event' => $event
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Event unfeature failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to unfeature event: ' . $e->getMessage()
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
