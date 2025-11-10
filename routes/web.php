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
use App\Http\Controllers\Alumni\AlumniEventController;
use App\Http\Controllers\Alumni\AlumniExperienceController;
use App\Http\Controllers\Alumni\AlumniJobController;
use App\Http\Controllers\Alumni\AlumniNewsController;
use App\Http\Controllers\Alumni\AlumniProfileController;
use App\Http\Controllers\Alumni\AlumniProjectController;
use App\Http\Controllers\Alumni\DashboardController as AlumniDashboardController;
use App\Http\Controllers\Partner\ApplicationController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Dashboard\AdminDashboardController;
use App\Http\Controllers\Dashboard\StaffDashboardController;
use App\Http\Controllers\Partner\JobPostingController;
use App\Http\Controllers\Partner\NewsController as PartnerNewsController;
use App\Http\Controllers\Partner\PartnerDashboardController;
use App\Http\Controllers\Partner\PartnershipController;
use App\Http\Controllers\Partner\ProfileController as PartnerProfileController;
use App\Http\Controllers\Partner\SettingsController as PartnerSettingsController;
use App\Http\Controllers\Staff\Events\EventController;
use App\Http\Controllers\Staff\News\NewsController as StaffNewsController;
use App\Http\Controllers\Student\ExperienceController;
use App\Http\Controllers\Student\ProjectController;
use App\Http\Controllers\Student\StudentEventController;
use App\Http\Controllers\Student\StudentJobController;
use App\Http\Controllers\Student\StudentNewsController;
use App\Http\Controllers\Student\StudentProfileController;
use App\Http\Controllers\Student\DashboardController;
use Illuminate\Support\Facades\Route;



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
});


// =====================================================
// AUTHENTICATED ROUTES (Require Auth + Email Verified + Password Changed + Active)
// =====================================================


