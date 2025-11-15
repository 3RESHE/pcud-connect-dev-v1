<?php

namespace App\Http\Controllers\Staff\Events;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\ActivityLog;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Mailable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Envelope;
use Illuminate\Mail\Content;
use Illuminate\Http\Request;


class EventRegistrationController extends Controller
{
    /**
     * Show all registrations for an event
     */
    public function index(Event $event)
    {
        $this->authorizeEventOwner($event);

        $registrations = $event->registrations()
            ->with([
                'user:id,first_name,last_name,email,role',
                'user.studentProfile:id,user_id,student_id',
                'user.alumniProfile:id,user_id'
            ])
            ->latest('created_at')
            ->paginate(20);

        return view('users.staff.events.registrations.index', compact('event', 'registrations'));
    }


    /**
     * Export registrations to CSV
     */
    public function export(Event $event)
    {
        $this->authorizeEventOwner($event);

        $registrations = $event->registrations()
            ->with('user:id,first_name,last_name,email')
            ->latest('created_at')
            ->get();

        $fileName = 'registrations-' . $event->id . '-' . now()->format('Y-m-d-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename=' . $fileName,
        ];

        $callback = function () use ($event, $registrations) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Event: ' . $event->title]);
            fputcsv($file, ['Date: ' . $event->event_date->format('F d, Y')]);
            fputcsv($file, []);

            fputcsv($file, ['Name', 'Email', 'Registered On']);

            foreach ($registrations as $reg) {
                fputcsv($file, [
                    $reg->user->first_name . ' ' . $reg->user->last_name,
                    $reg->user->email,
                    $reg->created_at->format('Y-m-d H:i'),
                ]);
            }

            fclose($file);
        };

        // ✅ LOG ACTIVITY - Export happens before stream response
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'updated',
            'subject_type' => Event::class,
            'subject_id' => $event->id,
            'description' => "Exported registrations for event: {$event->title}",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Show registration details
     */
    public function show(Event $event, EventRegistration $registration)
    {
        $this->authorizeEventOwner($event);

        if ($registration->event_id !== $event->id) {
            abort(404, 'Registration not found');
        }

        return view('users.staff.events.registrations.show', compact('event', 'registration'));
    }

    /**
     * Confirm a registration
     */
    public function confirm(Event $event, EventRegistration $registration)
    {
        $this->authorizeEventOwner($event);

        if ($registration->event_id !== $event->id) {
            abort(404, 'Registration not found');
        }

        $registration->update(['status' => 'confirmed']);

        // ✅ LOG ACTIVITY
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'updated',
            'subject_type' => EventRegistration::class,
            'subject_id' => $registration->id,
            'description' => "Confirmed registration for event: {$event->title} (User: {$registration->user->first_name} {$registration->user->last_name})",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->back()->with('success', 'Registration confirmed!');
    }

    /**
     * Cancel a registration
     */
    public function cancel(Event $event, EventRegistration $registration)
    {
        $this->authorizeEventOwner($event);

        if ($registration->event_id !== $event->id) {
            abort(404, 'Registration not found');
        }

        $registration->update(['status' => 'cancelled']);

        // ✅ LOG ACTIVITY
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'updated',
            'subject_type' => EventRegistration::class,
            'subject_id' => $registration->id,
            'description' => "Cancelled registration for event: {$event->title} (User: {$registration->user->first_name} {$registration->user->last_name})",
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->back()->with('success', 'Registration cancelled!');
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
    /**
     * Send email to multiple registrants
     */
    public function sendEmail(Event $event, Request $request)
    {
        $this->authorizeEventOwner($event);

        try {
            $request->validate([
                'recipients' => 'required|json',
                'subject' => 'required|string|max:255',
                'message' => 'required|string',
            ]);

            $recipients = json_decode($request->recipients, true);
            $subject = $request->input('subject');
            $message = $request->input('message');

            if (empty($recipients)) {
                return redirect()->back()->with('error', 'No recipients selected.');
            }

            // Send emails
            foreach ($recipients as $email) {
                try {
                    Mail::raw($message, function ($mail) use ($email, $subject) {
                        $mail->to($email)
                            ->subject($subject)
                            ->from(config('mail.from.address'), config('mail.from.name'));
                    });
                } catch (\Exception $e) {
                    \Log::error("Failed to send email to {$email}: " . $e->getMessage());
                }
            }

            // ✅ LOG ACTIVITY
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'created',
                'subject_type' => Event::class,
                'subject_id' => $event->id,
                'description' => "Sent email notification to " . count($recipients) . " participant(s) for event: {$event->title} (Subject: {$subject})",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            return redirect()->back()->with('success', 'Email sent successfully to ' . count($recipients) . ' participant(s).');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->with('error', 'Validation failed');
        } catch (\Exception $e) {
            \Log::error('Failed to send bulk email: ' . $e->getMessage());

            // ✅ LOG ACTIVITY - Error
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'failed',
                'subject_type' => Event::class,
                'subject_id' => $event->id,
                'description' => "Failed to send email for event: {$event->title}. Error: {$e->getMessage()}",
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            return redirect()->back()->with('error', 'Failed to send email. Please try again.');
        }
    }
}
