<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use Illuminate\View\View;

class StudentJobController extends Controller
{
    /**
     * Display list of job postings for students.
     */
    public function index(): View
    {
        try {
            $query = JobPosting::query()->where('status', 'approved');

            // Search filtering
            if (request('search')) {
                $search = request('search');
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('company', 'like', "%{$search}%")
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

            // Sort
            if (request('sort') === 'oldest') {
                $query->orderBy('created_at', 'asc');
            } else {
                $query->orderBy('created_at', 'desc');
            }

            $jobs = $query->paginate(10);

        } catch (\Exception $e) {
            \Log::error('Student jobs index error: ' . $e->getMessage());
            $jobs = JobPosting::where('status', 'approved')->paginate(10);
        }

        return view('users.student.jobs.index', [
            'jobs' => $jobs,
        ]);
    }

    /**
     * Display job posting details.
     */
    public function show(JobPosting $job): View
    {
        try {
            if ($job->status !== 'approved') {
                abort(404, 'Job posting not found.');
            }

            // Get related job postings (same company or industry)
            $relatedJobs = JobPosting::where('status', 'approved')
                ->where('id', '!=', $job->id)
                ->where(function ($q) use ($job) {
                    $q->where('company', $job->company)
                      ->orWhere('job_type', $job->job_type);
                })
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get();

            // Get recent jobs
            $recentJobs = JobPosting::where('status', 'approved')
                ->where('id', '!=', $job->id)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            return view('users.student.jobs.show', [
                'job' => $job,
                'relatedJobs' => $relatedJobs,
                'recentJobs' => $recentJobs,
            ]);
        } catch (\Exception $e) {
            \Log::error('Student job show error: ' . $e->getMessage());
            abort(500, 'An error occurred.');
        }
    }
}