Route::middleware(['auth', 'verified', 'password.changed', 'active'])->group(function () {

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

        // ===== EVENTS APPROVALS =====
        Route::prefix('approvals/events')->name('approvals.events.')->group(function () {
            Route::get('/', [EventApprovalController::class, 'index'])
                ->name('index');
            Route::post('/{id}/approve', [EventApprovalController::class, 'approve'])
                ->name('approve');
            Route::post('/{id}/reject', [EventApprovalController::class, 'reject'])
                ->name('reject');
        });

        // ===== NEWS ARTICLES APPROVALS =====
        Route::prefix('approvals/news')->name('approvals.news.')->group(function () {
            Route::get('/', [NewsApprovalController::class, 'index'])
                ->name('index');
            Route::post('/{id}/approve', [NewsApprovalController::class, 'approve'])
                ->name('approve');
            Route::post('/{id}/reject', [NewsApprovalController::class, 'reject'])
                ->name('reject');
        });

        // ===== PARTNERSHIPS APPROVALS =====
        Route::prefix('approvals/partnerships')->name('approvals.partnerships.')->group(function () {
            Route::get('/', [PartnershipApprovalController::class, 'index'])
                ->name('index');
            Route::post('/{id}/approve', [PartnershipApprovalController::class, 'approve'])
                ->name('approve');
            Route::post('/{id}/reject', [PartnershipApprovalController::class, 'reject'])
                ->name('reject');
        });

        // Activity Logs
        Route::get('/activity-logs', [ActivityLogController::class, 'index'])
            ->name('activity-logs');
        Route::get('/activity-logs/{log}', [ActivityLogController::class, 'show'])
            ->name('activity-logs.show');
        Route::post('/activity-logs/export', [ActivityLogController::class, 'export'])
            ->name('activity-logs.export');

        Route::get('/reports', [ReportController::class, 'index'])
            ->name('reports');
    });

    // =====================================================
    // STAFF DASHBOARD ROUTES
    // =====================================================

    Route::middleware('role:staff')->prefix('staff')->name('staff.')->group(function () {

        // ===== DASHBOARD =====
        Route::get('/dashboard', [StaffDashboardController::class, 'dashboard'])
            ->name('dashboard');

        // ===== PROFILE & SETTINGS =====
        Route::get('/profile', [StaffDashboardController::class, 'profile'])
            ->name('profile');
        Route::put('/profile', [StaffDashboardController::class, 'updateProfile'])
            ->name('profile.update');

        Route::get('/settings', [StaffDashboardController::class, 'settings'])
            ->name('settings');
        Route::put('/settings', [StaffDashboardController::class, 'updateSettings'])
            ->name('settings.update');

        // ===== EVENT MANAGEMENT =====
        Route::prefix('events')->name('events.')->group(function () {
            // Basic CRUD
            Route::get('/', [EventController::class, 'index'])
                ->name('index');
            Route::get('/create', [EventController::class, 'create'])
                ->name('create');
            Route::post('/', [EventController::class, 'store'])
                ->name('store');
            Route::get('/{event}', [EventController::class, 'show'])
                ->name('show');
            Route::get('/{event}/edit', [EventController::class, 'edit'])
                ->name('edit');
            Route::put('/{event}', [EventController::class, 'update'])
                ->name('update');
            Route::delete('/{event}', [EventController::class, 'destroy'])
                ->name('destroy');

            // Event Status Actions
            Route::post('/{event}/submit', [EventController::class, 'submit'])
                ->name('submit');
            Route::post('/{event}/withdraw', [EventController::class, 'withdraw'])
                ->name('withdraw');
            Route::post('/{event}/publish', [EventController::class, 'publish'])
                ->name('publish');
            Route::post('/{event}/cancel', [EventController::class, 'cancel'])
                ->name('cancel');
            Route::post('/{event}/end', [EventController::class, 'end'])
                ->name('end');

            // Registrations & Attendance
            Route::get('/{event}/registrations', [EventController::class, 'registrations'])
                ->name('registrations');
            Route::post('/{event}/registrations/{registration}/confirm', [EventController::class, 'confirmRegistration'])
                ->name('registrations.confirm');
            Route::post('/{event}/registrations/{registration}/cancel', [EventController::class, 'cancelRegistration'])
                ->name('registrations.cancel');
            Route::get('/{event}/registrations/{registration}', [EventController::class, 'registrationDetails'])
                ->name('registrations.details');

            // Attendance Management
            Route::get('/{event}/attendance', [EventController::class, 'attendance'])
                ->name('attendance');
            Route::post('/{event}/attendance/{registration}/check-in', [EventController::class, 'checkIn'])
                ->name('attendance.check-in');
            Route::post('/{event}/attendance/{registration}/check-out', [EventController::class, 'checkOut'])
                ->name('attendance.check-out');
            Route::get('/{event}/attendance/export', [EventController::class, 'exportAttendance'])
                ->name('attendance.export');

            // Registrations Export
            Route::get('/{event}/registrations/export', [EventController::class, 'exportRegistrations'])
                ->name('registrations.export');
        });

        // ===== NEWS MANAGEMENT =====
        Route::prefix('news')->name('news.')->group(function () {
            Route::get('/', [StaffNewsController::class, 'index'])
                ->name('index');
            Route::get('/create', [StaffNewsController::class, 'create'])
                ->name('create');
            Route::post('/', [StaffNewsController::class, 'store'])
                ->name('store');
            Route::get('/{newsArticle}', [StaffNewsController::class, 'show'])
                ->name('show');
            Route::get('/{newsArticle}/edit', [StaffNewsController::class, 'edit'])
                ->name('edit');
            Route::put('/{newsArticle}', [StaffNewsController::class, 'update'])
                ->name('update');
            Route::post('/{newsArticle}/submit', [StaffNewsController::class, 'submit'])
                ->name('submit');
            Route::post('/{newsArticle}/withdraw', [StaffNewsController::class, 'withdraw'])
                ->name('withdraw');
            Route::delete('/{newsArticle}', [StaffNewsController::class, 'destroy'])
                ->name('destroy');
        });
    });

    // =====================================================
    // PARTNER DASHBOARD ROUTES
    // =====================================================

    Route::middleware('role:partner')->prefix('partner')->name('partner.')->group(function () {

        // ===== DASHBOARD =====
        Route::get('/dashboard', [PartnerDashboardController::class, 'index'])
            ->name('dashboard');

        // ===== PROFILE & SETTINGS (ALWAYS ACCESSIBLE - NO MIDDLEWARE) =====
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [PartnerProfileController::class, 'show'])
                ->name('show');
            Route::get('/edit', [PartnerProfileController::class, 'edit'])
                ->name('edit');
            Route::post('/', [PartnerProfileController::class, 'update'])
                ->name('update');
        });

        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [PartnerSettingsController::class, 'show'])
                ->name('show');
            Route::put('/', [PartnerSettingsController::class, 'update'])
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
                Route::get('/{jobPosting}/applications', [JobPostingController::class, 'applications'])
                    ->name('applications');
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

            // ===== NEWS =====
            Route::prefix('news')->name('news.')->group(function () {
                Route::get('/', [PartnerNewsController::class, 'index'])
                    ->name('index');
                Route::get('/create', [PartnerNewsController::class, 'create'])
                    ->name('create');
                Route::post('/', [PartnerNewsController::class, 'store'])
                    ->name('store');
                Route::get('/{news}', [PartnerNewsController::class, 'show'])
                    ->name('show');
                Route::get('/{news}/edit', [PartnerNewsController::class, 'edit'])
                    ->name('edit');
                Route::put('/{news}', [PartnerNewsController::class, 'update'])
                    ->name('update');
                Route::post('/{news}/publish', [PartnerNewsController::class, 'publish'])
                    ->name('publish');
                Route::delete('/{news}', [PartnerNewsController::class, 'destroy'])
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
            //  APPLICATIONS ROUTES
            Route::prefix('applications')->name('applications.')->group(function () {
                Route::get('/', [StudentJobController::class, 'applications'])->name('index');
                Route::get('/{application}', [StudentJobController::class, 'viewApplication'])->name('show');
                Route::delete('/{application}', [StudentJobController::class, 'withdrawApplication'])->name('destroy');
            });



            // ===== EVENTS =====
            Route::prefix('events')->name('events.')->group(function () {
                Route::get('/', [StudentEventController::class, 'index'])->name('index');
                Route::get('/{event}', [StudentEventController::class, 'show'])->name('show');
                Route::post('/{event}/register', [StudentEventController::class, 'register'])->name('register');
            });

            // ===== NEWS =====
            Route::prefix('news')->name('news.')->group(function () {
                Route::get('/', [StudentNewsController::class, 'index'])->name('index');
                Route::get('/{article}', [StudentNewsController::class, 'show'])->name('show');
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

        // ===== PROFILE & SETTINGS (ALWAYS ACCESSIBLE - NO MIDDLEWARE) =====
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [AlumniProfileController::class, 'show'])
                ->name('show');
            Route::get('/edit', [AlumniProfileController::class, 'edit'])
                ->name('edit');
            Route::post('/update', [AlumniProfileController::class, 'update'])
                ->name('update');
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
            //  APPLICATIONS ROUTES
            Route::prefix('applications')->name('applications.')->group(function () {
                Route::get('/', [AlumniJobController::class, 'applications'])->name('index');
                Route::get('/{application}', [AlumniJobController::class, 'viewApplication'])->name('show');
                Route::delete('/{application}', [AlumniJobController::class, 'withdrawApplication'])->name('destroy');
            });

            // ===== EVENTS =====
            Route::prefix('events')->name('events.')->group(function () {
                Route::get('/', [AlumniEventController::class, 'index'])
                    ->name('index');
                Route::get('/{event}', [AlumniEventController::class, 'show'])
                    ->name('show');
                Route::post('/{event}/register', [AlumniEventController::class, 'register'])
                    ->name('register');
            });

            // ===== NEWS =====
            Route::prefix('news')->name('news.')->group(function () {
                Route::get('/', [AlumniNewsController::class, 'index'])
                    ->name('index');
                Route::get('/{news}', [AlumniNewsController::class, 'show'])
                    ->name('show');
            });
        });
    });
});
