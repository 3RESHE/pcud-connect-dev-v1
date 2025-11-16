<?php

namespace App\Http\Controllers\Alumni;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\JobApplication;
use App\Models\JobPosting;
use App\Models\NewsArticle;
use App\Models\Experience;
use App\Models\Project;
use App\Models\AlumniProfile;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the alumni dashboard with comprehensive analytics
     */
    public function dashboard(): View
    {
        $user = auth()->user();

        // ===== SECTION 1: PROFILE COMPLETION STATUS =====
        try {
            $alumniProfile = AlumniProfile::firstOrCreate(['user_id' => $user->id]);
            $profileCompletionPercentage = $alumniProfile->getProfileCompletionPercentage() ?? 0;
            $isProfileComplete = $alumniProfile->isProfileComplete();
        } catch (\Exception $e) {
            \Log::warning('Profile completion error: ' . $e->getMessage());
            $profileCompletionPercentage = 0;
            $isProfileComplete = false;
            $alumniProfile = null;
        }

        // ===== SECTION 2: ACTIVE JOB APPLICATIONS WITH STATUS BREAKDOWN =====
        try {
            $totalApplications = JobApplication::where('applicant_id', $user->id)
                ->where('applicant_type', 'alumni')
                ->count();

            $activeApplications = JobApplication::where('applicant_id', $user->id)
                ->where('applicant_type', 'alumni')
                ->whereIn('status', ['pending', 'contacted'])
                ->count();

            $approvedApplications = JobApplication::where('applicant_id', $user->id)
                ->where('applicant_type', 'alumni')
                ->where('status', 'approved')
                ->count();

            $rejectedApplications = JobApplication::where('applicant_id', $user->id)
                ->where('applicant_type', 'alumni')
                ->where('status', 'rejected')
                ->count();

            $applicationStats = [
                'total' => $totalApplications,
                'active' => $activeApplications,
                'approved' => $approvedApplications,
                'rejected' => $rejectedApplications,
                'success_rate' => $totalApplications > 0
                    ? round(($approvedApplications / $totalApplications) * 100, 1)
                    : 0,
            ];
        } catch (\Exception $e) {
            \Log::warning('Application stats error: ' . $e->getMessage());
            $applicationStats = [
                'total' => 0,
                'active' => 0,
                'approved' => 0,
                'rejected' => 0,
                'success_rate' => 0,
            ];
        }

        // ===== SECTION 3: EXPERIENCE & PROJECTS =====
        try {
            $experienceCount = Experience::where('user_id', $user->id)
                ->where('user_type', 'alumni')
                ->count();

            $projectCount = Project::where('user_id', $user->id)
                ->where('user_type', 'alumni')
                ->count();

            $recentExperiences = Experience::where('user_id', $user->id)
                ->where('user_type', 'alumni')
                ->orderByDesc('is_current')
                ->orderByDesc('start_date')
                ->take(3)
                ->get();

            $recentProjects = Project::where('user_id', $user->id)
                ->where('user_type', 'alumni')
                ->orderByDesc('start_date')
                ->take(3)
                ->get();
        } catch (\Exception $e) {
            \Log::warning('Experience/Projects error: ' . $e->getMessage());
            $experienceCount = 0;
            $projectCount = 0;
            $recentExperiences = collect();
            $recentProjects = collect();
        }

        // ===== SECTION 4: EVENT ENGAGEMENT =====
        try {
            $registeredEventsCount = EventRegistration::where('user_id', $user->id)
                ->whereHas('event', fn($q) => $q->where('status', 'published'))
                ->count();

            $upcomingRegistered = EventRegistration::where('user_id', $user->id)
                ->whereHas('event', fn($q) =>
                    $q->where('status', 'published')
                        ->where('event_date', '>=', Carbon::now())
                )
                ->count();

            $availableJobsCount = JobPosting::where('status', 'approved')
                ->where('sub_status', 'active')
                ->count();

            $upcomingEventsCount = Event::where('status', 'published')
                ->where('event_date', '>=', Carbon::now())
                ->count();
        } catch (\Exception $e) {
            \Log::warning('Event stats error: ' . $e->getMessage());
            $registeredEventsCount = 0;
            $upcomingRegistered = 0;
            $availableJobsCount = 0;
            $upcomingEventsCount = 0;
        }

        // ===== SECTION 5: LATEST NEWS =====
        try {
            $latestNewsCount = NewsArticle::where('status', 'published')->count();
            $latestNews = NewsArticle::where('status', 'published')
                ->orderBy('created_at', 'desc')
                ->limit(6)
                ->get();
        } catch (\Exception $e) {
            \Log::warning('News error: ' . $e->getMessage());
            $latestNewsCount = 0;
            $latestNews = collect();
        }

        // ===== SECTION 6: RECENT JOB POSTINGS =====
        try {
            $recentJobs = JobPosting::where('status', 'approved')
                ->where('sub_status', 'active')
                ->orderBy('created_at', 'desc')
                ->limit(4)
                ->get();
        } catch (\Exception $e) {
            \Log::warning('Recent jobs error: ' . $e->getMessage());
            $recentJobs = collect();
        }

        // ===== SECTION 7: UPCOMING EVENTS =====
        try {
            $upcomingEvents = Event::where('status', 'published')
                ->where('event_date', '>=', Carbon::now())
                ->orderBy('event_date', 'asc')
                ->limit(3)
                ->get();
        } catch (\Exception $e) {
            \Log::warning('Upcoming events error: ' . $e->getMessage());
            $upcomingEvents = collect();
        }

        // ===== SECTION 8: RECENT APPLICATIONS =====
        try {
            $recentApplications = JobApplication::where('applicant_id', $user->id)
                ->where('applicant_type', 'alumni')
                ->with('jobPosting')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        } catch (\Exception $e) {
            \Log::warning('Recent applications error: ' . $e->getMessage());
            $recentApplications = collect();
        }

        // ===== SECTION 9: ALERTS & NOTIFICATIONS =====
        $alerts = [
            'incomplete_profile' => !$isProfileComplete,
            'profile_completion_percentage' => $profileCompletionPercentage,
            'pending_applications' => $applicationStats['active'],
            'job_opportunities' => $availableJobsCount,
            'upcoming_events' => $upcomingEventsCount,
        ];

        return view('users.alumni.dashboard', [
            // Profile
            'alumniProfile' => $alumniProfile,
            'profileCompletionPercentage' => $profileCompletionPercentage,
            'isProfileComplete' => $isProfileComplete,

            // Applications
            'applicationStats' => $applicationStats,
            'recentApplications' => $recentApplications,

            // Experience & Projects
            'experienceCount' => $experienceCount,
            'projectCount' => $projectCount,
            'recentExperiences' => $recentExperiences,
            'recentProjects' => $recentProjects,

            // Events
            'registeredEventsCount' => $registeredEventsCount,
            'upcomingRegistered' => $upcomingRegistered,
            'upcomingEventsCount' => $upcomingEventsCount,
            'upcomingEvents' => $upcomingEvents,

            // Jobs
            'availableJobsCount' => $availableJobsCount,
            'recentJobs' => $recentJobs,

            // News
            'latestNewsCount' => $latestNewsCount,
            'latestNews' => $latestNews,

            // Alerts
            'alerts' => $alerts,
        ]);
    }

    /**
     * Get application statistics (AJAX)
     */
    public function applicationStats()
    {
        try {
            $user = auth()->user();

            $stats = [
                'total' => JobApplication::where('applicant_id', $user->id)
                    ->where('applicant_type', 'alumni')
                    ->count(),
                'pending' => JobApplication::where('applicant_id', $user->id)
                    ->where('applicant_type', 'alumni')
                    ->where('status', 'pending')
                    ->count(),
                'contacted' => JobApplication::where('applicant_id', $user->id)
                    ->where('applicant_type', 'alumni')
                    ->where('status', 'contacted')
                    ->count(),
                'approved' => JobApplication::where('applicant_id', $user->id)
                    ->where('applicant_type', 'alumni')
                    ->where('status', 'approved')
                    ->count(),
                'rejected' => JobApplication::where('applicant_id', $user->id)
                    ->where('applicant_type', 'alumni')
                    ->where('status', 'rejected')
                    ->count(),
            ];

            return response()->json(['success' => true, 'data' => $stats]);
        } catch (\Exception $e) {
            \Log::error('Application stats error: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get profile completion details (AJAX)
     */
    public function profileCompletion()
    {
        try {
            $user = auth()->user();
            $profile = AlumniProfile::firstOrCreate(['user_id' => $user->id]);

            return response()->json([
                'success' => true,
                'completion_percentage' => $profile->getProfileCompletionPercentage(),
                'is_complete' => $profile->isProfileComplete(),
                'missing_fields' => $this->getMissingFields($profile),
            ]);
        } catch (\Exception $e) {
            \Log::error('Profile completion error: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get missing profile fields
     */
    private function getMissingFields($profile)
    {
        $missing = [];
        $requiredFields = [
            'headline' => 'Professional Headline',
            'personal_email' => 'Personal Email',
            'phone' => 'Phone Number',
            'current_location' => 'Current Location',
            'degree_program' => 'Degree Program',
            'graduation_year' => 'Graduation Year',
            'current_organization' => 'Current Organization',
            'current_position' => 'Current Position',
            'professional_summary' => 'Professional Summary',
        ];

        foreach ($requiredFields as $field => $label) {
            if (empty($profile->$field)) {
                $missing[] = $label;
            }
        }

        return $missing;
    }
}
