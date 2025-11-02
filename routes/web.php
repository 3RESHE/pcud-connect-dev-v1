<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Dashboard\AdminDashboardController;
use App\Http\Controllers\Admin\Users\UserController;
use App\Http\Controllers\Admin\Departments\DepartmentController;
use App\Http\Controllers\Admin\Jobs\JobApprovalController;
use App\Http\Controllers\Admin\Events\EventApprovalController;
use App\Http\Controllers\Admin\News\NewsApprovalController;
use App\Http\Controllers\Admin\Partnerships\PartnershipApprovalController;
use App\Http\Controllers\Admin\Analytics\ActivityLogController;
use App\Http\Controllers\Admin\Analytics\ReportController;
use App\Http\Controllers\Dashboard\StaffDashboardController;
use App\Http\Controllers\Dashboard\PartnerDashboardController;
use App\Http\Controllers\Dashboard\StudentDashboardController;
use App\Http\Controllers\Dashboard\AlumniDashboardController;


// =====================================================
// PUBLIC ROUTES (No Authentication Required)
// =====================================================

Route::get('/', function () {
    return view('welcome');
})->name('home');


// =====================================================
// AUTHENTICATION ROUTES (from Laravel Breeze)
// =====================================================

require __DIR__ . '/auth.php';


// =====================================================
// AUTHENTICATED ROUTES (Require Auth + Password Changed + Active)
// =====================================================

