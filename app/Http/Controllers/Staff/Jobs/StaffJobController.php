<?php

namespace App\Http\Controllers\Staff\Jobs;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use Illuminate\View\View;

class StaffJobController extends Controller
{
    /**
     * Display list of job postings for staff with inline details
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
            \Log::error('Staff jobs index error: ' . $e->getMessage());
            $jobs = JobPosting::where('status', 'approved')
                ->where('sub_status', 'active')
                ->paginate(10);
        }

        return view('users.staff.jobs.index', [
            'jobs' => $jobs,
        ]);
    }
}
