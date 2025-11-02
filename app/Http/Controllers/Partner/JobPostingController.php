<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use Illuminate\Http\Request;

class JobPostingController extends Controller
{
    /**
     * Display all job postings for partner
     */
    public function index()
    {
        $partner = auth()->user();

        $jobs = JobPosting::where('partner_id', $partner->id)
            ->with('applications')
            ->latest('created_at')
            ->paginate(20);

        $data = [
            'jobs' => $jobs,
            'approved_count' => JobPosting::where('partner_id', $partner->id)
                ->where('status', 'approved')
                ->count(),
            'pending_count' => JobPosting::where('partner_id', $partner->id)
                ->where('status', 'pending')
                ->count(),
            'rejected_count' => JobPosting::where('partner_id', $partner->id)
                ->where('status', 'rejected')
                ->count(),
            'completed_count' => JobPosting::where('partner_id', $partner->id)
                ->where('status', 'completed')
                ->count(),
            'total_applications' => JobPosting::where('partner_id', $partner->id)
                ->withCount('applications')
                ->get()
                ->sum('applications_count'),
            'total_jobs' => JobPosting::where('partner_id', $partner->id)->count(),
        ];

        return view('users.partner.job-postings.index', $data);
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('users.partner.job-postings.create');
    }

    /**
     * Store job posting
     */
    public function store(Request $request)
    {
        // Blank implementation - will implement later
        return redirect()->route('partner.job-postings.index')
            ->with('success', 'Job posting created successfully!');
    }

    /**
     * Show job posting
     */
    public function show(JobPosting $jobPosting)
    {
        // Blank implementation - will implement later
        return view('users.partner.job-postings.show', compact('jobPosting'));
    }

    /**
     * Show edit form
     */
    public function edit(JobPosting $jobPosting)
    {
        // Blank implementation - will implement later
        return view('users.partner.job-postings.edit', compact('jobPosting'));
    }

    /**
     * Update job posting
     */
    public function update(Request $request, JobPosting $jobPosting)
    {
        // Blank implementation - will implement later
        return redirect()->route('partner.job-postings.index')
            ->with('success', 'Job posting updated successfully!');
    }

    /**
     * Pause job posting
     */
    public function pause(JobPosting $jobPosting)
    {
        $jobPosting->update(['sub_status' => 'paused']);
        return redirect()->back()->with('success', 'Job posting paused successfully!');
    }

    /**
     * Resume job posting
     */
    public function resume(JobPosting $jobPosting)
    {
        $jobPosting->update(['sub_status' => 'active']);
        return redirect()->back()->with('success', 'Job posting resumed successfully!');
    }

    /**
     * Close job posting
     */
    public function close(JobPosting $jobPosting)
    {
        $jobPosting->update([
            'status' => 'completed',
            'closed_at' => now(),
        ]);
        return redirect()->back()->with('success', 'Job posting closed successfully!');
    }

    /**
     * View applications for job
     */
    public function applications(JobPosting $jobPosting)
    {
        // Blank implementation - will implement later
        return view('users.partner.job-postings.applications', compact('jobPosting'));
    }
}
