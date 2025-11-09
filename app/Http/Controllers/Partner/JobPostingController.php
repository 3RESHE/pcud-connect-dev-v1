<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use App\Models\ActivityLog;
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
                'is_unpaid' => 'sometimes|in:0,1,on,true',  // ✅ FIX
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

            // ✅ FIX: Convert is_unpaid to boolean properly
            $isUnpaid = (bool) ($request->input('is_unpaid') === '1' || $request->input('is_unpaid') === 'on');

            if (!$isUnpaid) {
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

            // ✅ Set is_unpaid correctly
            $validated['is_unpaid'] = $isUnpaid;

            // Create job posting
            $job = JobPosting::create([
                ...$validated,
                'partner_id' => $user->id,
                'status' => 'pending',
                'sub_status' => 'active',
            ]);

            // Log activity
            ActivityLog::create([
                'user_id' => $user->id,
                'action' => 'created',
                'subject_type' => 'JobPosting',
                'subject_id' => $job->id,
                'description' => "Created job posting: {$job->title}",
                'ip_address' => request()->ip(),
                'new_values' => json_encode($job->toArray()),
            ]);

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
     * Update job posting
     */
    public function update(Request $request, JobPosting $jobPosting)
    {
        try {
            if ($jobPosting->partner_id !== auth()->id()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            if (!in_array($jobPosting->status, ['pending', 'rejected', 'approved'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'This job posting cannot be edited'
                ], 422);
            }

            $oldValues = $jobPosting->toArray();

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'job_type' => 'required|in:fulltime,parttime,internship,other',
                'experience_level' => 'required|in:entry,mid,senior,lead,student',
                'department' => 'nullable|string|max:255',
                'description' => 'required|string|min:50|max:5000',
                'work_setup' => 'required|in:onsite,remote,hybrid',
                'location' => 'nullable|string|max:255',
                'salary_min' => 'nullable|numeric|min:0',
                'salary_max' => 'nullable|numeric|min:0',
                'salary_period' => 'nullable|in:monthly,hourly,daily,project',
                'is_unpaid' => 'sometimes|in:0,1,on,true',  // ✅ FIX
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

            // ✅ FIX: Convert is_unpaid to boolean properly
            $isUnpaid = (bool) ($request->input('is_unpaid') === '1' || $request->input('is_unpaid') === 'on');

            // Salary validation
            if (!$isUnpaid) {
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
                $skillsArray = array_filter(array_map('trim', explode(',', $validated['technical_skills'])));
                $validated['technical_skills'] = json_encode($skillsArray);
            } else {
                $validated['technical_skills'] = json_encode([]);
            }

            // If was rejected, reset status to pending for re-review
            $wasRejected = $jobPosting->status === 'rejected';
            if ($wasRejected) {
                $validated['status'] = 'pending';
                $validated['rejection_reason'] = null;
            }

            // ✅ Set is_unpaid correctly
            $validated['is_unpaid'] = $isUnpaid;

            $jobPosting->update($validated);

            // Log activity
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'updated',
                'subject_type' => 'JobPosting',
                'subject_id' => $jobPosting->id,
                'description' => "Updated job posting: {$jobPosting->title}" . ($wasRejected ? ' (Resubmitted after rejection)' : ''),
                'ip_address' => request()->ip(),
                'old_values' => json_encode($oldValues),
                'new_values' => json_encode($jobPosting->toArray()),
            ]);

            return response()->json([
                'success' => true,
                'message' => $wasRejected ? 'Updated and resubmitted for approval!' : 'Job posting updated successfully!',
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
     * Show job posting
     */
    public function show(JobPosting $jobPosting)
    {
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
        if ($jobPosting->partner_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        if (!$jobPosting->canBeEdited()) {
            return redirect()->route('partner.job-postings.show', $jobPosting->id)
                ->with('error', 'This job posting cannot be edited');
        }

        // Decode technical_skills for the form
        $technicalSkills = is_string($jobPosting->technical_skills)
            ? json_decode($jobPosting->technical_skills, true)
            : ($jobPosting->technical_skills ?? []);
        $technicalSkills = is_array($technicalSkills) ? implode(', ', $technicalSkills) : '';

        return view('users.partner.job-postings.edit', [
            'jobPosting' => $jobPosting,
            'technicalSkills' => $technicalSkills,
            'isEditing' => true,
        ]);
    }


    /**
     * Update job posting
     */

    /**
     * Delete/Withdraw job posting
     */
    public function destroy(JobPosting $jobPosting)
    {
        if ($jobPosting->partner_id !== auth()->id()) {
            return redirect()->route('partner.job-postings.index')
                ->with('error', 'Unauthorized action');
        }

        if (!in_array($jobPosting->status, ['pending', 'rejected'])) {
            return redirect()->route('partner.job-postings.show', $jobPosting->id)
                ->with('error', 'This job posting cannot be withdrawn');
        }

        $title = $jobPosting->title;
        $jobId = $jobPosting->id;

        // Log activity BEFORE deleting
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'deleted',
            'subject_type' => 'JobPosting',
            'subject_id' => $jobId,
            'description' => "Withdrew job posting: {$title}",
            'ip_address' => request()->ip(),
            'old_values' => json_encode($jobPosting->toArray()),
        ]);

        $jobPosting->delete();

        return redirect()->route('partner.job-postings.index')
            ->with('success', "Job posting '{$title}' has been withdrawn successfully");
    }

    /**
     * Pause job posting
     */
    public function pause(JobPosting $jobPosting)
    {
        if ($jobPosting->partner_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        if ($jobPosting->status !== 'approved') {
            return redirect()->route('partner.job-postings.show', $jobPosting->id)
                ->with('error', 'Only approved postings can be paused');
        }

        $oldStatus = $jobPosting->sub_status;
        $jobPosting->update(['sub_status' => 'paused']);

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'updated',
            'subject_type' => 'JobPosting',
            'subject_id' => $jobPosting->id,
            'description' => "Paused job posting: {$jobPosting->title}",
            'ip_address' => request()->ip(),
            'old_values' => json_encode(['sub_status' => $oldStatus]),
            'new_values' => json_encode(['sub_status' => 'paused']),
        ]);

        return redirect()->route('partner.job-postings.show', $jobPosting->id)
            ->with('success', 'Job posting paused successfully');
    }

    /**
     * Resume job posting
     */
    public function resume(JobPosting $jobPosting)
    {
        if ($jobPosting->partner_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        if ($jobPosting->status !== 'approved') {
            return redirect()->route('partner.job-postings.show', $jobPosting->id)
                ->with('error', 'Only approved postings can be resumed');
        }

        $oldStatus = $jobPosting->sub_status;
        $jobPosting->update(['sub_status' => 'active']);

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'updated',
            'subject_type' => 'JobPosting',
            'subject_id' => $jobPosting->id,
            'description' => "Resumed job posting: {$jobPosting->title}",
            'ip_address' => request()->ip(),
            'old_values' => json_encode(['sub_status' => $oldStatus]),
            'new_values' => json_encode(['sub_status' => 'active']),
        ]);

        return redirect()->route('partner.job-postings.show', $jobPosting->id)
            ->with('success', 'Job posting resumed successfully');
    }

    /**
     * Close job posting
     */
    public function close(JobPosting $jobPosting)
    {
        if ($jobPosting->partner_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        if ($jobPosting->status !== 'approved') {
            return redirect()->route('partner.job-postings.show', $jobPosting->id)
                ->with('error', 'Only approved postings can be closed');
        }

        $oldStatus = $jobPosting->status;
        $jobPosting->update([
            'status' => 'completed',
            'closed_at' => now(),
        ]);

        // Log activity
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'updated',
            'subject_type' => 'JobPosting',
            'subject_id' => $jobPosting->id,
            'description' => "Closed job posting: {$jobPosting->title}",
            'ip_address' => request()->ip(),
            'old_values' => json_encode(['status' => $oldStatus]),
            'new_values' => json_encode(['status' => 'completed']),
        ]);

        return redirect()->route('partner.job-postings.index')
            ->with('success', 'Job posting closed successfully');
    }


    public function applications(JobPosting $jobPosting)
    {
        if ($jobPosting->partner_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Get applications with pagination
        $applications = $jobPosting->applications()
            ->with('alumni')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get application statistics
        $stats = [
            'total' => $jobPosting->applications()->count(),
            'pending' => $jobPosting->applications()->where('status', 'pending')->count(),
            'reviewed' => $jobPosting->applications()->where('status', 'reviewed')->count(),
            'approved' => $jobPosting->applications()->where('status', 'approved')->count(),
            'rejected' => $jobPosting->applications()->where('status', 'rejected')->count(),
        ];

        return view('users.partner.job-postings.applications', [
            'jobPosting' => $jobPosting,
            'applications' => $applications,
            'stats' => $stats,
        ]);
    }
}
