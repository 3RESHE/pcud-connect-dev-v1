<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use App\Models\JobApplication;
use App\Models\Partnership;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class PartnerDashboardController extends Controller
{
    /**
     * Show partner dashboard.
     */
    public function dashboard()
    {
        $user = auth()->user();

        $stats = [
            'total_jobs' => JobPosting::where('partner_id', $user->id)->count(),
            'published_jobs' => JobPosting::where('partner_id', $user->id)->where('status', 'published')->count(),
            'pending_jobs' => JobPosting::where('partner_id', $user->id)->where('status', 'pending')->count(),
            'total_applications' => JobApplication::whereHas('jobPosting', function ($q) use ($user) {
                $q->where('partner_id', $user->id);
            })->count(),
            'pending_applications' => JobApplication::whereHas('jobPosting', function ($q) use ($user) {
                $q->where('partner_id', $user->id);
            })->where('status', 'pending')->count(),
            'recent_jobs' => JobPosting::where('partner_id', $user->id)->latest()->limit(5)->get(),
        ];

        return view('users.partner.dashboard', $stats);
    }

    // ===== JOB POSTING MANAGEMENT =====

    /**
     * Show all jobs posted by partner.
     */
    public function jobs()
    {
        $user = auth()->user();
        $jobs = JobPosting::where('partner_id', $user->id)
            ->latest()
            ->paginate(20);

        return view('users.partner.jobs.index', ['jobs' => $jobs]);
    }

    /**
     * Show create job form.
     */
    public function createJob()
    {
        return view('users.partner.jobs.create');
    }

    /**
     * Store new job posting.
     */
    public function storeJob(Request $request)
    {
        $validated = $request->validate([
            'job_type' => 'required|in:fulltime,parttime,internship,contract',
            'title' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'experience_level' => 'required|in:entry,mid,senior,lead,student',
            'description' => 'required|string',
            'work_setup' => 'required|in:onsite,remote,hybrid',
            'location' => 'nullable|string|max:255',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0',
            'salary_period' => 'nullable|in:monthly,hourly,daily,project',
            'is_unpaid' => 'boolean',
            'duration_months' => 'nullable|integer|min:1',
            'preferred_start_date' => 'nullable|date',
            'education_requirements' => 'nullable|string',
            'technical_skills' => 'nullable|json',
            'experience_requirements' => 'nullable|string',
            'application_deadline' => 'required|date|after:today',
            'positions_available' => 'required|integer|min:1',
            'application_process' => 'nullable|string',
            'benefits' => 'nullable|string',
        ]);

        $job = JobPosting::create(array_merge($validated, [
            'partner_id' => auth()->id(),
            'status' => 'pending',
        ]));

        ActivityLog::logActivity(
            auth()->id(),
            'created',
            "Created job posting: {$job->title}",
            JobPosting::class,
            $job->id
        );

        return redirect()->route('partner.jobs.index')
            ->with('success', 'Job posting created and submitted for approval.');
    }

    /**
     * Show edit job form.
     */
    public function editJob($id)
    {
        $user = auth()->user();
        $job = JobPosting::where('partner_id', $user->id)->findOrFail($id);

        return view('users.partner.jobs.edit', ['job' => $job]);
    }

    /**
     * Update job posting.
     */
    public function updateJob(Request $request, $id)
    {
        $user = auth()->user();
        $job = JobPosting::where('partner_id', $user->id)->findOrFail($id);

        $validated = $request->validate([
            'job_type' => 'required|in:fulltime,parttime,internship,contract',
            'title' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'experience_level' => 'required|in:entry,mid,senior,lead,student',
            'description' => 'required|string',
            'work_setup' => 'required|in:onsite,remote,hybrid',
            'location' => 'nullable|string|max:255',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0',
            'salary_period' => 'nullable|in:monthly,hourly,daily,project',
            'is_unpaid' => 'boolean',
            'duration_months' => 'nullable|integer|min:1',
            'preferred_start_date' => 'nullable|date',
            'education_requirements' => 'nullable|string',
            'technical_skills' => 'nullable|json',
            'experience_requirements' => 'nullable|string',
            'application_deadline' => 'required|date',
            'positions_available' => 'required|integer|min:1',
            'application_process' => 'nullable|string',
            'benefits' => 'nullable|string',
        ]);

        $job->update($validated);

        ActivityLog::logActivity(
            auth()->id(),
            'updated',
            "Updated job posting: {$job->title}",
            JobPosting::class,
            $job->id
        );

        return redirect()->route('partner.jobs.index')
            ->with('success', 'Job posting updated successfully.');
    }

    /**
     * Delete job posting.
     */
    public function deleteJob($id)
    {
        $user = auth()->user();
        $job = JobPosting::where('partner_id', $user->id)->findOrFail($id);

        ActivityLog::logActivity(
            auth()->id(),
            'deleted',
            "Deleted job posting: {$job->title}",
            JobPosting::class,
            $job->id
        );

        $job->delete();

        return redirect()->route('partner.jobs.index')
            ->with('success', 'Job posting deleted successfully.');
    }

    /**
     * Show applications for a job.
     */
    public function jobApplications($id)
    {
        $user = auth()->user();
        $job = JobPosting::where('partner_id', $user->id)->findOrFail($id);
        $applications = JobApplication::where('job_posting_id', $job->id)
            ->with('applicant')
            ->paginate(50);

        return view('users.partner.jobs.applications', [
            'job' => $job,
            'applications' => $applications,
        ]);
    }

    /**
     * Approve job application.
     */
    public function approveApplication($jobId, $appId)
    {
        $user = auth()->user();
        $job = JobPosting::where('partner_id', $user->id)->findOrFail($jobId);
        $application = JobApplication::where('job_posting_id', $job->id)->findOrFail($appId);

        $application->approve();

        ActivityLog::logActivity(
            auth()->id(),
            'approved',
            "Approved application from {$application->applicant->email}",
            JobApplication::class,
            $application->id
        );

        return redirect()->back()->with('success', 'Application approved.');
    }

    /**
     * Reject job application.
     */
    public function rejectApplication($jobId, $appId)
    {
        $user = auth()->user();
        $job = JobPosting::where('partner_id', $user->id)->findOrFail($jobId);
        $application = JobApplication::where('job_posting_id', $job->id)->findOrFail($appId);

        $application->reject();

        ActivityLog::logActivity(
            auth()->id(),
            'rejected',
            "Rejected application from {$application->applicant->email}",
            JobApplication::class,
            $application->id
        );

        return redirect()->back()->with('success', 'Application rejected.');
    }

    // ===== PARTNERSHIP MANAGEMENT =====

    /**
     * Show all partnerships submitted by partner.
     */
    public function partnerships()
    {
        $user = auth()->user();
        $partnerships = Partnership::where('partner_id', $user->id)
            ->latest()
            ->paginate(20);

        return view('users.partner.partnerships.index', ['partnerships' => $partnerships]);
    }

    /**
     * Show create partnership form.
     */
    public function createPartnership()
    {
        return view('users.partner.partnerships.create');
    }

    /**
     * Store new partnership.
     */
    public function storePartnership(Request $request)
    {
        $validated = $request->validate([
            'activity_type' => 'required|in:feedingprogram,brigadaeskwela,communitycleanup,treeplanting,donationdrive,other',
            'custom_activity_type' => 'nullable|string|max:255',
            'organization_name' => 'required|string|max:255',
            'organization_background' => 'required|string',
            'organization_website' => 'nullable|url',
            'organization_phone' => 'required|string|max:20',
            'activity_title' => 'required|string|max:255',
            'activity_description' => 'required|string',
            'activity_date' => 'required|date|after:today',
            'activity_time' => 'required|date_format:H:i',
            'venue_address' => 'required|string',
            'activity_objectives' => 'required|string',
            'expected_impact' => 'required|string',
            'contact_name' => 'required|string|max:255',
            'contact_position' => 'required|string|max:255',
            'contact_email' => 'required|email',
            'contact_phone' => 'required|string|max:20',
            'previous_experience' => 'nullable|string',
            'additional_notes' => 'nullable|string',
        ]);

        $partnership = Partnership::create(array_merge($validated, [
            'partner_id' => auth()->id(),
            'status' => 'submitted',
        ]));

        ActivityLog::logActivity(
            auth()->id(),
            'created',
            "Submitted partnership: {$partnership->activity_title}",
            Partnership::class,
            $partnership->id
        );

        return redirect()->route('partner.partnerships.index')
            ->with('success', 'Partnership submitted for review.');
    }

    /**
     * Show edit partnership form.
     */
    public function editPartnership($id)
    {
        $user = auth()->user();
        $partnership = Partnership::where('partner_id', $user->id)->findOrFail($id);

        return view('users.partner.partnerships.edit', ['partnership' => $partnership]);
    }

    /**
     * Update partnership.
     */
    public function updatePartnership(Request $request, $id)
    {
        $user = auth()->user();
        $partnership = Partnership::where('partner_id', $user->id)->findOrFail($id);

        $validated = $request->validate([
            'activity_type' => 'required|in:feedingprogram,brigadaeskwela,communitycleanup,treeplanting,donationdrive,other',
            'custom_activity_type' => 'nullable|string|max:255',
            'organization_name' => 'required|string|max:255',
            'organization_background' => 'required|string',
            'organization_website' => 'nullable|url',
            'organization_phone' => 'required|string|max:20',
            'activity_title' => 'required|string|max:255',
            'activity_description' => 'required|string',
            'activity_date' => 'required|date',
            'activity_time' => 'required|date_format:H:i',
            'venue_address' => 'required|string',
            'activity_objectives' => 'required|string',
            'expected_impact' => 'required|string',
            'contact_name' => 'required|string|max:255',
            'contact_position' => 'required|string|max:255',
            'contact_email' => 'required|email',
            'contact_phone' => 'required|string|max:20',
            'previous_experience' => 'nullable|string',
            'additional_notes' => 'nullable|string',
        ]);

        $partnership->update($validated);

        ActivityLog::logActivity(
            auth()->id(),
            'updated',
            "Updated partnership: {$partnership->activity_title}",
            Partnership::class,
            $partnership->id
        );

        return redirect()->route('partner.partnerships.index')
            ->with('success', 'Partnership updated successfully.');
    }

    /**
     * Delete partnership.
     */
    public function deletePartnership($id)
    {
        $user = auth()->user();
        $partnership = Partnership::where('partner_id', $user->id)->findOrFail($id);

        ActivityLog::logActivity(
            auth()->id(),
            'deleted',
            "Deleted partnership: {$partnership->activity_title}",
            Partnership::class,
            $partnership->id
        );

        $partnership->delete();

        return redirect()->route('partner.partnerships.index')
            ->with('success', 'Partnership deleted successfully.');
    }

    // ===== PROFILE MANAGEMENT =====

    /**
     * Show partner profile.
     */
    public function profile()
    {
        $user = auth()->user();
        $profile = $user->partnerProfile;

        return view('users.partner.profile', ['profile' => $profile]);
    }

    /**
     * Update partner profile.
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $profile = $user->partnerProfile;

        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'industry' => 'nullable|string|max:255',
            'company_size' => 'nullable|string|max:100',
            'founded_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'description' => 'nullable|string',
            'contact_person' => 'nullable|string|max:255',
            'contact_title' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'website' => 'nullable|url',
            'linkedin_url' => 'nullable|url',
        ]);

        $profile->update($validated);

        ActivityLog::logActivity(
            auth()->id(),
            'updated',
            "Updated company profile",
            \App\Models\PartnerProfile::class,
            $profile->id
        );

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
}
