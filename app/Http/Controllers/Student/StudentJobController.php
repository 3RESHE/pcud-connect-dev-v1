<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use App\Models\User;
use Illuminate\View\View;

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

            // ✅ ENHANCED SEARCH - Search by title, department, location, company, description
            if (request('search')) {
                $search = request('search');
                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('department', 'like', "%{$search}%")
                      ->orWhere('location', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            // ✅ Filter by job type
            if (request('job_type')) {
                $query->where('job_type', request('job_type'));
            }

            // ✅ Filter by location
            if (request('location')) {
                $query->where('location', 'like', '%' . request('location') . '%');
            }

            // ✅ Filter by experience level
            if (request('experience_level')) {
                $query->where('experience_level', request('experience_level'));
            }

            // ✅ Filter by work setup
            if (request('work_setup')) {
                $query->where('work_setup', request('work_setup'));
            }

            // ✅ Filter by department
            if (request('department')) {
                $query->where('department', 'like', '%' . request('department') . '%');
            }

            // ✅ Filter by salary range
            if (request('salary_min')) {
                $query->where('salary_min', '>=', request('salary_min'));
            }
            if (request('salary_max')) {
                $query->where('salary_max', '<=', request('salary_max'));
            }

            // ✅ SORTING
            $sort = request('sort', 'newest');
            if ($sort === 'oldest') {
                $query->orderBy('created_at', 'asc');
            } elseif ($sort === 'salary-high') {
                $query->orderBy('salary_max', 'desc');
            } else {
                $query->orderBy('created_at', 'desc');
            }

            $jobs = $query->paginate(10);

            // ✅ GET FEATURED JOBS (First 3 active featured jobs)
            $featuredJobs = JobPosting::where('status', 'approved')
                ->where('sub_status', 'active')
                ->where('is_featured', true)
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get();

            // ✅ GET JOB STATS
            $totalJobs = JobPosting::where('status', 'approved')
                ->where('sub_status', 'active')
                ->count();

        } catch (\Exception $e) {
            \Log::error('Student jobs index error: ' . $e->getMessage());
            $jobs = JobPosting::where('status', 'approved')
                ->where('sub_status', 'active')
                ->paginate(10);
            $featuredJobs = [];
            $totalJobs = 0;
        }

        return view('users.student.jobs.index', [
            'jobs' => $jobs,
            'featuredJobs' => $featuredJobs,
            'totalJobs' => $totalJobs,
        ]);
    }

    /**
     * Display job posting details for students
     */
    public function show(JobPosting $job): View
    {
        try {
            if ($job->status !== 'approved' || $job->sub_status !== 'active') {
                abort(404, 'Job posting not found or not available.');
            }

            // ✅ Get related jobs (same job type or from same partner)
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

            // ✅ Get job applicant count
            $applicantCount = $job->applications()->count();

            // ✅ Check if current student already applied
            $alreadyApplied = auth()->check() &&
                $job->applications()
                    ->where('student_id', auth()->id())
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
}
