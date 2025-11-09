<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    /**
     * Show application details (AJAX)
     */
    public function show(JobApplication $application)
    {
        // Verify ownership
        if ($application->jobPosting->partner_id !== auth()->id()) {
            abort(403);
        }

        $html = view('components.application-details', ['application' => $application])->render();

        return response()->json(['html' => $html]);
    }

    /**
     * Approve application
     */
    public function approve(JobApplication $application)
    {
        if ($application->jobPosting->partner_id !== auth()->id()) {
            abort(403);
        }

        $application->update(['status' => 'approved']);

        return redirect()->back()->with('success', 'Application approved successfully!');
    }

    /**
     * Reject application
     */
    public function reject(Request $request, JobApplication $application)
    {
        if ($application->jobPosting->partner_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'rejection_reason' => 'nullable|string|max:500',
        ]);

        $application->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        return redirect()->back()->with('success', 'Application rejected successfully!');
    }


    /**
     * Get application details as JSON (for AJAX modal)
     */
    public function getApplicationDetails($applicationId)
    {
        $application = JobApplication::with(['applicant', 'student', 'alumni', 'jobPosting'])->findOrFail($applicationId);

        // Check authorization
        if ($application->jobPosting->partner_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $profile = null;
        if ($application->applicant_type === 'alumni') {
            $profile = $application->alumni;
        } else {
            $profile = $application->student;
        }

        return response()->json([
            'success' => true,
            'application' => $application,
            'profile' => $profile,
        ]);
    }
}
