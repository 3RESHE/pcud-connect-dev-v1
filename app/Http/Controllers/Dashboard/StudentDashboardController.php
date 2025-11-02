<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use App\Models\JobApplication;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\NewsArticle;
use App\Models\Experience;
use App\Models\Project;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class StudentDashboardController extends Controller
{
    /**
     * Show student dashboard.
     */
    public function dashboard()
    {
        $user = auth()->user();

        $stats = [
            'total_applications' => JobApplication::where('applicant_id', $user->id)->count(),
            'pending_applications' => JobApplication::where('applicant_id', $user->id)->where('status', 'pending')->count(),
            'approved_applications' => JobApplication::where('applicant_id', $user->id)->where('status', 'approved')->count(),
            'total_event_registrations' => EventRegistration::where('user_id', $user->id)->count(),
            'upcoming_events' => EventRegistration::where('user_id', $user->id)
                ->whereHas('event', fn($q) => $q->where('event_date', '>=', now()))
                ->with('event')
                ->limit(5)
                ->get(),
        ];

        return view('users.student.dashboard', $stats);
    }

    // ===== JOB BROWSING & APPLICATION =====

    /**
     * Show all published job postings.
     */
    public function jobs()
    {
        $jobs = JobPosting::where('status', 'published')
            ->where('application_deadline', '>=', now())
            ->with('partner')
            ->latest('published_at')
            ->paginate(20);

        return view('users.student.jobs.index', ['jobs' => $jobs]);
    }

    /**
     * Show job details.
     */
    public function viewJob($id)
    {
        $job = JobPosting::where('status', 'published')->findOrFail($id);
        $hasApplied = JobApplication::where('job_posting_id', $job->id)
            ->where('applicant_id', auth()->id())
            ->exists();

        return view('users.student.jobs.show', [
            'job' => $job,
            'hasApplied' => $hasApplied,
        ]);
    }

    /**
     * Apply for job.
     */
    public function applyJob(Request $request, $id)
    {
        $user = auth()->user();
        $job = JobPosting::where('status', 'published')->findOrFail($id);

        // Check if already applied
        if (JobApplication::where('job_posting_id', $job->id)
            ->where('applicant_id', $user->id)
            ->exists()) {
            return redirect()->back()->with('error', 'You have already applied for this job.');
        }

        $validated = $request->validate([
            'cover_letter' => 'nullable|string',
            'resume_path' => 'required|file|mimes:pdf,doc,docx',
        ]);

        $resume_path = $validated['resume_path']->store('resumes', 'public');

        $application = JobApplication::create([
            'job_posting_id' => $job->id,
            'applicant_id' => $user->id,
            'applicant_type' => 'student',
            'cover_letter' => $validated['cover_letter'] ?? null,
            'resume_path' => $resume_path,
            'status' => 'pending',
        ]);

        ActivityLog::logActivity(
            auth()->id(),
            'created',
            "Applied for job: {$job->title}",
            JobApplication::class,
            $application->id
        );

        return redirect()->route('student.applications.index')
            ->with('success', 'Application submitted successfully.');
    }

    /**
     * Show student's applications.
     */
    public function applications()
    {
        $user = auth()->user();
        $applications = JobApplication::where('applicant_id', $user->id)
            ->with('jobPosting')
            ->latest()
            ->paginate(20);

        return view('users.student.applications.index', ['applications' => $applications]);
    }

    /**
     * Show application details.
     */
    public function viewApplication($id)
    {
        $user = auth()->user();
        $application = JobApplication::where('applicant_id', $user->id)->findOrFail($id);

        return view('users.student.applications.show', ['application' => $application]);
    }

    // ===== EVENT MANAGEMENT =====

    /**
     * Show all published events.
     */
    public function events()
    {
        $events = Event::where('status', 'published')
            ->where('event_date', '>=', now())
            ->with('creator')
            ->orderBy('event_date')
            ->paginate(20);

        return view('users.student.events.index', ['events' => $events]);
    }

    /**
     * Show event details.
     */
    public function viewEvent($id)
    {
        $event = Event::where('status', 'published')->findOrFail($id);
        $isRegistered = EventRegistration::where('event_id', $event->id)
            ->where('user_id', auth()->id())
            ->exists();

        return view('users.student.events.show', [
            'event' => $event,
            'isRegistered' => $isRegistered,
        ]);
    }

    /**
     * Register for event.
     */
    public function registerEvent(Request $request, $id)
    {
        $user = auth()->user();
        $event = Event::where('status', 'published')->findOrFail($id);

        // Check if already registered
        if (EventRegistration::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->exists()) {
            return redirect()->back()->with('error', 'You are already registered for this event.');
        }

        // Check if at capacity
        if ($event->isAtCapacity()) {
            return redirect()->back()->with('error', 'Event is at full capacity.');
        }

        $registration = EventRegistration::create([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'user_type' => 'student',
            'registration_type' => 'online',
            'attendance_status' => 'registered',
        ]);

        ActivityLog::logActivity(
            auth()->id(),
            'created',
            "Registered for event: {$event->title}",
            EventRegistration::class,
            $registration->id
        );

        return redirect()->route('student.event-registrations.index')
            ->with('success', 'Event registration confirmed.');
    }

    /**
     * Show student's event registrations.
     */
    public function eventRegistrations()
    {
        $user = auth()->user();
        $registrations = EventRegistration::where('user_id', $user->id)
            ->with('event')
            ->latest()
            ->paginate(20);

        return view('users.student.event-registrations.index', ['registrations' => $registrations]);
    }

    // ===== PROFILE & PORTFOLIO =====

    /**
     * Show student profile.
     */
    public function profile()
    {
        $user = auth()->user();
        $profile = $user->studentProfile;

        return view('users.student.profile', ['profile' => $profile]);
    }

    /**
     * Update student profile.
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $profile = $user->studentProfile;

        $validated = $request->validate([
            'headline' => 'nullable|string|max:500',
            'personal_email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other,prefer_not_to_say',
            'linkedin_url' => 'nullable|url',
            'github_url' => 'nullable|url',
            'portfolio_url' => 'nullable|url',
            'technical_skills' => 'nullable|string',
            'soft_skills' => 'nullable|string',
            'certifications' => 'nullable|string',
            'languages' => 'nullable|string',
        ]);

        $profile->update($validated);

        ActivityLog::logActivity(
            auth()->id(),
            'updated',
            "Updated profile",
            \App\Models\StudentProfile::class,
            $profile->id
        );

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    // ===== EXPERIENCES =====

    /**
     * Show student's experiences.
     */
    public function experiences()
    {
        $user = auth()->user();
        $experiences = Experience::where('user_id', $user->id)
            ->orderBy('start_date', 'desc')
            ->paginate(20);

        return view('users.student.experiences.index', ['experiences' => $experiences]);
    }

    /**
     * Show create experience form.
     */
    public function createExperience()
    {
        return view('users.student.experiences.create');
    }

    /**
     * Store new experience.
     */
    public function storeExperience(Request $request)
    {
        $validated = $request->validate([
            'role_position' => 'required|string|max:255',
            'organization' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_current' => 'boolean',
            'experience_type' => 'required|in:parttime,internship,volunteer,organization,competition,project',
            'description' => 'nullable|string',
        ]);

        $experience = Experience::create(array_merge($validated, [
            'user_id' => auth()->id(),
            'user_type' => 'student',
        ]));

        ActivityLog::logActivity(
            auth()->id(),
            'created',
            "Added experience: {$experience->role_position}",
            Experience::class,
            $experience->id
        );

        return redirect()->route('student.experiences.index')
            ->with('success', 'Experience added successfully.');
    }

    /**
     * Show edit experience form.
     */
    public function editExperience($id)
    {
        $user = auth()->user();
        $experience = Experience::where('user_id', $user->id)->findOrFail($id);

        return view('users.student.experiences.edit', ['experience' => $experience]);
    }

    /**
     * Update experience.
     */
    public function updateExperience(Request $request, $id)
    {
        $user = auth()->user();
        $experience = Experience::where('user_id', $user->id)->findOrFail($id);

        $validated = $request->validate([
            'role_position' => 'required|string|max:255',
            'organization' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_current' => 'boolean',
            'experience_type' => 'required|in:parttime,internship,volunteer,organization,competition,project',
            'description' => 'nullable|string',
        ]);

        $experience->update($validated);

        ActivityLog::logActivity(
            auth()->id(),
            'updated',
            "Updated experience: {$experience->role_position}",
            Experience::class,
            $experience->id
        );

        return redirect()->route('student.experiences.index')
            ->with('success', 'Experience updated successfully.');
    }

    /**
     * Delete experience.
     */
    public function deleteExperience($id)
    {
        $user = auth()->user();
        $experience = Experience::where('user_id', $user->id)->findOrFail($id);

        ActivityLog::logActivity(
            auth()->id(),
            'deleted',
            "Deleted experience: {$experience->role_position}",
            Experience::class,
            $experience->id
        );

        $experience->delete();

        return redirect()->route('student.experiences.index')
            ->with('success', 'Experience deleted successfully.');
    }

    // ===== PROJECTS =====

    /**
     * Show student's projects.
     */
    public function projects()
    {
        $user = auth()->user();
        $projects = Project::where('user_id', $user->id)
            ->orderBy('start_date', 'desc')
            ->paginate(20);

        return view('users.student.projects.index', ['projects' => $projects]);
    }

    /**
     * Show create project form.
     */
    public function createProject()
    {
        return view('users.student.projects.create');
    }

    /**
     * Store new project.
     */
    public function storeProject(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'nullable|url',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'description' => 'nullable|string',
        ]);

        $project = Project::create(array_merge($validated, [
            'user_id' => auth()->id(),
            'user_type' => 'student',
        ]));

        ActivityLog::logActivity(
            auth()->id(),
            'created',
            "Added project: {$project->title}",
            Project::class,
            $project->id
        );

        return redirect()->route('student.projects.index')
            ->with('success', 'Project added successfully.');
    }

    /**
     * Show edit project form.
     */
    public function editProject($id)
    {
        $user = auth()->user();
        $project = Project::where('user_id', $user->id)->findOrFail($id);

        return view('users.student.projects.edit', ['project' => $project]);
    }

    /**
     * Update project.
     */
    public function updateProject(Request $request, $id)
    {
        $user = auth()->user();
        $project = Project::where('user_id', $user->id)->findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'nullable|url',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'description' => 'nullable|string',
        ]);

        $project->update($validated);

        ActivityLog::logActivity(
            auth()->id(),
            'updated',
            "Updated project: {$project->title}",
            Project::class,
            $project->id
        );

        return redirect()->route('student.projects.index')
            ->with('success', 'Project updated successfully.');
    }

    /**
     * Delete project.
     */
    public function deleteProject($id)
    {
        $user = auth()->user();
        $project = Project::where('user_id', $user->id)->findOrFail($id);

        ActivityLog::logActivity(
            auth()->id(),
            'deleted',
            "Deleted project: {$project->title}",
            Project::class,
            $project->id
        );

        $project->delete();

        return redirect()->route('student.projects.index')
            ->with('success', 'Project deleted successfully.');
    }

    // ===== NEWS FEED =====

    /**
     * Show published news articles.
     */
    public function news()
    {
        $articles = NewsArticle::where('status', 'published')
            ->where('is_archived', false)
            ->latest('published_at')
            ->paginate(20);

        return view('users.student.news.index', ['articles' => $articles]);
    }

    /**
     * Show article details.
     */
    public function viewNews($id)
    {
        $article = NewsArticle::where('status', 'published')
            ->where('is_archived', false)
            ->findOrFail($id);

        // Increment views
        $article->incrementViews();

        return view('users.student.news.show', ['article' => $article]);
    }
}
