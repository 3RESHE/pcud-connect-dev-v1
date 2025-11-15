<?php

namespace App\Http\Controllers\Staff\Events;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\ActivityLog;
use App\Models\User;
use App\Models\Department;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class EventAttendanceController extends Controller
{
    /**
     * Display attendance marking interface for ongoing events
     */
    public function markAttendance(Event $event)
    {
        $this->authorizeEventOwner($event);

        // Only ongoing events can have attendance marked
        if ($event->status !== 'ongoing') {
            return redirect()->back()->with('error', 'Only ongoing events can have attendance marked.');
        }

        // Get registrations with user data
        $registrations = $event->registrations()
            ->with([
                'user:id,first_name,last_name,email',
                'user.studentProfile:id,user_id,student_id',
                'user.alumniProfile:id,user_id'
            ])
            ->latest('created_at')
            ->paginate(20);

        // Calculate attendance statistics
        $stats = [
            'total' => $event->registrations()->count(),
            'attended' => $event->registrations()->where('attendance_status', 'attended')->count(),
            'registered' => $event->registrations()->where('attendance_status', 'registered')->count(),
            'no_show' => $event->registrations()->where('attendance_status', 'no_show')->count(),
        ];

        return view('users.staff.events.attendance.mark', compact('event', 'registrations', 'stats'));
    }

    /**
     * ✅ Add walk-in participant and mark attendance
     * Simplified: Only name and email (student_id removed)
     * For students: Assigns default "N/A" department
     */
    public function storeWalkin(Event $event, Request $request)
    {
        $this->authorizeEventOwner($event);

        // Only ongoing events can accept walk-ins
        if ($event->status !== 'ongoing') {
            return redirect()->back()->with('error', 'Only ongoing events can accept walk-in registrations.');
        }

        $userType = $request->input('user_type');

        // Validate - SAME for both student and alumni (no student_id required)
        $validated = $request->validate([
            'user_type' => 'required|in:student,alumni',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
        ]);

        try {
            // ✅ For students: Get or create default "N/A" department
            $department = null;
            if ($userType === 'student') {
                $department = Department::firstOrCreate(
                    ['code' => 'N/A'],
                    ['title' => 'Not Assigned']
                );
            }

            // Create new user for walk-in
            $userData = [
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'email' => $validated['email'],
                'password' => bcrypt('temp_' . time()),
                'role' => $userType === 'student' ? 'student' : 'alumni',
                'is_active' => true,
            ];

            // ✅ Add department_id for students
            if ($userType === 'student' && $department) {
                $userData['department_id'] = $department->id;
            }

            $user = User::create($userData);

            // Create new registration for walk-in
            $registration = EventRegistration::create([
                'event_id' => $event->id,
                'user_id' => $user->id,
                'user_type' => $validated['user_type'],
                'registration_type' => 'walkin',
                'attendance_status' => 'attended',
                'checked_in_at' => now(),
            ]);

            // Log activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'created',
                'subject_type' => EventRegistration::class,
                'subject_id' => $registration->id,
                'description' => "Added walk-in {$validated['user_type']} registration for {$user->first_name} {$user->last_name} - Event: {$event->title}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()->back()->with('success', "Walk-in participant {$user->first_name} {$user->last_name} added and checked in successfully!");

        } catch (\Exception $e) {
            \Log::error('Walk-in registration error: ' . $e->getMessage());

            // Log activity - Error
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'failed',
                'subject_type' => Event::class,
                'subject_id' => $event->id,
                'description' => "Failed to add walk-in registration for event: {$event->title}. Error: {$e->getMessage()}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()->back()->withErrors(['error' => 'Failed to add walk-in participant: ' . $e->getMessage()]);
        }
    }

    /**
     * Update attendance status for a registration
     */
    public function updateAttendance(Event $event, EventRegistration $registration, Request $request)
    {
        $this->authorizeEventOwner($event);

        // Verify registration belongs to event
        if ($registration->event_id !== $event->id) {
            abort(404, 'Registration not found');
        }

        // Only ongoing events can have attendance updated
        if ($event->status !== 'ongoing') {
            return redirect()->back()->with('error', 'Only ongoing events can have attendance marked.');
        }

        $validated = $request->validate([
            'attendance_status' => 'required|in:registered,attended,no_show',
        ]);

        $previousStatus = $registration->attendance_status;
        $checkedInTime = null;

        // Set check-in time if marking as attended
        if ($validated['attendance_status'] === 'attended') {
            $checkedInTime = now();
        }

        $registration->update([
            'attendance_status' => $validated['attendance_status'],
            'checked_in_at' => $checkedInTime,
        ]);

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'updated',
            'subject_type' => EventRegistration::class,
            'subject_id' => $registration->id,
            'description' => "Updated attendance status from '{$previousStatus}' to '{$validated['attendance_status']}' for {$registration->user->first_name} {$registration->user->last_name} - Event: {$event->title}",
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->back()->with('success', 'Attendance updated successfully!');
    }

    /**
     * Bulk update attendance status
     */
    public function bulkUpdateAttendance(Event $event, Request $request)
    {
        $this->authorizeEventOwner($event);

        if ($event->status !== 'ongoing') {
            return redirect()->back()->with('error', 'Only ongoing events can have attendance marked.');
        }

        $validated = $request->validate([
            'registrations' => 'required|array',
            'registrations.*.id' => 'required|integer',
            'registrations.*.status' => 'required|in:registered,attended,no_show',
        ]);

        $updatedCount = 0;

        try {
            foreach ($validated['registrations'] as $data) {
                $registration = EventRegistration::find($data['id']);

                // Verify ownership
                if ($registration && $registration->event_id === $event->id) {
                    $registration->update([
                        'attendance_status' => $data['status'],
                        'checked_in_at' => $data['status'] === 'attended' ? now() : null,
                    ]);
                    $updatedCount++;
                }
            }

            // Log activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'updated',
                'subject_type' => Event::class,
                'subject_id' => $event->id,
                'description' => "Bulk updated attendance for {$updatedCount} registrations - Event: {$event->title}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()->back()->with('success', "Attendance updated for {$updatedCount} participant(s)!");
        } catch (\Exception $e) {
            \Log::error('Bulk attendance update error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update attendance. Please try again.');
        }
    }

    /**
     * View attendance records for completed events
     */
    public function viewAttendance(Event $event)
    {
        $this->authorizeEventOwner($event);

        // Can view attendance for completed or ongoing events
        if (!in_array($event->status, ['ongoing', 'completed'])) {
            return redirect()->back()->with('error', 'Attendance records are only available for ongoing or completed events.');
        }

        // Get registrations with attendance data
        $registrations = $event->registrations()
            ->with([
                'user:id,first_name,last_name,email',
                'user.studentProfile:id,user_id,student_id',
                'user.alumniProfile:id,user_id'
            ])
            ->latest('created_at')
            ->paginate(20);

        // Calculate comprehensive statistics
        $stats = [
            'total' => $event->registrations()->count(),
            'attended' => $event->registrations()->where('attendance_status', 'attended')->count(),
            'registered' => $event->registrations()->where('attendance_status', 'registered')->count(),
            'no_show' => $event->registrations()->where('attendance_status', 'no_show')->count(),
            'attendance_rate' => $event->registrations()->count() > 0
                ? round(($event->registrations()->where('attendance_status', 'attended')->count() / $event->registrations()->count()) * 100, 2)
                : 0,
        ];

        return view('users.staff.events.attendance.view', compact('event', 'registrations', 'stats'));
    }

    /**
     * Export attendance records to CSV
     */
    public function exportAttendance(Event $event)
    {
        $this->authorizeEventOwner($event);

        // Can export attendance for completed or ongoing events
        if (!in_array($event->status, ['ongoing', 'completed'])) {
            return redirect()->back()->with('error', 'Attendance records are only available for ongoing or completed events.');
        }

        $registrations = $event->registrations()
            ->with('user:id,first_name,last_name,email')
            ->latest('created_at')
            ->get();

        $fileName = 'attendance-' . $event->id . '-' . now()->format('Y-m-d-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename=' . $fileName,
        ];

        $callback = function () use ($event, $registrations) {
            $file = fopen('php://output', 'w');

            // Header info
            fputcsv($file, ['Event: ' . $event->title]);
            fputcsv($file, ['Date: ' . $event->event_date->format('F d, Y')]);
            fputcsv($file, ['Status: ' . ucfirst($event->status)]);
            fputcsv($file, []);

            // Attendance data header
            fputcsv($file, ['Name', 'Email', 'Type', 'Registration Type', 'Status', 'Check-In Time']);

            // Attendance records
            foreach ($registrations as $reg) {
                $userType = $reg->user_type === 'student'
                    ? ($reg->user->studentProfile ? $reg->user->studentProfile->student_id : 'N/A')
                    : 'Alumni';

                fputcsv($file, [
                    $reg->user->first_name . ' ' . $reg->user->last_name,
                    $reg->user->email,
                    ucfirst($reg->user_type),
                    ucfirst(str_replace('_', ' ', $reg->registration_type)),
                    ucfirst($reg->attendance_status),
                    $reg->checked_in_at ? $reg->checked_in_at->format('Y-m-d H:i:s') : '—',
                ]);
            }

            fclose($file);
        };

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'exported',
            'subject_type' => Event::class,
            'subject_id' => $event->id,
            'description' => "Exported attendance records for event: {$event->title} ({$registrations->count()} records)",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get attendance statistics (JSON API)
     */
    public function getStatistics(Event $event)
    {
        $this->authorizeEventOwner($event);

        if (!in_array($event->status, ['ongoing', 'completed'])) {
            return response()->json(['error' => 'Event must be ongoing or completed'], 403);
        }

        $total = $event->registrations()->count();
        $attended = $event->registrations()->where('attendance_status', 'attended')->count();
        $registered = $event->registrations()->where('attendance_status', 'registered')->count();
        $no_show = $event->registrations()->where('attendance_status', 'no_show')->count();

        return response()->json([
            'total' => $total,
            'attended' => $attended,
            'registered' => $registered,
            'no_show' => $no_show,
            'attendance_rate' => $total > 0 ? round(($attended / $total) * 100, 2) : 0,
        ]);
    }

    /**
     * Authorize event owner
     */
    private function authorizeEventOwner(Event $event)
    {
        if ($event->created_by !== auth()->id()) {
            abort(403, 'Unauthorized action');
        }
    }
}
