<?php

namespace App\Http\Controllers\Alumni;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\JobPosting;
use Carbon\Carbon;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class JobController extends Controller
{
    /**
     * Display a list of available jobs for alumni.
     *
     * @return View
     */
    public function index(): View
    {
        try {
            $query = JobPosting::query()
                ->where('status', 'approved');

            // ===== SEARCH FILTERING =====
            if (request('search')) {
                $search = request('search');
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhere('company', 'like', "%{$search}%");
                });
            }

            // ===== LOCATION FILTERING =====
            if (request('location')) {
                $query->where('location', 'like', '%' . request('location') . '%');
            }

            // ===== JOB TYPE FILTERING =====
            if (request('job_type')) {
                $query->where('job_type', request('job_type'));
            }

            // ===== SORTING =====
            if (request('sort') === 'company') {
                $query->orderBy('company', 'asc');
            } else {
                $query->orderBy('created_at', 'desc');
            }

            // ===== PAGINATION =====
            $jobs = $query->paginate(10);
        } catch (\Exception $e) {
            \Log::error('Job index error: ' . $e->getMessage());
            $jobs = collect()->paginate(10); // Empty paginated collection
        }

        return view('users.alumni.jobs.index', [
            'jobs' => $jobs,
        ]);
    }

    /**
     * Display a single job posting.
     *
     * @param JobPosting $job
     * @return View
     */
    public function show(JobPosting $job): View
    {
        try {
            // Check if job is approved
            if ($job->status !== 'approved') {
                abort(404, 'Job posting not found.');
            }

            $user = auth()->user();

            // Check if user has already applied
            $hasApplied = false;
            $userApplication = null;

            try {
                $hasApplied = JobApplication::where('applicant_id', $user->id)
                    ->where('job_posting_id', $job->id)
                    ->exists();

                if ($hasApplied) {
                    $userApplication = JobApplication::where('applicant_id', $user->id)
                        ->where('job_posting_id', $job->id)
                        ->first();
                }
            } catch (\Exception $e) {
                \Log::warning('Error checking job application: ' . $e->getMessage());
            }

            // Get similar jobs (same job type, max 5)
            $similarJobs = collect();
            try {
                $similarJobs = JobPosting::query()
                    ->where('status', 'approved')
                    ->where('id', '!=', $job->id)
                    ->where(function ($q) use ($job) {
                        $q->where('job_type', $job->job_type)
                          ->orWhere('location', $job->location);
                    })
                    ->limit(5)
                    ->get();
            } catch (\Exception $e) {
                \Log::warning('Error fetching similar jobs: ' . $e->getMessage());
            }

            return view('users.alumni.jobs.show', [
                'job' => $job,
                'hasApplied' => $hasApplied,
                'userApplication' => $userApplication,
                'similarJobs' => $similarJobs,
            ]);
        } catch (\Exception $e) {
            \Log::error('Job show error: ' . $e->getMessage());
            abort(500, 'An error occurred while loading the job details.');
        }
    }

    /**
     * Submit a job application.
     *
     * @param JobPosting $job
     * @return RedirectResponse
     */
    public function apply(JobPosting $job): RedirectResponse
    {
        try {
            $user = auth()->user();

            // ===== VALIDATION: JOB EXISTS & IS ACTIVE =====
            if ($job->status !== 'approved') {
                return redirect()->back()->with('error', 'This job posting is no longer available.');
            }

            // ===== VALIDATION: USER HASN'T ALREADY APPLIED =====
            $existingApplication = JobApplication::where('applicant_id', $user->id)
                ->where('job_posting_id', $job->id)
                ->first();

            if ($existingApplication) {
                return redirect()->back()->with('error', 'You have already applied for this position.');
            }

            // ===== CREATE APPLICATION =====
            JobApplication::create([
                'job_posting_id' => $job->id,
                'applicant_id' => $user->id,
                'status' => 'pending',
                'applied_at' => Carbon::now(),
            ]);

            return redirect()->route('alumni.jobs.show', $job->id)
                ->with('success', 'Your application has been submitted successfully!');
        } catch (\Exception $e) {
            \Log::error('Job application error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while submitting your application. Please try again.');
        }
    }
}
