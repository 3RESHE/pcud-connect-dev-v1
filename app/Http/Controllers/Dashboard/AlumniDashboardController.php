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
use App\Models\Notification;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class AlumniDashboardController extends Controller
{
    /**
     * Show alumni dashboard.
     */
    public function dashboard()
    {
        $user = auth()->user();

        $stats = [
            'total_applications' => JobApplication::where('applicant_id', $user->id)->count(),
            'pending_applications' => JobApplication::where('applicant_id', $user->id)->where('status', 'pending')->count(),
            'approved_applications' => JobApplication::where('applicant_id', $user->id)->where('status', 'approved')->count(),
            'total_event_registrations' => EventRegistration::where('user_id', $user->id)->count(),
            'unread_notifications' => Notification::where('user_id', $user->id)->unread()->count(),
            'upcoming_events' => EventRegistration::where('user_id', $user->id)
                ->whereHas('event', fn($q) => $q->where('event_date', '>=', now()))
                ->with('event')
                ->limit(5)
                ->get(),
        ];

        return view('users.alumni.dashboard', $stats);
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

        return view('users.alumni.jobs.index', ['jobs' => $jobs]);
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

        return view('users.alumni.jobs.show', [
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
            'applicant_type' => 'alumni',
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

        return redirect()->route('alumni.applications.index')
            ->with('success', 'Application submitted successfully.');
    }

    /**
     * Show alumni's applications.
     */
    public function applications()
    {
        $user = auth()->user();
        $applications = JobApplication::where('applicant_id', $user->id)
            ->with('jobPosting')
            ->latest()
            ->paginate(20);

        return view('users.alumni.applications.index', ['applications' => $applications]);
    }

    /**
     * Show application details.
     */
    public function viewApplication($id)
    {
        $user = auth()->user();
        $application = JobApplication::where('applicant_id', $user->id)->findOrFail($id);

        return view('users.alumni.applications.show', ['application' => $application]);
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

        return view('users.alumni.events.index', ['events' => $events]);
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

        return view('users.alumni.events.show', [
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
            'user_type' => 'alumni',
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

        return redirect()->route('alumni.event-registrations.index')
            ->with('success', 'Event registration confirmed.');
    }

    /**
     * Show alumni's event registrations.
     */
    public function eventRegistrations()
    {
        $user = auth()->user();
        $registrations = EventRegistration::where('user_id', $user->id)
            ->with('event')
            ->latest()
            ->paginate(20);

        return view('users.alumni.event-registrations.index', ['registrations' => $registrations]);
    }

    // ===== PROFILE & PORTFOLIO =====

    /**
     * Show alumni profile.
     */
    public function profile()
    {
        $user = auth()->user();
        $profile = $user->alumniProfile;

        return view('users.alumni.profile', ['profile' => $profile]);
    }

    /**
     * Update alumni profile.
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $profile = $user->alumniProfile;

        $validated = $request->validate([
            'headline' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'current_location' => 'nullable|string|max:255',
            'linkedin_url' => 'nullable|url',
            'github_url' => 'nullable|url',
            'portfolio_url' => 'nullable|url',
            'professional_summary' => 'nullable|string',
            'degree_program' => 'nullable|string|max:255',
            'graduation_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'gwa' => 'nullable|numeric|min:0|max:4',
            'honors' => 'nullable|string|max:255',
            'thesis_title' => 'nullable|string|max:500',
            'organizations' => 'nullable|string',
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
            \App\Models\AlumniProfile::class,
            $profile->id
        );

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    // ===== EXPERIENCES (Same as Student) =====

    public function experiences()
    {
        $user = auth()->user();
        $experiences = Experience::where('user_id', $user->id)
            ->orderBy('start_date', 'desc')
            ->paginate(20);

        return view('users.alumni.experiences.index', ['experiences' => $experiences]);
    }

    public function createExperience()
    {
        return view('users.alumni.experiences.create');
    }

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
            'user_type' => 'alumni',
        ]));

        ActivityLog::logActivity(
            auth()->id(),
            'created',
            "Added experience: {$experience->role_position}",
            Experience::class,
            $experience->id
        );

        return redirect()->route('alumni.experiences.index')
            ->with('success', 'Experience added successfully.');
    }

    public function editExperience($id)
    {
        $user = auth()->user();
        $experience = Experience::where('user_id', $user->id)->findOrFail($id);

        return view('users.alumni.experiences.edit', ['experience' => $experience]);
    }

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

        return redirect()->route('alumni.experiences.index')
            ->with('success', 'Experience updated successfully.');
    }

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

        return redirect()->route('alumni.experiences.index')
            ->with('success', 'Experience deleted successfully.');
    }

    // ===== PROJECTS (Same as Student) =====

    public function projects()
    {
        $user = auth()->user();
        $projects = Project::where('user_id', $user->id)
            ->orderBy('start_date', 'desc')
            ->paginate(20);

        return view('users.alumni.projects.index', ['projects' => $projects]);
    }

    public function createProject()
    {
        return view('users.alumni.projects.create');
    }

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
            'user_type' => 'alumni',
        ]));

        ActivityLog::logActivity(
            auth()->id(),
            'created',
            "Added project: {$project->title}",
            Project::class,
            $project->id
        );

        return redirect()->route('alumni.projects.index')
            ->with('success', 'Project added successfully.');
    }

    public function editProject($id)
    {
        $user = auth()->user();
        $project = Project::where('user_id', $user->id)->findOrFail($id);

        return view('users.alumni.projects.edit', ['project' => $project]);
    }

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

        return redirect()->route('alumni.projects.index')
            ->with('success', 'Project updated successfully.');
    }

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

        return redirect()->route('alumni.projects.index')
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

        return view('users.alumni.news.index', ['articles' => $articles]);
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

        return view('users.alumni.news.show', ['article' => $article]);
    }

    // ===== NOTIFICATIONS =====

    /**
     * Show alumni notifications.
     */
    public function notifications()
    {
        $user = auth()->user();
        $notifications = Notification::where('user_id', $user->id)
            ->latest()
            ->paginate(50);

        return view('users.alumni.notifications.index', ['notifications' => $notifications]);
    }

    /**
     * Mark notification as read.
     */
    public function markNotificationAsRead($id)
    {
        $user = auth()->user();
        $notification = Notification::where('user_id', $user->id)->findOrFail($id);

        $notification->markAsRead();

        return redirect()->back()->with('success', 'Notification marked as read.');
    }
}
