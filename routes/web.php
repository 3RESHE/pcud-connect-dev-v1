<?php

use App\Http\Controllers\Account\PasswordManagementController;
use App\Http\Controllers\Admin\Analytics\ActivityLogController;
use App\Http\Controllers\Admin\Analytics\ReportController;
use App\Http\Controllers\Admin\Departments\DepartmentController;
use App\Http\Controllers\Admin\Events\EventApprovalController;
use App\Http\Controllers\Admin\Jobs\JobApprovalController;
use App\Http\Controllers\Admin\News\NewsApprovalController;
use App\Http\Controllers\Admin\Partnerships\PartnershipApprovalController;
use App\Http\Controllers\Admin\Users\UserController;
use App\Http\Controllers\Alumni\AlumniExperienceController;
use App\Http\Controllers\Alumni\AlumniJobController;
use App\Http\Controllers\Alumni\AlumniProfileController;
use App\Http\Controllers\Alumni\AlumniProjectController;
use App\Http\Controllers\Alumni\DashboardController as AlumniDashboardController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Dashboard\AdminDashboardController;
use App\Http\Controllers\Dashboard\StaffDashboardController;
use App\Http\Controllers\Partner\ApplicationController;
use App\Http\Controllers\Partner\JobPostingController;
use App\Http\Controllers\Partner\PartnerDashboardController;
use App\Http\Controllers\Partner\PartnershipController;
use App\Http\Controllers\Partner\ProfileController as PartnerProfileController;
use App\Http\Controllers\Partner\SettingsController as PartnerSettingsController;
use App\Http\Controllers\Shared\EventController as SharedEventController;
use App\Http\Controllers\Staff\Events\EventAttendanceController;
use App\Http\Controllers\Staff\Events\EventController;
use App\Http\Controllers\Staff\Events\EventRegistrationController;
use App\Http\Controllers\Staff\Jobs\StaffJobController;
use App\Http\Controllers\Staff\News\NewsController as StaffNewsController;
use App\Http\Controllers\Staff\ProfileController;
use App\Http\Controllers\Student\DashboardController;
use App\Http\Controllers\Student\ExperienceController;
use App\Http\Controllers\Student\ProjectController;
use App\Http\Controllers\Student\StudentJobController;
use App\Http\Controllers\Student\StudentProfileController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;





// =====================================================
// PUBLIC ROUTES (No Authentication Required)
// =====================================================

Route::middleware('guest')->group(function () {
    Route::get('/', [WelcomeController::class, 'index'])->name('home');
});

// =====================================================
// AUTHENTICATION ROUTES (from Laravel Breeze)
// =====================================================
require __DIR__ . '/auth.php';

// =====================================================
// CUSTOM PASSWORD MANAGEMENT ROUTES (First Login & Account Settings)
// =====================================================

