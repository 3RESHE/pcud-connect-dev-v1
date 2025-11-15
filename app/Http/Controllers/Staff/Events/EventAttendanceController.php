<?php

namespace App\Http\Controllers\Staff\Events;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\ActivityLog;
use App\Models\User;
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
     * ✅ NEW: Add walk-in participant and mark attendance
     */
    public function storeWalkin(Event $event, Request $request)
    {
        $this->authorizeEventOwner($event);

        // Only ongoing events can accept walk-ins
        if ($event->status !== 'ongoing') {
            return redirect()->back()->with('error', 'Only ongoing events can accept walk-in registrations.');
        }

        $userType = $request->input('user_type');

        // Validate based on user type
        if ($userType === 'student') {
            $validated = $request->validate([
                'user_type' => 'required|in:student,alumni',
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'student_id' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'nullable|string|max:20',
                'year_level' => 'nullable|string|max:50',
                'notes' => 'nullable|string',
            ]);
        } else {
            $validated = $request->validate([
                'user_type' => 'required|in:student,alumni',
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'nullable|string|max:20',
                'graduation_year' => 'nullable|integer|min:1990|max:2099',
                'notes' => 'nullable|string',
            ]);
        }

        try {
            // Check if user already exists
            $user = User::where('email', $validated['email'])->first();

            if (!$user) {
                // Create new user for walk-in
                $user = User::create([
                    'first_name' => $validated['first_name'],
                    'last_name' => $validated['last_name'],
                    'email' => $validated['email'],
                    'password' => bcrypt('temporary_password_' . time()), // Temporary password
                    'role' => $userType === 'student' ? 'student' : 'alumni',
                    'is_active' => true,
                ]);

                // If student, add phone to profile if provided
                if ($userType === 'student' && $validated['phone']) {
                    $user->studentProfile()->updateOrCreate(
                        ['user_id' => $user->id],
                        ['phone' => $validated['phone']]
                    );
                }
                // If alumni, add phone to profile if provided
                elseif ($userType === 'alumni' && $validated['phone']) {
                    $user->alumniProfile()->updateOrCreate(
                        ['user_id' => $user->id],
                        ['phone' => $validated['phone']]
                    );
                }
            }

            // Check if registration already exists
            $existingRegistration = EventRegistration::where([
                'event_id' => $event->id,
                'user_id' => $user->id,
            ])->first();

            if ($existingRegistration) {
                // Update existing registration to attended
                if ($existingRegistration->attendance_status !== 'attended') {
                    $previousStatus = $existingRegistration->attendance_status;
                    $existingRegistration->update([
                        'attendance_status' => 'attended',
                        'checked_in_at' => now(),
                    ]);

                    // Log activity
                    ActivityLog::create([
                        'user_id' => auth()->id(),
                        'action' => 'updated',
                        'subject_type' => EventRegistration::class,
                        'subject_id' => $existingRegistration->id,
                        'description' => "Updated walk-in attendance from '{$previousStatus}' to 'attended' for {$user->first_name} {$user->last_name} - Event: {$event->title}",
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                    ]);
                }

                return redirect()->back()->with('success', 'Walk-in participant already registered. Attendance marked!');
            }

            // Create new registration for walk-in
            $registration = EventRegistration::create([
                'event_id' => $event->id,
                'user_id' => $user->id,
                'user_type' => $validated['user_type'],
                'registration_type' => 'walkin', // Mark as walk-in registration
                'attendance_status' => 'attended', // Automatically mark as attended
                'checked_in_at' => now(),
            ]);

            // ✅ LOG ACTIVITY
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

            // ✅ LOG ACTIVITY - Error
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'failed',
                'subject_type' => Event::class,
                'subject_id' => $event->id,
                'description' => "Failed to add walk-in registration for event: {$event->title}. Error: {$e->getMessage()}",
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()->back()->with('error', 'Failed to add walk-in participant. Please try again.');
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

        // ✅ LOG ACTIVITY
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

            // ✅ LOG ACTIVITY
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

        // ✅ LOG ACTIVITY
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