Route::middleware(['auth', 'verified', 'password.changed', 'active'])->group(function () {

    // =====================================================
    // PASSWORD CHANGE ROUTE (First Login)
    // =====================================================

    Route::get('/change-password', function () {
        return view('auth.change-password');
    })->name('password.change');

    Route::post('/change-password', [AuthenticatedSessionController::class, 'updatePassword'])
        ->name('password.update');

    // =====================================================
    // REDIRECT ROOT TO APPROPRIATE DASHBOARD BY ROLE
    // =====================================================

    Route::get('/dashboard', function () {
        $user = auth()->user();

        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'staff' => redirect()->route('staff.dashboard'),
            'partner' => redirect()->route('partner.dashboard'),
            'student' => redirect()->route('student.dashboard'),
            'alumni' => redirect()->route('alumni.dashboard'),
            default => redirect()->route('home'),
        };
    })->name('dashboard');

    // =====================================================
    // ADMIN DASHBOARD ROUTES (REFACTORED)
    // =====================================================

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {

        // ===== DASHBOARD, PROFILE & SETTINGS =====
        Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])
            ->name('dashboard');

        Route::get('/profile', [AdminDashboardController::class, 'profile'])
            ->name('profile');
        Route::post('/profile', [AdminDashboardController::class, 'updateProfile'])
            ->name('profile.update');

        Route::get('/settings', [AdminDashboardController::class, 'settings'])
            ->name('settings');
        Route::post('/settings', [AdminDashboardController::class, 'updateSettings'])
            ->name('settings.update');

        // ===== USER MANAGEMENT (Separate Controller) =====
        Route::resource('users', UserController::class);
        Route::get('/users/bulk-import', [UserController::class, 'bulkImportForm'])
            ->name('users.bulk-import-form');
        Route::post('/users/bulk-import', [UserController::class, 'bulkImport'])
            ->name('users.bulk-import');

        // ===== DEPARTMENT MANAGEMENT (Separate Controller) =====
        Route::resource('departments', DepartmentController::class);

        // ===== JOB POSTINGS APPROVALS (Separate Controller) =====
        Route::prefix('approvals/jobs')->name('approvals.jobs.')->group(function () {
            Route::get('/', [JobApprovalController::class, 'index'])
                ->name('index');
            Route::post('/{id}/approve', [JobApprovalController::class, 'approve'])
                ->name('approve');
            Route::post('/{id}/reject', [JobApprovalController::class, 'reject'])
                ->name('reject');
        });

        // ===== EVENTS APPROVALS (Separate Controller) =====
        Route::prefix('approvals/events')->name('approvals.events.')->group(function () {
            Route::get('/', [EventApprovalController::class, 'index'])
                ->name('index');
            Route::post('/{id}/approve', [EventApprovalController::class, 'approve'])
                ->name('approve');
            Route::post('/{id}/reject', [EventApprovalController::class, 'reject'])
                ->name('reject');
        });

        // ===== NEWS ARTICLES APPROVALS (Separate Controller) =====
        Route::prefix('approvals/news')->name('approvals.news.')->group(function () {
            Route::get('/', [NewsApprovalController::class, 'index'])
                ->name('index');
            Route::post('/{id}/approve', [NewsApprovalController::class, 'approve'])
                ->name('approve');
            Route::post('/{id}/reject', [NewsApprovalController::class, 'reject'])
                ->name('reject');
        });

        // ===== PARTNERSHIPS APPROVALS (Separate Controller) =====
        Route::prefix('approvals/partnerships')->name('approvals.partnerships.')->group(function () {
            Route::get('/', [PartnershipApprovalController::class, 'index'])
                ->name('index');
            Route::post('/{id}/approve', [PartnershipApprovalController::class, 'approve'])
                ->name('approve');
            Route::post('/{id}/reject', [PartnershipApprovalController::class, 'reject'])
                ->name('reject');
        });

        // ===== ACTIVITY LOGS & REPORTS (Separate Controllers) =====
        Route::get('/activity-logs', [ActivityLogController::class, 'index'])
            ->name('activity-logs');

        Route::get('/reports', [ReportController::class, 'index'])
            ->name('reports');
    });

    // =====================================================
    // STAFF DASHBOARD ROUTES (Keep as-is for now)
    // =====================================================

    Route::middleware('role:staff')->prefix('staff')->name('staff.')->group(function () {

        // Dashboard
        Route::get('/dashboard', [StaffDashboardController::class, 'dashboard'])
            ->name('dashboard');

        // Event Management
        Route::get('/events', [StaffDashboardController::class, 'events'])
            ->name('events.index');
        Route::get('/events/create', [StaffDashboardController::class, 'createEvent'])
            ->name('events.create');
        Route::post('/events', [StaffDashboardController::class, 'storeEvent'])
            ->name('events.store');
        Route::get('/events/{id}/edit', [StaffDashboardController::class, 'editEvent'])
            ->name('events.edit');
        Route::put('/events/{id}', [StaffDashboardController::class, 'updateEvent'])
            ->name('events.update');
        Route::delete('/events/{id}', [StaffDashboardController::class, 'deleteEvent'])
            ->name('events.destroy');
        Route::get('/events/{id}/registrations', [StaffDashboardController::class, 'eventRegistrations'])
            ->name('events.registrations');
        Route::post('/events/{id}/check-in', [StaffDashboardController::class, 'checkInAttendee'])
            ->name('events.check-in');

        // News Management
        Route::get('/news', [StaffDashboardController::class, 'news'])
            ->name('news.index');
        Route::get('/news/create', [StaffDashboardController::class, 'createNews'])
            ->name('news.create');
        Route::post('/news', [StaffDashboardController::class, 'storeNews'])
            ->name('news.store');
        Route::get('/news/{id}/edit', [StaffDashboardController::class, 'editNews'])
            ->name('news.edit');
        Route::put('/news/{id}', [StaffDashboardController::class, 'updateNews'])
            ->name('news.update');
        Route::delete('/news/{id}', [StaffDashboardController::class, 'deleteNews'])
            ->name('news.destroy');
    });

    // =====================================================
    // PARTNER DASHBOARD ROUTES (Keep as-is for now)
    // =====================================================

    Route::middleware('role:partner')->prefix('partner')->name('partner.')->group(function () {

        // Dashboard
        Route::get('/dashboard', [PartnerDashboardController::class, 'dashboard'])
            ->name('dashboard');

        // Job Posting Management
        Route::get('/jobs', [PartnerDashboardController::class, 'jobs'])
            ->name('jobs.index');
        Route::get('/jobs/create', [PartnerDashboardController::class, 'createJob'])
            ->name('jobs.create');
        Route::post('/jobs', [PartnerDashboardController::class, 'storeJob'])
            ->name('jobs.store');
        Route::get('/jobs/{id}/edit', [PartnerDashboardController::class, 'editJob'])
            ->name('jobs.edit');
        Route::put('/jobs/{id}', [PartnerDashboardController::class, 'updateJob'])
            ->name('jobs.update');
        Route::delete('/jobs/{id}', [PartnerDashboardController::class, 'deleteJob'])
            ->name('jobs.destroy');
        Route::get('/jobs/{id}/applications', [PartnerDashboardController::class, 'jobApplications'])
            ->name('jobs.applications');
        Route::post('/jobs/{id}/applications/{appId}/approve', [PartnerDashboardController::class, 'approveApplication'])
            ->name('jobs.applications.approve');
        Route::post('/jobs/{id}/applications/{appId}/reject', [PartnerDashboardController::class, 'rejectApplication'])
            ->name('jobs.applications.reject');

        // Partnership Submissions
        Route::get('/partnerships', [PartnerDashboardController::class, 'partnerships'])
            ->name('partnerships.index');
        Route::get('/partnerships/create', [PartnerDashboardController::class, 'createPartnership'])
            ->name('partnerships.create');
        Route::post('/partnerships', [PartnerDashboardController::class, 'storePartnership'])
            ->name('partnerships.store');
        Route::get('/partnerships/{id}/edit', [PartnerDashboardController::class, 'editPartnership'])
            ->name('partnerships.edit');
        Route::put('/partnerships/{id}', [PartnerDashboardController::class, 'updatePartnership'])
            ->name('partnerships.update');
        Route::delete('/partnerships/{id}', [PartnerDashboardController::class, 'deletePartnership'])
            ->name('partnerships.destroy');

        // Company Profile
        Route::get('/profile', [PartnerDashboardController::class, 'profile'])
            ->name('profile');
        Route::put('/profile', [PartnerDashboardController::class, 'updateProfile'])
            ->name('profile.update');
    });

    // =====================================================
    // STUDENT DASHBOARD ROUTES (Keep as-is for now)
    // =====================================================

    Route::middleware('role:student')->prefix('student')->name('student.')->group(function () {

        // Dashboard
        Route::get('/dashboard', [StudentDashboardController::class, 'dashboard'])
            ->name('dashboard');

        // Job Browsing & Application
        Route::get('/jobs', [StudentDashboardController::class, 'jobs'])
            ->name('jobs.index');
        Route::get('/jobs/{id}', [StudentDashboardController::class, 'viewJob'])
            ->name('jobs.show');
        Route::post('/jobs/{id}/apply', [StudentDashboardController::class, 'applyJob'])
            ->name('jobs.apply');
        Route::get('/applications', [StudentDashboardController::class, 'applications'])
            ->name('applications.index');
        Route::get('/applications/{id}', [StudentDashboardController::class, 'viewApplication'])
            ->name('applications.show');

        // Event Registration
        Route::get('/events', [StudentDashboardController::class, 'events'])
            ->name('events.index');
        Route::get('/events/{id}', [StudentDashboardController::class, 'viewEvent'])
            ->name('events.show');
        Route::post('/events/{id}/register', [StudentDashboardController::class, 'registerEvent'])
            ->name('events.register');
        Route::get('/event-registrations', [StudentDashboardController::class, 'eventRegistrations'])
            ->name('event-registrations.index');

        // Profile & Portfolio
        Route::get('/profile', [StudentDashboardController::class, 'profile'])
            ->name('profile');
        Route::put('/profile', [StudentDashboardController::class, 'updateProfile'])
            ->name('profile.update');

        // Experiences
        Route::get('/experiences', [StudentDashboardController::class, 'experiences'])
            ->name('experiences.index');
        Route::get('/experiences/create', [StudentDashboardController::class, 'createExperience'])
            ->name('experiences.create');
        Route::post('/experiences', [StudentDashboardController::class, 'storeExperience'])
            ->name('experiences.store');
        Route::get('/experiences/{id}/edit', [StudentDashboardController::class, 'editExperience'])
            ->name('experiences.edit');
        Route::put('/experiences/{id}', [StudentDashboardController::class, 'updateExperience'])
            ->name('experiences.update');
        Route::delete('/experiences/{id}', [StudentDashboardController::class, 'deleteExperience'])
            ->name('experiences.destroy');

        // Projects
        Route::get('/projects', [StudentDashboardController::class, 'projects'])
            ->name('projects.index');
        Route::get('/projects/create', [StudentDashboardController::class, 'createProject'])
            ->name('projects.create');
        Route::post('/projects', [StudentDashboardController::class, 'storeProject'])
            ->name('projects.store');
        Route::get('/projects/{id}/edit', [StudentDashboardController::class, 'editProject'])
            ->name('projects.edit');
        Route::put('/projects/{id}', [StudentDashboardController::class, 'updateProject'])
            ->name('projects.update');
        Route::delete('/projects/{id}', [StudentDashboardController::class, 'deleteProject'])
            ->name('projects.destroy');

        // News Feed
        Route::get('/news', [StudentDashboardController::class, 'news'])
            ->name('news.index');
        Route::get('/news/{id}', [StudentDashboardController::class, 'viewNews'])
            ->name('news.show');
    });

    // =====================================================
    // ALUMNI DASHBOARD ROUTES (Keep as-is for now)
    // =====================================================

    Route::middleware('role:alumni')->prefix('alumni')->name('alumni.')->group(function () {

        // Dashboard
        Route::get('/dashboard', [AlumniDashboardController::class, 'dashboard'])
            ->name('dashboard');

        // Job Browsing & Application
        Route::get('/jobs', [AlumniDashboardController::class, 'jobs'])
            ->name('jobs.index');
        Route::get('/jobs/{id}', [AlumniDashboardController::class, 'viewJob'])
            ->name('jobs.show');
        Route::post('/jobs/{id}/apply', [AlumniDashboardController::class, 'applyJob'])
            ->name('jobs.apply');
        Route::get('/applications', [AlumniDashboardController::class, 'applications'])
            ->name('applications.index');
        Route::get('/applications/{id}', [AlumniDashboardController::class, 'viewApplication'])
            ->name('applications.show');

        // Event Registration
        Route::get('/events', [AlumniDashboardController::class, 'events'])
            ->name('events.index');
        Route::get('/events/{id}', [AlumniDashboardController::class, 'viewEvent'])
            ->name('events.show');
        Route::post('/events/{id}/register', [AlumniDashboardController::class, 'registerEvent'])
            ->name('events.register');
        Route::get('/event-registrations', [AlumniDashboardController::class, 'eventRegistrations'])
            ->name('event-registrations.index');

        // Profile & Portfolio
        Route::get('/profile', [AlumniDashboardController::class, 'profile'])
            ->name('profile');
        Route::put('/profile', [AlumniDashboardController::class, 'updateProfile'])
            ->name('profile.update');

        // Experiences
        Route::get('/experiences', [AlumniDashboardController::class, 'experiences'])
            ->name('experiences.index');
        Route::get('/experiences/create', [AlumniDashboardController::class, 'createExperience'])
            ->name('experiences.create');
        Route::post('/experiences', [AlumniDashboardController::class, 'storeExperience'])
            ->name('experiences.store');
        Route::get('/experiences/{id}/edit', [AlumniDashboardController::class, 'editExperience'])
            ->name('experiences.edit');
        Route::put('/experiences/{id}', [AlumniDashboardController::class, 'updateExperience'])
            ->name('experiences.update');
        Route::delete('/experiences/{id}', [AlumniDashboardController::class, 'deleteExperience'])
            ->name('experiences.destroy');

        // Projects
        Route::get('/projects', [AlumniDashboardController::class, 'projects'])
            ->name('projects.index');
        Route::get('/projects/create', [AlumniDashboardController::class, 'createProject'])
            ->name('projects.create');
        Route::post('/projects', [AlumniDashboardController::class, 'storeProject'])
            ->name('projects.store');
        Route::get('/projects/{id}/edit', [AlumniDashboardController::class, 'editProject'])
            ->name('projects.edit');
        Route::put('/projects/{id}', [AlumniDashboardController::class, 'updateProject'])
            ->name('projects.update');
        Route::delete('/projects/{id}', [AlumniDashboardController::class, 'deleteProject'])
            ->name('projects.destroy');

        // News Feed
        Route::get('/news', [AlumniDashboardController::class, 'news'])
            ->name('news.index');
        Route::get('/news/{id}', [AlumniDashboardController::class, 'viewNews'])
            ->name('news.show');

        // Notifications
        Route::get('/notifications', [AlumniDashboardController::class, 'notifications'])
            ->name('notifications.index');
        Route::post('/notifications/{id}/read', [AlumniDashboardController::class, 'markNotificationAsRead'])
            ->name('notifications.read');
    });
});
