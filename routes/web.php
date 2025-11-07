<?php

use App\Http\Controllers\Admin\Analytics\ActivityLogController;
use App\Http\Controllers\Admin\Analytics\ReportController;
use App\Http\Controllers\Admin\Departments\DepartmentController;
use App\Http\Controllers\Admin\Events\EventApprovalController;
use App\Http\Controllers\Admin\Jobs\JobApprovalController;
use App\Http\Controllers\Admin\News\NewsApprovalController;
use App\Http\Controllers\Admin\Partnerships\PartnershipApprovalController;
use App\Http\Controllers\Admin\Users\UserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Dashboard\AdminDashboardController;
use App\Http\Controllers\Dashboard\StaffDashboardController;
use App\Http\Controllers\Partner\DashboardController;
use App\Http\Controllers\Partner\JobPostingController;
use App\Http\Controllers\Partner\NewsController;
use App\Http\Controllers\Partner\PartnershipController;
use App\Http\Controllers\Partner\ProfileController;
use App\Http\Controllers\Partner\SettingsController;
use App\Http\Controllers\Staff\Events\EventController;
use Illuminate\Support\Facades\Route;


// ===== STUDENT ROUTES =====
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Student\DashboardController::class, 'dashboard'])->name('dashboard');

    // Jobs
    Route::get('/jobs', [App\Http\Controllers\Student\StudentJobController::class, 'index'])->name('jobs.index');
    Route::get('/jobs/{job}', [App\Http\Controllers\Student\StudentJobController::class, 'show'])->name('jobs.show');

    // Events
    Route::get('/events', [App\Http\Controllers\Student\StudentEventController::class, 'index'])->name('events.index');
    Route::get('/events/{event}', [App\Http\Controllers\Student\StudentEventController::class, 'show'])->name('events.show');
    Route::post('/events/{event}/register', [App\Http\Controllers\Student\StudentEventController::class, 'register'])->name('events.register');

    // News
    Route::get('/news', [App\Http\Controllers\Student\StudentNewsController::class, 'index'])->name('news.index');
    Route::get('/news/{article}', [App\Http\Controllers\Student\StudentNewsController::class, 'show'])->name('news.show');

    // Profile
    Route::get('/profile', [App\Http\Controllers\Student\StudentProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [App\Http\Controllers\Student\StudentProfileController::class, 'update'])->name('profile.update');
});





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
    // STAFF DASHBOARD ROUTES
    // =====================================================

    // Staff Events Routes (Separate Controller)
    // =====================================================
    // STAFF DASHBOARD ROUTES (REFACTORED & COMPLETE)
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

        // ===== EVENT MANAGEMENT (Separate Controller) =====
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

        // ===== NEWS MANAGEMENT (Separate Controller) =====
        Route::prefix('news')->name('news.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Staff\News\NewsController::class, 'index'])
                ->name('index');
            Route::get('/create', [\App\Http\Controllers\Staff\News\NewsController::class, 'create'])
                ->name('create');
            Route::post('/', [\App\Http\Controllers\Staff\News\NewsController::class, 'store'])
                ->name('store');
            Route::get('/{newsArticle}', [\App\Http\Controllers\Staff\News\NewsController::class, 'show'])
                ->name('show');
            Route::get('/{newsArticle}/edit', [\App\Http\Controllers\Staff\News\NewsController::class, 'edit'])
                ->name('edit');
            Route::put('/{newsArticle}', [\App\Http\Controllers\Staff\News\NewsController::class, 'update'])
                ->name('update');
            Route::post('/{newsArticle}/submit', [\App\Http\Controllers\Staff\News\NewsController::class, 'submit'])
                ->name('submit');
            Route::post('/{newsArticle}/withdraw', [\App\Http\Controllers\Staff\News\NewsController::class, 'withdraw'])
                ->name('withdraw');
            Route::delete('/{newsArticle}', [\App\Http\Controllers\Staff\News\NewsController::class, 'destroy'])
                ->name('destroy');
        });
    });




    // =====================================================
    // PARTNER DASHBOARD ROUTES (FIXED)
    // =====================================================

    Route::middleware('role:partner')->prefix('partner')->name('partner.')->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Job Postings - FIXED: Removed duplicate routes
        Route::prefix('job-postings')->name('job-postings.')->group(function () {
            Route::get('/', [JobPostingController::class, 'index'])->name('index');
            Route::get('/create', [JobPostingController::class, 'create'])->name('create');
            Route::post('/', [JobPostingController::class, 'store'])->name('store');
            Route::get('/{jobPosting}', [JobPostingController::class, 'show'])->name('show');
            Route::get('/{jobPosting}/edit', [JobPostingController::class, 'edit'])->name('edit');
            Route::put('/{jobPosting}', [JobPostingController::class, 'update'])->name('update');
            Route::post('/{jobPosting}/pause', [JobPostingController::class, 'pause'])->name('pause');
            Route::post('/{jobPosting}/resume', [JobPostingController::class, 'resume'])->name('resume');
            Route::post('/{jobPosting}/close', [JobPostingController::class, 'close'])->name('close');
            Route::get('/{jobPosting}/applications', [JobPostingController::class, 'applications'])->name('applications');
        });

        // Partnerships - FIXED: Added complete route
        Route::prefix('partnerships')->name('partnerships.')->group(function () {
            Route::get('/', [PartnershipController::class, 'index'])->name('index');
            Route::get('/create', [PartnershipController::class, 'create'])->name('create');
            Route::post('/', [PartnershipController::class, 'store'])->name('store');
            Route::get('/{partnership}', [PartnershipController::class, 'show'])->name('show');
            Route::get('/{partnership}/edit', [PartnershipController::class, 'edit'])->name('edit');
            Route::put('/{partnership}', [PartnershipController::class, 'update'])->name('update');
            Route::post('/{partnership}/complete', [PartnershipController::class, 'complete'])->name('complete');
            Route::delete('/{partnership}', [PartnershipController::class, 'destroy'])->name('destroy');
        });

        // News - FIXED: Added complete routes
        Route::prefix('news')->name('news.')->group(function () {
            Route::get('/', [NewsController::class, 'index'])->name('index');
            Route::get('/create', [NewsController::class, 'create'])->name('create');
            Route::post('/', [NewsController::class, 'store'])->name('store');
            Route::get('/{news}', [NewsController::class, 'show'])->name('show');
            Route::get('/{news}/edit', [NewsController::class, 'edit'])->name('edit');
            Route::put('/{news}', [NewsController::class, 'update'])->name('update');
            Route::post('/{news}/publish', [NewsController::class, 'publish'])->name('publish');
            Route::delete('/{news}', [NewsController::class, 'destroy'])->name('destroy');
        });

        // Profile
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [ProfileController::class, 'show'])->name('show');
            Route::put('/', [ProfileController::class, 'update'])->name('update');
        });

        // Settings
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [SettingsController::class, 'show'])->name('show');
            Route::put('/', [SettingsController::class, 'update'])->name('update');
        });
    });

    // =====================================================
    // STUDENT DASHBOARD ROUTES
    // =====================================================




    // =====================================================
    // ALUMNI DASHBOARD ROUTES
    // =====================================================

    // ===== ALUMNI ROUTES =====
    Route::middleware('role:alumni')->prefix('alumni')->name('alumni.')->group(function () {

        // Dashboard
        Route::get('/dashboard', [\App\Http\Controllers\Alumni\DashboardController::class, 'dashboard'])
            ->name('dashboard');

        // Profile & Settings
        Route::get('/profile', [\App\Http\Controllers\Alumni\ProfileController::class, 'index'])
            ->name('profile.index');
        Route::get('/profile/edit', [\App\Http\Controllers\Alumni\ProfileController::class, 'edit'])
            ->name('profile.edit');
        Route::put('/profile', [\App\Http\Controllers\Alumni\ProfileController::class, 'update'])
            ->name('profile.update');
        Route::get('/settings', [\App\Http\Controllers\Alumni\ProfileController::class, 'settings'])
            ->name('profile.settings');
        Route::put('/settings', [\App\Http\Controllers\Alumni\ProfileController::class, 'updateSettings'])
            ->name('profile.settings.update');

        // Jobs (Read-only + Apply)
        Route::prefix('jobs')->name('jobs.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Alumni\JobController::class, 'index'])
                ->name('index');
            Route::get('/{job}', [\App\Http\Controllers\Alumni\JobController::class, 'show'])
                ->name('show');
            Route::post('/{job}/apply', [\App\Http\Controllers\Alumni\JobController::class, 'apply'])
                ->name('apply');
        });

        // Events (View & Register)
        Route::prefix('events')->name('events.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Alumni\EventController::class, 'index'])
                ->name('index');
            Route::get('/{event}', [\App\Http\Controllers\Alumni\EventController::class, 'show'])
                ->name('show');
            Route::post('/{event}/register', [\App\Http\Controllers\Alumni\EventController::class, 'register'])
                ->name('register');
        });

        // News (Read-only)
        Route::prefix('news')->name('news.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Alumni\NewsController::class, 'index'])
                ->name('index');
            Route::get('/{news}', [\App\Http\Controllers\Alumni\NewsController::class, 'show'])
                ->name('show');
        });
    });
});
