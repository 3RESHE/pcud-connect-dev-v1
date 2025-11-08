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
        try {
            $user = auth()->user();

            // Validate input
            $validated = $request->validate([
                'title' => 'required|string|max:255|min:5',
                'job_type' => 'required|in:fulltime,parttime,internship,other',
                'experience_level' => 'required|in:entry,mid,senior,lead,student',
                'department' => 'nullable|string|max:255',
                'description' => 'required|string|min:50|max:5000',
                'work_setup' => 'required|in:onsite,remote,hybrid',
                'location' => 'nullable|string|max:255',
                'salary_min' => 'nullable|numeric|min:0|decimal:0,2',
                'salary_max' => 'nullable|numeric|min:0|decimal:0,2',
                'salary_period' => 'nullable|in:monthly,hourly,daily,project',
                'is_unpaid' => 'nullable|boolean',
                'duration_months' => 'nullable|integer|min:1|max:60',
                'preferred_start_date' => 'nullable|date|after_or_equal:today',
                'application_deadline' => 'required|date|after:today',
                'education_requirements' => 'nullable|string|max:2000',
                'experience_requirements' => 'nullable|string|max:2000',
                'technical_skills' => 'nullable|string|max:1000',
                'positions_available' => 'required|integer|min:1|max:100',
                'application_process' => 'nullable|string|max:1000',
                'benefits' => 'nullable|string|max:2000',
            ]);

            // Location validation for onsite jobs
            if ($validated['work_setup'] === 'onsite' && empty($validated['location'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => [
                        'location' => ['Location is required for on-site positions'],
                    ],
                ], 422);
            }

            // Salary validation for paid positions
            $isUnpaid = $request->has('is_unpaid') && $request->boolean('is_unpaid');

            if (!$isUnpaid) {
                // For paid positions, ensure salary is provided
                if (empty($validated['salary_min']) && empty($validated['salary_max'])) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validation failed',
                        'errors' => [
                            'salary_min' => ['Please specify at least minimum or maximum salary for paid positions'],
                            'salary_max' => ['Or specify maximum salary'],
                        ],
                    ], 422);
                }

                // Ensure min is not greater than max
                if (
                    !empty($validated['salary_min']) && !empty($validated['salary_max']) &&
                    (float)$validated['salary_min'] > (float)$validated['salary_max']
                ) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validation failed',
                        'errors' => [
                            'salary_max' => ['Maximum salary must be greater than minimum salary'],
                        ],
                    ], 422);
                }

                // Ensure salary period is specified
                if (empty($validated['salary_period'])) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validation failed',
                        'errors' => [
                            'salary_period' => ['Please specify the salary period (monthly, hourly, etc.)'],
                        ],
                    ], 422);
                }
            }

            // Convert technical_skills comma-separated to JSON array
            if (!empty($validated['technical_skills'])) {
                $skillsArray = array_filter(array_map('trim', explode(',', $validated['technical_skills'])));
                $validated['technical_skills'] = json_encode($skillsArray);
            } else {
                $validated['technical_skills'] = json_encode([]);
            }

            // Set is_unpaid
            $validated['is_unpaid'] = $isUnpaid;

            // Create job posting
            $job = JobPosting::create([
                ...$validated,
                'partner_id' => $user->id,
                'status' => 'pending',
                'sub_status' => 'active',
            ]);

            // Log activity
            \Log::info("Job posting created by partner {$user->id}: {$job->title}");

            return response()->json([
                'success' => true,
                'message' => 'Job posting created successfully! It\'s now pending admin review.',
                'redirect' => route('partner.job-postings.index'),
                'job_id' => $job->id,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Job posting creation error:', [
                'user_id' => auth()->id(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create job posting: ' . $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Show job posting
     */
    public function show(JobPosting $jobPosting)
    {
        // Check authorization
        if ($jobPosting->partner_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        return view('users.partner.job-postings.show', compact('jobPosting'));
    }

    /**
     * Show edit form
     */
    public function edit(JobPosting $jobPosting)
    {
        // Check authorization
        if ($jobPosting->partner_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Can only edit if pending or rejected
        if (!in_array($jobPosting->status, ['pending', 'rejected', 'approved'])) {
            return redirect()->route('partner.job-postings.show', $jobPosting->id)
                ->with('error', 'This job posting cannot be edited');
        }

        return view('users.partner.job-postings.edit', compact('jobPosting'));
    }

    /**
     * Update job posting
     */
    public function update(Request $request, JobPosting $jobPosting)
    {
        try {
            // Check authorization
            if ($jobPosting->partner_id !== auth()->id()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            // Can only edit if pending or rejected
            if (!in_array($jobPosting->status, ['pending', 'rejected', 'approved'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'This job posting cannot be edited'
                ], 422);
            }

            // Validate
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'job_type' => 'required|in:fulltime,parttime,internship,other',
                'experience_level' => 'required|in:entry,mid,senior,lead,student',
                'department' => 'nullable|string|max:255',
                'custom_department' => 'nullable|string|max:255',
                'description' => 'required|string|min:50|max:5000',
                'work_setup' => 'required|in:onsite,remote,hybrid',
                'location' => 'nullable|string|max:255',
                'salary_min' => 'nullable|numeric|min:0',
                'salary_max' => 'nullable|numeric|min:0',
                'salary_period' => 'nullable|in:monthly,hourly,daily,project',
                'is_unpaid' => 'boolean',
                'duration_months' => 'nullable|integer|min:1|max:60',
                'preferred_start_date' => 'nullable|date|after_or_equal:today',
                'application_deadline' => 'required|date|after:today',
                'education_requirements' => 'nullable|string|max:1000',
                'experience_requirements' => 'nullable|string|max:1000',
                'technical_skills' => 'nullable|string|max:1000',
                'positions_available' => 'required|integer|min:1|max:100',
                'application_process' => 'nullable|string|max:1000',
                'benefits' => 'nullable|string|max:1000',
            ]);

            // Salary validation
            if (!$request->boolean('is_unpaid')) {
                if (!$validated['salary_min'] && !$validated['salary_max']) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Paid positions must have at least minimum or maximum salary',
                        'errors' => ['salary_min' => ['Please specify salary range']],
                    ], 422);
                }

                if ($validated['salary_min'] && $validated['salary_max'] && $validated['salary_min'] > $validated['salary_max']) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Minimum salary cannot be greater than maximum salary',
                        'errors' => ['salary_max' => ['Maximum must be greater than minimum']],
                    ], 422);
                }
            }

            // Location validation
            if ($validated['work_setup'] === 'onsite' && !$validated['location']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Location is required for on-site positions',
                    'errors' => ['location' => ['Location required for on-site']],
                ], 422);
            }

            // Convert technical_skills to JSON array
            if ($validated['technical_skills']) {
                $validated['technical_skills'] = array_map(
                    'trim',
                    explode(',', $validated['technical_skills'])
                );
            }

            // If was rejected, reset status to pending
            if ($jobPosting->status === 'rejected') {
                $validated['status'] = 'pending';
            }

            $validated['is_unpaid'] = $request->boolean('is_unpaid');

            $jobPosting->update($validated);

            return response()->json([
                'success' => true,
                'message' => $jobPosting->status === 'pending' ? 'Updated and resubmitted for approval!' : 'Job posting updated successfully!',
                'redirect' => route('partner.job-postings.show', $jobPosting->id),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Job posting update error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to update job posting: ' . $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Pause job posting
     */
    public function pause(JobPosting $jobPosting)
    {
        if ($jobPosting->status !== 'approved') {
            return redirect()->back()->with('error', 'Only approved postings can be paused');
        }

        $jobPosting->update(['sub_status' => 'paused']);
        return redirect()->back()->with('success', 'Job posting paused successfully!');
    }

    /**
     * Resume job posting
     */
    public function resume(JobPosting $jobPosting)
    {
        if ($jobPosting->status !== 'approved') {
            return redirect()->back()->with('error', 'Only approved postings can be resumed');
        }

        $jobPosting->update(['sub_status' => 'active']);
        return redirect()->back()->with('success', 'Job posting resumed successfully!');
    }

    /**
     * Close job posting
     */
    public function close(JobPosting $jobPosting)
    {
        if ($jobPosting->status === 'completed') {
            return redirect()->back()->with('error', 'This job posting is already completed');
        }

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
        return view('users.partner.job-postings.applications', compact('jobPosting'));
    }
}