Route::middleware(['auth'])->group(function () {
    // First Login - Forced Password Change (for new users)
    Route::get('/setup-password', [PasswordManagementController::class, 'showChangePassword'])
        ->name('password.change-first');
    Route::post('/setup-password', [PasswordManagementController::class, 'updateChangePassword'])
        ->name('password.update-first');

    // Account Settings - Change Password (for existing users)
    Route::get('/account/security/password', [PasswordManagementController::class, 'showUpdatePassword'])
        ->name('account.password.change');
    Route::post('/account/security/password', [PasswordManagementController::class, 'updatePassword'])
        ->name('account.password.update');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// =====================================================
// AUTHENTICATED ROUTES (Require Auth + Email Verified + Password Changed + Active)
// =====================================================

Route::middleware(['auth', 'verified', 'password.changed', 'active'])->group(function () {

    // =====================================================
    // REDIRECT ROOT TO APPROPRIATE DASHBOARD BY ROLE
    // =====================================================

    Route::middleware('role:student,alumni,partner')->prefix('news')->name('news.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Shared\NewsController::class, 'index'])->name('index');
        Route::get('/{newsArticle}', [\App\Http\Controllers\Shared\NewsController::class, 'show'])->name('show');
    });

    // =====================================================
    // SHARED EVENTS ROUTES (Alumni & Student)
    // =====================================================

    Route::middleware('role:student,alumni')->prefix('events')->name('events.')->group(function () {
        Route::get('/', [SharedEventController::class, 'index'])->name('index');
        Route::get('/registrations', [SharedEventController::class, 'myRegistrations'])->name('myRegistrations');
        Route::get('/{event}', [SharedEventController::class, 'show'])->name('show');
        Route::post('/{event}/register', [SharedEventController::class, 'register'])->name('register');
        Route::delete('/{event}/unregister', [SharedEventController::class, 'unregister'])->name('unregister');
    });



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
    // LOGOUT ROUTE
    // =====================================================

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    // =====================================================
    // ADMIN DASHBOARD ROUTES
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

        // ===== USER MANAGEMENT =====
        Route::prefix('users')->name('users.')->group(function () {

            // Bulk Import Routes
            Route::get('/bulk-import', [UserController::class, 'bulkImportForm'])->name('bulk-import-form');
            Route::post('/bulk-import', [UserController::class, 'bulkImport'])->name('bulk-import');
            Route::get('/template/download/{type}', [UserController::class, 'downloadTemplate'])->name('download-template');

            Route::get('/', [UserController::class, 'index'])->name('index');
            Route::get('/all', [UserController::class, 'getAll'])->name('all');
            Route::get('/stats', [UserController::class, 'getStats'])->name('stats');
            Route::post('/', [UserController::class, 'store'])->name('store');
            Route::get('/{user}', [UserController::class, 'show'])->name('show');
            Route::put('/{user}', [UserController::class, 'update'])->name('update');
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
            // In the admin users routes section
            Route::post('/bulk-update-status', [UserController::class, 'bulkUpdateStatus'])->name('bulk-update-status');
        });



        // ===== DEPARTMENT MANAGEMENT =====
        Route::prefix('departments')->name('departments.')->group(function () {
            Route::get('/', [DepartmentController::class, 'index'])
                ->name('index');
            Route::get('/all', [DepartmentController::class, 'getAll'])
                ->name('all');
            Route::post('/', [DepartmentController::class, 'store'])
                ->name('store');
            Route::get('/{department}', [DepartmentController::class, 'show'])
                ->name('show');
            Route::put('/{department}', [DepartmentController::class, 'update'])
                ->name('update');
            Route::delete('/{department}', [DepartmentController::class, 'destroy'])
                ->name('destroy');
            Route::get('/search', [DepartmentController::class, 'search'])
                ->name('search');


            // ✅ NEW: Export routes
            Route::get('/{department}/export-students', [DepartmentController::class, 'exportStudents'])
                ->name('export-students');
            Route::get('/export-all-summary', [DepartmentController::class, 'exportAll'])
                ->name('export-all');
        });

        // ===== JOB POSTINGS APPROVALS =====
        Route::prefix('approvals/jobs')->name('approvals.jobs.')->group(function () {
            Route::get('/', [JobApprovalController::class, 'index'])
                ->name('index');
            Route::get('/{id}', [JobApprovalController::class, 'show'])
                ->name('show');
            Route::post('/{id}/approve', [JobApprovalController::class, 'approve'])
                ->name('approve');
            Route::post('/{id}/reject', [JobApprovalController::class, 'reject'])
                ->name('reject');
        });

        // ===== JOB MANAGEMENT (Feature, Unfeature, Unpublish) =====
        Route::prefix('jobs')->name('jobs.')->middleware('role:admin')->group(function () {
            Route::put('/{id}/feature', [JobApprovalController::class, 'feature'])
                ->name('feature');
            Route::put('/{id}/unfeature', [JobApprovalController::class, 'unfeature'])
                ->name('unfeature');
            Route::put('/{id}/unpublish', [JobApprovalController::class, 'unpublish'])
                ->name('unpublish');
        });

        Route::prefix('approvals/events')->name('approvals.events.')->group(function () {
            Route::get('/', [EventApprovalController::class, 'index'])->name('index');
            Route::get('/{id}', [EventApprovalController::class, 'show'])->name('show');
            Route::post('/{id}/approve', [EventApprovalController::class, 'approve'])->name('approve');
            Route::post('/{id}/reject', [EventApprovalController::class, 'reject'])->name('reject');
            Route::post('/{id}/change-status', [EventApprovalController::class, 'changeStatus'])->name('change-status');
            Route::post('/{id}/unpublish', [EventApprovalController::class, 'unpublish'])->name('unpublish');
        });

        // ===== EVENT MANAGEMENT (Feature, Unfeature) =====
        Route::prefix('events')->name('events.')->middleware('role:admin')->group(function () {
            Route::post('/{id}/feature', [EventApprovalController::class, 'feature'])
                ->name('feature');
            Route::post('/{id}/unfeature', [EventApprovalController::class, 'unfeature'])
                ->name('unfeature');
        });

        // News Approval Routes
        Route::prefix('approvals/news')->name('approvals.news.')->group(function () {
            Route::get('/', [NewsApprovalController::class, 'index'])->name('index');
            Route::get('/{newsArticle}', [NewsApprovalController::class, 'show'])->name('show');
            Route::post('/{newsArticle}/approve', [NewsApprovalController::class, 'approve'])->name('approve');
            Route::post('/{newsArticle}/reject', [NewsApprovalController::class, 'reject'])->name('reject');
            Route::post('/{newsArticle}/feature', [NewsApprovalController::class, 'feature'])->name('feature');
            Route::post('/{newsArticle}/unfeature', [NewsApprovalController::class, 'unfeature'])->name('unfeature');
        });

        // ===== PARTNERSHIPS APPROVALS =====
        Route::prefix('approvals/partnerships')->name('approvals.partnerships.')->group(function () {
            Route::get('/', [PartnershipApprovalController::class, 'index'])
                ->name('index');
            Route::get('/{id}', [PartnershipApprovalController::class, 'show'])
                ->name('show');
            Route::post('/{id}/move-to-discussion', [PartnershipApprovalController::class, 'moveToDiscussion'])
                ->name('move-to-discussion');
            Route::post('/{id}/approve', [PartnershipApprovalController::class, 'approve'])
                ->name('approve');
            Route::post('/{id}/reject', [PartnershipApprovalController::class, 'reject'])
                ->name('reject');
            Route::post('/{id}/mark-complete', [PartnershipApprovalController::class, 'markComplete'])
                ->name('mark-complete');
        });

        // Activity Logs
        Route::get('/activity-logs', [ActivityLogController::class, 'index'])
            ->name('activity-logs');
        Route::get('/activity-logs/{log}', [ActivityLogController::class, 'show'])
            ->name('activity-logs.show');
        Route::post('/activity-logs/export', [ActivityLogController::class, 'export'])
            ->name('activity-logs.export');

        // ===== REPORTS & EXPORTS =====
        Route::get('/reports', [ReportController::class, 'index'])
            ->name('reports');
        Route::get('/reports/export-excel', [ReportController::class, 'exportExcel'])
            ->name('reports.export-excel');
        Route::get('/reports/export-applications', [ReportController::class, 'exportApplicationsExcel'])
            ->name('reports.export-applications');

        // AJAX endpoints for charts
        Route::get('/reports/user-growth', [ReportController::class, 'getUserGrowth'])
            ->name('reports.user-growth');
        Route::get('/reports/approval-stats', [ReportController::class, 'getApprovalStats'])
            ->name('reports.approval-stats');
        Route::get('/reports/activity-heatmap', [ReportController::class, 'getActivityHeatmap'])
            ->name('reports.activity-heatmap');
        Route::get('/reports/content-distribution', [ReportController::class, 'getContentDistribution'])
            ->name('reports.content-distribution');


        // AJAX endpoints for charts
        Route::get('/reports/user-growth', [ReportController::class, 'getUserGrowth'])
            ->name('reports.user-growth');
        Route::get('/reports/approval-stats', [ReportController::class, 'getApprovalStats'])
            ->name('reports.approval-stats');
        Route::get('/reports/activity-heatmap', [ReportController::class, 'getActivityHeatmap'])
            ->name('reports.activity-heatmap');
        Route::get('/reports/content-distribution', [ReportController::class, 'getContentDistribution'])
            ->name('reports.content-distribution');
    });

    // =====================================================
    // STAFF DASHBOARD ROUTES
    // =====================================================

    Route::middleware('role:staff')->prefix('staff')->name('staff.')->group(function () {
        // DASHBOARD


        Route::get('/dashboard', [StaffDashboardController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

        // JOBS ROUTES (index only - no show, details are inline)
        Route::prefix('jobs')->name('jobs.')->group(function () {
            Route::get('/', [StaffJobController::class, 'index'])->name('index');
        });

        // EVENT MANAGEMENT
        Route::prefix('events')->name('events.')->group(function () {
            // Basic CRUD
            Route::get('/', [EventController::class, 'index'])->name('index');
            Route::get('/create', [EventController::class, 'create'])->name('create');
            Route::post('/', [EventController::class, 'store'])->name('store');
            Route::get('/{event}', [EventController::class, 'show'])->name('show');
            Route::get('/{event}/edit', [EventController::class, 'edit'])->name('edit');
            Route::put('/{event}', [EventController::class, 'update'])->name('update');
            Route::delete('/{event}', [EventController::class, 'destroy'])->name('destroy');

            // Status Actions
            Route::post('/{event}/publish', [EventController::class, 'publish'])->name('publish');
            Route::post('/{event}/mark-ongoing', [EventController::class, 'markOngoing'])->name('mark-ongoing');
            Route::post('/{event}/mark-completed', [EventController::class, 'markCompleted'])->name('mark-completed');

            // REGISTRATION MANAGEMENT (NEW CONTROLLER)
            Route::prefix('{event}/registrations')->name('registrations.')->group(function () {
                Route::get('/', [EventRegistrationController::class, 'index'])->name('index');
                Route::get('/export', [EventRegistrationController::class, 'export'])->name('export');
                Route::post('/send-email', [EventRegistrationController::class, 'sendEmail'])->name('send-email');
                Route::get('/{registration}', [EventRegistrationController::class, 'show'])->name('show');
                Route::post('/{registration}/confirm', [EventRegistrationController::class, 'confirm'])->name('confirm');
                Route::post('/{registration}/cancel', [EventRegistrationController::class, 'cancel'])->name('cancel');
            });

            // ✅ ATTENDANCE MANAGEMENT
            Route::prefix('{event}/attendance')->name('attendance.')->group(function () {
                // Mark attendance (ongoing events)
                Route::get('/mark', [EventAttendanceController::class, 'markAttendance'])->name('mark');
                Route::post('/mark', [EventAttendanceController::class, 'storeWalkin'])->name('store'); // ✅ NEW
                Route::put('/{registration}', [EventAttendanceController::class, 'updateAttendance'])->name('update');
                Route::post('/bulk-update', [EventAttendanceController::class, 'bulkUpdateAttendance'])->name('bulk-update');

                // View attendance (completed events)
                Route::get('/view', [EventAttendanceController::class, 'viewAttendance'])->name('view');
                Route::get('/export', [EventAttendanceController::class, 'exportAttendance'])->name('export');

                // API endpoint for statistics
                Route::get('/statistics', [EventAttendanceController::class, 'getStatistics'])->name('statistics');
            });
        });

        // NEWS MANAGEMENT (keep unchanged)
        Route::prefix('news')->name('news.')->group(function () {
            Route::get('/', [StaffNewsController::class, 'index'])->name('index');
            Route::get('/create', [StaffNewsController::class, 'create'])->name('create');
            Route::post('/', [StaffNewsController::class, 'store'])->name('store');
            Route::get('/{newsArticle}', [StaffNewsController::class, 'show'])->name('show');
            Route::get('/{newsArticle}/edit', [StaffNewsController::class, 'edit'])->name('edit');
            Route::put('/{newsArticle}', [StaffNewsController::class, 'update'])->name('update');
            Route::post('/{newsArticle}/submit', [StaffNewsController::class, 'submit'])->name('submit');
            Route::post('/{newsArticle}/withdraw', [StaffNewsController::class, 'withdraw'])->name('withdraw');
            Route::post('/{newsArticle}/publish', [StaffNewsController::class, 'publish'])->name('publish');
            Route::delete('/{newsArticle}', [StaffNewsController::class, 'destroy'])->name('destroy');
        });
    });


    // =====================================================
    // PARTNER DASHBOARD ROUTES
    // =====================================================

    Route::middleware('role:partner')->prefix('partner')->name('partner.')->group(function () {


        Route::get('/dashboard', [PartnerDashboardController::class, 'index'])
            ->name('dashboard');
        // ===== DASHBOARD =====
        Route::prefix('dashboard')->name('dashboard.')->group(function () {



            // Export endpoints - Dashboard reports
            Route::get('/export', [PartnerDashboardController::class, 'exportExcel'])
                ->name('export');
            Route::get('/export/jobs', [PartnerDashboardController::class, 'exportJobs'])
                ->name('export-jobs');
            Route::get('/export/partnerships', [PartnerDashboardController::class, 'exportPartnerships'])
                ->name('export-partnerships');
            Route::get('/export/applications', [PartnerDashboardController::class, 'exportApplications'])
                ->name('export-applications');


            // AJAX endpoints - Statistics
            Route::get('/stats/jobs', [PartnerDashboardController::class, 'jobStats'])
                ->name('stats.jobs');
            Route::get('/stats/applications', [PartnerDashboardController::class, 'applicationStats'])
                ->name('stats.applications');
            Route::get('/activity-feed', [PartnerDashboardController::class, 'activityFeed'])
                ->name('activity-feed');
        });


        // ===== PROFILE & SETTINGS (ALWAYS ACCESSIBLE - NO MIDDLEWARE) =====
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [PartnerProfileController::class, 'show'])
                ->name('show');
            Route::get('/edit', [PartnerProfileController::class, 'edit'])
                ->name('edit');
            Route::post('/', [PartnerProfileController::class, 'update'])
                ->name('update');
        });


        // ===== ROUTES REQUIRING COMPLETE PROFILE =====
        Route::middleware('partner.profile.complete')->group(function () {


            // ===== JOB POSTINGS =====
            Route::prefix('job-postings')->name('job-postings.')->group(function () {
                Route::get('/', [JobPostingController::class, 'index'])
                    ->name('index');
                Route::get('/create', [JobPostingController::class, 'create'])
                    ->name('create');
                Route::post('/', [JobPostingController::class, 'store'])
                    ->name('store');
                Route::get('/{jobPosting}', [JobPostingController::class, 'show'])
                    ->name('show');
                Route::get('/{jobPosting}/edit', [JobPostingController::class, 'edit'])
                    ->name('edit');
                Route::put('/{jobPosting}', [JobPostingController::class, 'update'])
                    ->name('update');
                Route::delete('/{jobPosting}', [JobPostingController::class, 'destroy'])
                    ->name('destroy');
                Route::post('/{jobPosting}/pause', [JobPostingController::class, 'pause'])
                    ->name('pause');
                Route::post('/{jobPosting}/resume', [JobPostingController::class, 'resume'])
                    ->name('resume');
                Route::post('/{jobPosting}/close', [JobPostingController::class, 'close'])
                    ->name('close');


                // ✅ APPLICATION ROUTES (Nested under job-postings)
                Route::get('/{jobPosting}/applications', [JobPostingController::class, 'applications'])
                    ->name('applications');


                // ✅ Export applications to Excel by job posting
                Route::get('/{jobPosting}/applications/export', [ApplicationController::class, 'exportExcel'])
                    ->name('applications.export');
            });


            // ===== JOB APPLICATIONS =====
            Route::prefix('applications')->name('applications.')->group(function () {
                Route::get('/{application}', [ApplicationController::class, 'show'])
                    ->name('show');
                Route::post('/{application}/approve', [ApplicationController::class, 'approve'])
                    ->name('approve');
                Route::post('/{application}/reject', [ApplicationController::class, 'reject'])
                    ->name('reject');
                Route::post('/{application}/contact', [ApplicationController::class, 'contact'])
                    ->name('contact');
                Route::get('/{application}/download-resume', [ApplicationController::class, 'downloadResume'])
                    ->name('download-resume');
                Route::get('/{application}/download-cover-letter', [ApplicationController::class, 'downloadCoverLetter'])
                    ->name('download-cover-letter');
                Route::patch('/{application}/update-status', [ApplicationController::class, 'updateStatus'])
                    ->name('update-status');
            });


            // ===== PARTNERSHIPS =====
            Route::prefix('partnerships')->name('partnerships.')->group(function () {
                Route::get('/', [PartnershipController::class, 'index'])
                    ->name('index');
                Route::get('/create', [PartnershipController::class, 'create'])
                    ->name('create');
                Route::post('/', [PartnershipController::class, 'store'])
                    ->name('store');
                Route::get('/{partnership}', [PartnershipController::class, 'show'])
                    ->name('show');
                Route::get('/{partnership}/edit', [PartnershipController::class, 'edit'])
                    ->name('edit');
                Route::put('/{partnership}', [PartnershipController::class, 'update'])
                    ->name('update');
                Route::post('/{partnership}/complete', [PartnershipController::class, 'complete'])
                    ->name('complete');
                Route::delete('/{partnership}', [PartnershipController::class, 'destroy'])
                    ->name('destroy');
            });
        });
    });



    // =====================================================
    // STUDENT DASHBOARD ROUTES
    // =====================================================

    Route::middleware('role:student')->prefix('student')->name('student.')->group(function () {

        // ===== DASHBOARD =====
        Route::get('/dashboard', [DashboardController::class, 'dashboard'])
            ->name('dashboard');

        // ===== PROFILE (NO MIDDLEWARE - Can access anytime) =====
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [StudentProfileController::class, 'show'])->name('show');
            Route::get('/edit', [StudentProfileController::class, 'edit'])->name('edit');
            Route::post('/', [StudentProfileController::class, 'update'])->name('update');

            // ✅ NEW: File Deletion Routes
            Route::delete('/delete-resume', [StudentProfileController::class, 'deleteResume'])
                ->name('delete-resume');
            Route::delete('/delete-certification', [StudentProfileController::class, 'deleteCertification'])
                ->name('delete-certification');
        });

        // Experiences
        Route::prefix('experiences')->name('experiences.')->group(function () {
            Route::post('/', [ExperienceController::class, 'store'])->name('store');
            Route::put('{experience}', [ExperienceController::class, 'update'])->name('update');
            Route::delete('{experience}', [ExperienceController::class, 'destroy'])->name('destroy');
        });

        // Projects
        Route::prefix('projects')->name('projects.')->group(function () {
            Route::post('/', [ProjectController::class, 'store'])->name('store');
            Route::put('{project}', [ProjectController::class, 'update'])->name('update');
            Route::delete('{project}', [ProjectController::class, 'destroy'])->name('destroy');
        });

        // ===== PROTECTED ROUTES (Require complete profile) =====
        Route::middleware('student.profile.complete')->group(function () {

            // ===== JOBS =====
            Route::prefix('jobs')->name('jobs.')->group(function () {
                Route::get('/', [StudentJobController::class, 'index'])->name('index');
                Route::get('/{job}', [StudentJobController::class, 'show'])->name('show');
                Route::post('/{job}/apply', [StudentJobController::class, 'apply'])->name('apply');
            });

            // APPLICATIONS ROUTES
            Route::prefix('applications')->name('applications.')->group(function () {
                Route::get('/', [StudentJobController::class, 'applications'])->name('index');
                Route::get('/{application}', [StudentJobController::class, 'viewApplication'])->name('show');
                Route::delete('/{application}', [StudentJobController::class, 'withdrawApplication'])->name('destroy');
            });
        });
    });

    // =====================================================
    // ALUMNI DASHBOARD ROUTES - WITH PROFILE COMPLETION CHECK
    // =====================================================

    Route::middleware('role:alumni')->prefix('alumni')->name('alumni.')->group(function () {

        // ===== DASHBOARD =====
        Route::get('/dashboard', [AlumniDashboardController::class, 'dashboard'])
            ->name('dashboard');

        // ===== PROFILE & SETTINGS =====
        Route::prefix('profile')->name('profile.')->group(function () {
            // User Type Selection (First Step)
            Route::get('/select-type', [AlumniProfileController::class, 'selectType'])
                ->name('select-type');
            Route::post('/set-type', [AlumniProfileController::class, 'setType'])
                ->name('set-type');

            // ✅ NEW: Change Type Route
            Route::post('/change-type', [AlumniProfileController::class, 'changeType'])
                ->name('change-type');

            // Profile Edit & Update (After Type Selection)
            Route::get('/show', [AlumniProfileController::class, 'show'])
                ->name('show');
            Route::get('/edit', [AlumniProfileController::class, 'edit'])
                ->name('edit');
            Route::post('/update', [AlumniProfileController::class, 'update'])
                ->name('update');

            // ✅ NEW: File Deletion Routes
            Route::delete('/delete-resume', [AlumniProfileController::class, 'deleteResume'])
                ->name('delete-resume');
            Route::delete('/delete-certification', [AlumniProfileController::class, 'deleteCertification'])
                ->name('delete-certification');
        });

        // ===== ALUMNI EXPERIENCES =====
        Route::prefix('experiences')->name('experiences.')->group(function () {
            Route::post('/', [AlumniExperienceController::class, 'store'])
                ->name('store');
            Route::put('/{experience}', [AlumniExperienceController::class, 'update'])
                ->name('update');
            Route::delete('/{experience}', [AlumniExperienceController::class, 'destroy'])
                ->name('destroy');
        });

        // ===== ALUMNI PROJECTS =====
        Route::prefix('projects')->name('projects.')->group(function () {
            Route::post('/', [AlumniProjectController::class, 'store'])
                ->name('store');
            Route::put('/{project}', [AlumniProjectController::class, 'update'])
                ->name('update');
            Route::delete('/{project}', [AlumniProjectController::class, 'destroy'])
                ->name('destroy');
        });

        // ===== PROTECTED ROUTES (Require complete profile) =====
        Route::middleware('alumni.profile.complete')->group(function () {

            // ===== JOBS =====
            Route::prefix('jobs')->name('jobs.')->group(function () {
                Route::get('/', [AlumniJobController::class, 'index'])->name('index');
                Route::get('/{job}', [AlumniJobController::class, 'show'])->name('show');
                Route::post('/{job}/apply', [AlumniJobController::class, 'apply'])->name('apply');
            });

            // ===== APPLICATIONS ROUTES =====
            Route::prefix('applications')->name('applications.')->group(function () {
                Route::get('/', [AlumniJobController::class, 'applications'])->name('index');
                Route::get('/{application}', [AlumniJobController::class, 'viewApplication'])->name('show');
                Route::delete('/{application}', [AlumniJobController::class, 'withdrawApplication'])->name('destroy');
            });
        });
    });
});
