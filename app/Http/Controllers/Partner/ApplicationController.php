<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Mail\ContactApplicantMailable;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ApplicationController extends Controller
{
    /**
     * View single application details with full profile information
     */
    public function show(JobApplication $application)
    {
        try {
            // ✅ Verify ownership
            $jobPosting = $application->jobPosting;

            if ($jobPosting->partner_id !== auth()->id()) {
                abort(403, 'Unauthorized to view this application');
            }

            // ✅ Load applicant details with all relationships
            $applicant = $application->applicant()->with('department')->first();

            // ✅ Get applicant profile based on type with all relationships
            $applicantProfile = null;
            $experiences = collect();
            $projects = collect();

            if ($application->applicant_type === 'student') {
                $applicantProfile = $applicant->studentProfile;

                // Load student experiences and projects if they exist
                if ($applicantProfile) {
                    $experiences = $applicant->experiences()
                        ->where('user_type', 'student')
                        ->orderBy('start_date', 'desc')
                        ->get();

                    $projects = $applicant->projects()
                        ->where('user_type', 'student')
                        ->orderBy('start_date', 'desc')
                        ->get();
                }
            } else {
                // Alumni
                $applicantProfile = $applicant->alumniProfile;

                // Load alumni experiences and projects if they exist
                if ($applicantProfile) {
                    $experiences = $applicant->experiences()
                        ->where('user_type', 'alumni')
                        ->orderBy('start_date', 'desc')
                        ->get();

                    $projects = $applicant->projects()
                        ->where('user_type', 'alumni')
                        ->orderBy('start_date', 'desc')
                        ->get();
                }
            }

            return view('users.partner.job-postings.application-show', [
                'application' => $application,
                'jobPosting' => $jobPosting,
                'applicant' => $applicant,
                'applicantProfile' => $applicantProfile,
                'experiences' => $experiences,
                'projects' => $projects,
            ]);
        } catch (\Exception $e) {
            \Log::error('Application show error: ' . $e->getMessage());
            return back()->with('error', 'Failed to load application: ' . $e->getMessage());
        }
    }

    /**
     * Approve application
     */
    public function approve(Request $request, JobApplication $application)
    {
        try {
            // ✅ Authorization check
            if ($application->jobPosting->partner_id !== auth()->id()) {
                abort(403);
            }

            // ✅ Update status
            $application->update([
                'status' => 'approved',
                'reviewed_at' => now(),
            ]);

            // ✅ Log activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'application_approved',
                'description' => "Approved application from {$application->applicant->name} for {$application->jobPosting->title}",
                'subject_type' => JobApplication::class,
                'subject_id' => $application->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Application approved successfully',
                'status' => 'approved',
            ]);
        } catch (\Exception $e) {
            \Log::error('Approve error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve application: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Reject application
     */
    public function reject(Request $request, JobApplication $application)
    {
        try {
            // ✅ Validation
            $request->validate([
                'rejection_reason' => 'nullable|string|max:500',
            ]);

            // ✅ Authorization
            if ($application->jobPosting->partner_id !== auth()->id()) {
                abort(403);
            }

            // ✅ Update application
            $application->update([
                'status' => 'rejected',
                'reviewed_at' => now(),
                'rejection_reason' => $request->rejection_reason,
            ]);

            // ✅ Log activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'application_rejected',
                'description' => "Rejected application from {$application->applicant->name} for {$application->jobPosting->title}",
                'subject_type' => JobApplication::class,
                'subject_id' => $application->id,
                'properties' => ['reason' => $request->rejection_reason],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Application rejected successfully',
                'status' => 'rejected',
            ]);
        } catch (\Exception $e) {
            \Log::error('Reject error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject application: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Contact applicant via email
     */
    public function contact(Request $request, JobApplication $application)
    {
        try {
            // ✅ Validation
            $validated = $request->validate([
                'subject' => 'required|string|max:255',
                'message' => 'required|string|min:10|max:2000',
            ]);

            // ✅ Authorization
            if ($application->jobPosting->partner_id !== auth()->id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized action.',
                ], 403);
            }

            // ✅ Prepare email data
            $applicant = $application->applicant;
            $partner = auth()->user();
            $jobPosting = $application->jobPosting;

            \Log::info('Sending email', [
                'to' => $applicant->email,
                'subject' => $validated['subject'],
            ]);

            // ✅ Send email using simple approach
            \Mail::to($applicant->email)
                ->send(new ContactApplicantMailable(
                    $applicant,
                    $partner,
                    $jobPosting,
                    $validated['subject'],
                    $validated['message']
                ));

            \Log::info('Email sent successfully', ['email' => $applicant->email]);

            // ✅ Update status to "contacted"
            $application->update([
                'status' => 'contacted',
                'last_contacted_at' => now(),
            ]);

            // ✅ Log activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'application_contacted',
                'description' => "Contacted {$applicant->name} regarding application for {$jobPosting->title}",
                'subject_type' => JobApplication::class,
                'subject_id' => $application->id,
                'properties' => [
                    'subject' => $validated['subject'],
                    'email_sent_to' => $applicant->email,
                ],
            ]);

            return response()->json([
                'success' => true,
                'message' => '✓ Email sent successfully to ' . $applicant->email,
                'applicant_email' => $applicant->email,
                'status' => 'contacted',
            ]);
        } catch (\Exception $e) {
            \Log::error('Contact error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to send email: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Download applicant resume with proper path handling
     * Handles both profile resumes and application-uploaded resumes
     */
    public function downloadResume(JobApplication $application)
    {
        try {
            // ✅ Authorization check
            if ($application->jobPosting->partner_id !== auth()->id()) {
                abort(403, 'Unauthorized');
            }

            // ✅ Get resume path from application
            $resumePath = $application->resume_path;

            // ✅ Check if resume path exists
            if (!$resumePath) {
                return back()->with('error', 'Resume not found for this application');
            }

            // ✅ Check if file exists in storage
            if (!Storage::disk('public')->exists($resumePath)) {
                return back()->with('error', 'Resume file not found in storage');
            }

            // ✅ Get the full storage path
            $fullPath = storage_path('app/public/' . $resumePath);

            // ✅ Verify file exists before download
            if (!file_exists($fullPath)) {
                return back()->with('error', 'Resume file is missing from the server');
            }

            // ✅ Get applicant info for download filename
            $applicant = $application->applicant;
            $extension = pathinfo($resumePath, PATHINFO_EXTENSION);
            $filename = $this->sanitizeFileName($applicant->name) . '_resume_' . date('Y-m-d') . '.' . $extension;

            // ✅ Log download
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'resume_downloaded',
                'description' => "Downloaded resume from {$applicant->name} for job application",
                'subject_type' => JobApplication::class,
                'subject_id' => $application->id,
                'properties' => [
                    'applicant_name' => $applicant->name,
                    'applicant_email' => $applicant->email,
                    'file_path' => $resumePath,
                ],
            ]);

            // ✅ Return file download
            return response()->download($fullPath, $filename);
        } catch (\Exception $e) {
            \Log::error('Resume download error: ' . $e->getMessage(), [
                'application_id' => $application->id,
                'user_id' => auth()->id(),
            ]);
            return back()->with('error', 'Failed to download resume: ' . $e->getMessage());
        }
    }

    /**
     * Update application status via PATCH
     */
    public function updateStatus(Request $request, JobApplication $application)
    {
        try {
            // ✅ Validation
            $request->validate([
                'status' => 'required|in:pending,contacted,reviewed,approved,rejected',
            ]);

            // ✅ Authorization
            if ($application->jobPosting->partner_id !== auth()->id()) {
                abort(403);
            }

            $oldStatus = $application->status;
            $newStatus = $request->status;

            // ✅ Update status
            $application->update(['status' => $newStatus]);

            // ✅ Log activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'application_status_updated',
                'description' => "Updated application status from {$oldStatus} to {$newStatus}",
                'subject_type' => JobApplication::class,
                'subject_id' => $application->id,
                'properties' => [
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus,
                ],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully',
                'status' => $newStatus,
            ]);
        } catch (\Exception $e) {
            \Log::error('Status update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * ✅ HELPER: Sanitize filename for safe downloads
     */
    private function sanitizeFileName($filename)
    {
        // Remove special characters and replace spaces with underscores
        $filename = preg_replace('/[^A-Za-z0-9_\-]/', '', str_replace(' ', '_', $filename));
        return trim($filename, '_') ?: 'resume';
    }
}
