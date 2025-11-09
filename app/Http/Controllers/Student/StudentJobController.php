<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use App\Models\JobApplication;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class StudentJobController extends Controller
{
    /**
     * Display list of job postings for students with enhanced filtering
     */
    public function index(): View
    {
        try {
            $query = JobPosting::query()
                ->where('status', 'approved')
                ->where('sub_status', 'active');

            // Search filtering
            if (request('search')) {
                $search = request('search');
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('department', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            }

            // Filter by job type
            if (request('job_type')) {
                $query->where('job_type', request('job_type'));
            }

            // Filter by location
            if (request('location')) {
                $query->where('location', 'like', '%' . request('location') . '%');
            }

            // Filter by experience level
            if (request('experience_level')) {
                $query->where('experience_level', request('experience_level'));
            }

            // Filter by work setup
            if (request('work_setup')) {
                $query->where('work_setup', request('work_setup'));
            }

            // Filter by department
            if (request('department')) {
                $query->where('department', 'like', '%' . request('department') . '%');
            }

            // Sorting
            $sort = request('sort', 'newest');
            if ($sort === 'oldest') {
                $query->orderBy('created_at', 'asc');
            } elseif ($sort === 'salary-high') {
                $query->orderBy('salary_max', 'desc');
            } else {
                $query->orderBy('created_at', 'desc');
            }

            $jobs = $query->paginate(10);
        } catch (\Exception $e) {
            \Log::error('Student jobs index error: ' . $e->getMessage());
            $jobs = JobPosting::where('status', 'approved')
                ->where('sub_status', 'active')
                ->paginate(10);
        }

        return view('users.student.jobs.index', [
            'jobs' => $jobs,
        ]);
    }

    /**
     * Display job posting details for students
     */
    public function show(JobPosting $job): View
    {
        try {
            // Check if job is available
            if ($job->status !== 'approved' || $job->sub_status !== 'active') {
                abort(404, 'Job posting not found or not available.');
            }

            // Check if application deadline has passed
            if ($job->application_deadline < now()->toDateString()) {
                abort(410, 'The application deadline for this position has passed.');
            }

            // Get related jobs (same job type or from same partner)
            $relatedJobs = JobPosting::where('status', 'approved')
                ->where('sub_status', 'active')
                ->where('id', '!=', $job->id)
                ->where(function ($q) use ($job) {
                    $q->where('job_type', $job->job_type)
                        ->orWhere('partner_id', $job->partner_id);
                })
                ->orderBy('created_at', 'desc')
                ->limit(4)
                ->get();

            // Get job applicant count
            $applicantCount = $job->applications()->count();

            // Check if current student already applied
            $alreadyApplied = auth()->check() &&
                $job->applications()
                ->where('applicant_id', auth()->id())
                ->exists();

            return view('users.student.jobs.show', [
                'job' => $job,
                'relatedJobs' => $relatedJobs,
                'applicantCount' => $applicantCount,
                'alreadyApplied' => $alreadyApplied,
            ]);
        } catch (\Exception $e) {
            \Log::error('Student job show error: ' . $e->getMessage());
            abort(500, 'An error occurred while loading the job details.');
        }
    }

    public function apply(JobPosting $job, Request $request): RedirectResponse
    {
        try {
            // Basic checks
            if ($job->status !== 'approved' || $job->sub_status !== 'active') {
                return redirect()->back()->with('error', 'Job not available.');
            }

            // Check duplicate
            $existingApplication = JobApplication::where('job_posting_id', $job->id)
                ->where('applicant_id', auth()->id())
                ->first();

            if ($existingApplication) {
                return redirect()->back()->with('warning', 'Already applied.');
            }

            // Validate
            $validated = $request->validate([
                'cover_letter' => 'required|string|min:50|max:5000',
                'resume_option' => 'required|in:existing,upload',
                'resume_file' => 'nullable|mimes:pdf,doc,docx|max:5120',
                'confirmApplication' => 'required|accepted',
            ]);

            // Handle resume
            $resumePath = null;

            if ($request->resume_option === 'upload' && $request->hasFile('resume_file')) {
                $file = $request->file('resume_file');
                $filename = auth()->id() . '_' . time() . '_' . $file->getClientOriginalName();
                $resumePath = $file->storeAs('resumes/job-applications/' . auth()->id(), $filename, 'public');
            } else {
                $studentProfile = auth()->user()->studentProfile;
                if ($studentProfile && $studentProfile->resume_path) {
                    $resumePath = $studentProfile->resume_path;
                } else {
                    return redirect()->back()->with('error', 'No resume found.')->withInput();
                }
            }

            if (!$resumePath) {
                return redirect()->back()->with('error', 'Resume required.')->withInput();
            }

            // Create application
            $application = JobApplication::create([
                'job_posting_id' => $job->id,
                'applicant_id' => auth()->id(),
                'applicant_type' => 'student',
                'cover_letter' => $validated['cover_letter'],
                'resume_path' => $resumePath,
                'status' => 'pending',
            ]);

            // ✅ LOG TO ACTIVITY LOG TABLE
            try {
                ActivityLog::logActivity(
                    userId: auth()->id(),
                    action: 'applied',
                    description: auth()->user()->first_name . ' ' . auth()->user()->last_name . ' applied for job: ' . $job->title,
                    subjectType: JobApplication::class,
                    subjectId: $application->id,
                    properties: [
                        'job_id' => $job->id,
                        'job_title' => $job->title,
                    ]
                );
                \Log::info('Activity logged for application: ' . $application->id);
            } catch (\Exception $e) {
                \Log::warning('Activity log failed: ' . $e->getMessage());
                // Don't fail the application if logging fails
            }

            return redirect()->route('student.jobs.applications.show', $application->id)
                ->with('success', 'Application submitted!');
        } catch (\Exception $e) {
            \Log::error('Apply error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }



    /**
     * View student's job applications
     */
    public function applications(): View
    {
        try {
            $applications = JobApplication::where('applicant_id', auth()->id())
                ->where('applicant_type', 'student')
                ->with(['jobPosting', 'applicant'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            // Count by status
            $statuses = [
                'pending' => JobApplication::where('applicant_id', auth()->id())
                    ->where('applicant_type', 'student')
                    ->where('status', 'pending')
                    ->count(),
                'approved' => JobApplication::where('applicant_id', auth()->id())
                    ->where('applicant_type', 'student')
                    ->where('status', 'approved')
                    ->count(),
                'rejected' => JobApplication::where('applicant_id', auth()->id())
                    ->where('applicant_type', 'student')
                    ->where('status', 'rejected')
                    ->count(),
            ];

            return view('users.student.applications.index', [
                'applications' => $applications,
                'statuses' => $statuses,
            ]);
        } catch (\Exception $e) {
            \Log::error('Applications index error: ' . $e->getMessage());
            abort(500, 'An error occurred while loading your applications.');
        }
    }

    /**
     * View single job application details
     */
    public function viewApplication(JobApplication $application): View
    {
        try {
            // Check authorization
            if ($application->applicant_id !== auth()->id()) {
                abort(403, 'You do not have permission to view this application.');
            }

            return view('users.student.applications.show', [
                'application' => $application,
            ]);
        } catch (\Exception $e) {
            \Log::error('Application view error: ' . $e->getMessage());
            abort(500, 'An error occurred while loading this application.');
        }
    }
}
