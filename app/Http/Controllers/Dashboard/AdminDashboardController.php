<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\JobPosting;
use App\Models\Event;
use App\Models\NewsArticle;
use App\Models\Partnership;
use App\Models\ActivityLog;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    /**
     * Show enhanced admin dashboard with comprehensive statistics and insights.
     */
    public function dashboard()
    {
        // ===== CORE STATISTICS =====
        $stats = [
            // User Statistics
            'total_users' => User::count() ?? 0,
            'active_users' => User::where('is_active', true)->count() ?? 0,
            'inactive_users' => User::where('is_active', false)->count() ?? 0,
            'users_by_role' => $this->getUsersByRole(),

            // Content Statistics
            'total_jobs' => JobPosting::count() ?? 0,
            'published_jobs' => JobPosting::where('status', 'published')->count() ?? 0,
            'featured_jobs' => JobPosting::where('is_featured', true)->count() ?? 0,
            'job_applications' => $this->getTotalJobApplications(),

            'total_events' => Event::count() ?? 0,
            'published_events' => Event::where('status', 'published')->count() ?? 0,
            'featured_events' => Event::where('is_featured', true)->count() ?? 0,
            'upcoming_events' => $this->getUpcomingEventsCount(),

            'total_articles' => NewsArticle::count() ?? 0,
            'published_articles' => NewsArticle::where('status', 'published')->count() ?? 0,
            'featured_articles' => NewsArticle::where('is_featured', true)->count() ?? 0,

            'total_partnerships' => Partnership::count() ?? 0,
            'approved_partnerships' => Partnership::where('status', 'approved')->count() ?? 0,
            'completed_partnerships' => Partnership::where('status', 'completed')->count() ?? 0,

            // Approval Pending Counts
            'pending_approvals' => $this->getPendingApprovalsCount(),
            'pending_jobs' => JobPosting::where('status', 'pending')->count() ?? 0,
            'pending_events' => Event::where('status', 'pending')->count() ?? 0,
            'pending_articles' => NewsArticle::where('status', 'pending')->count() ?? 0,
            'pending_partnerships' => Partnership::whereIn('status', ['submitted', 'under_review'])->count() ?? 0,

            // Recent Activity & Logs
            'recent_activity' => ActivityLog::with('user')->latest()->limit(10)->get(),
            'activity_today' => ActivityLog::whereDate('created_at', today())->count() ?? 0,
            'activity_week' => ActivityLog::where('created_at', '>=', now()->subWeek())->count() ?? 0,

            // Top Statistics
            'top_active_users' => $this->getTopActiveUsers(),
            'content_creation_trends' => $this->getContentCreationTrends(),
        ];

        return view('users.admin.dashboard', $stats);
    }

    /**
     * Get user count breakdown by role.
     */
    private function getUsersByRole(): array
    {
        $users = User::selectRaw('role, count(*) as count')
            ->groupBy('role')
            ->get()
            ->pluck('count', 'role')
            ->toArray();

        return [
            'admin' => $users['admin'] ?? 0,
            'staff' => $users['staff'] ?? 0,
            'student' => $users['student'] ?? 0,
            'partner' => $users['partner'] ?? 0,
            'alumni' => $users['alumni'] ?? 0,
        ];
    }

    /**
     * Get total job applications count.
     */
    private function getTotalJobApplications(): int
    {
        return DB::table('job_applications')->count() ?? 0;
    }
    /**
     * Get count of upcoming events.
     */
    private function getUpcomingEventsCount(): int
    {
        return Event::where('status', 'published')
            ->where('event_date', '>=', now())  // Changed from start_date to event_date
            ->count() ?? 0;
    }


    /**
     * Get count of pending approvals across all content types.
     */
    private function getPendingApprovalsCount(): int
    {
        return (JobPosting::where('status', 'pending')->count() ?? 0)
            + (Event::where('status', 'pending')->count() ?? 0)
            + (NewsArticle::where('status', 'pending')->count() ?? 0)
            + (Partnership::whereIn('status', ['submitted', 'under_review'])->count() ?? 0);
    }

    /**
     * Get top active users (most activity in last 30 days).
     */
    private function getTopActiveUsers(int $limit = 5): array
    {
        return ActivityLog::selectRaw('user_id, COUNT(*) as activity_count')
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('user_id')
            ->orderBy('activity_count', 'desc')
            ->limit($limit)
            ->with('user')
            ->get()
            ->map(fn($log) => [
                'user' => $log->user,
                'activity_count' => $log->activity_count ?? 0,
            ])
            ->toArray();
    }

    /**
     * Get content creation trends (last 7 days).
     */
    private function getContentCreationTrends(): array
    {
        $trends = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $trends[$date] = [
                'date' => $date,
                'jobs' => JobPosting::whereDate('created_at', $date)->count() ?? 0,
                'events' => Event::whereDate('created_at', $date)->count() ?? 0,
                'articles' => NewsArticle::whereDate('created_at', $date)->count() ?? 0,
                'partnerships' => Partnership::whereDate('created_at', $date)->count() ?? 0,
            ];
        }
        return $trends;
    }

    // ===== ADMIN PROFILE & SETTINGS =====

    /**
     * Show admin profile.
     */
    public function profile()
    {
        $admin = auth()->user();
        return view('users.admin.profile', compact('admin'));
    }

    /**
     * Update admin profile.
     */
    public function updateProfile(Request $request)
    {
        $admin = auth()->user();

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => "required|email|unique:users,email,{$admin->id}",
            'phone' => 'nullable|string|max:20',
        ]);

        try {
            DB::beginTransaction();

            $admin->update($validated);

            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'updated',
                'description' => "Updated own profile",
                'subject_type' => User::class,
                'subject_id' => $admin->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            DB::commit();

            return redirect()->route('admin.profile')
                ->with('success', 'Profile updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Admin profile update failed: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Failed to update profile: ' . $e->getMessage());
        }
    }

    /**
     * Show admin settings.
     */
    public function settings()
    {
        return view('users.admin.settings');
    }

    /**
     * Update admin settings.
     */
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'items_per_page' => 'nullable|integer|min:5|max:100',
            'theme' => 'nullable|in:light,dark',
            'notifications_enabled' => 'boolean',
        ]);

        try {
            // Store settings in session or database (based on your preference)
            session(['admin_settings' => $validated]);

            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => 'updated',
                'description' => "Updated admin settings",
                'subject_type' => 'settings',
                'subject_id' => 0,
                'properties' => json_encode($validated),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()->route('admin.settings')
                ->with('success', 'Settings updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Admin settings update failed: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Failed to update settings: ' . $e->getMessage());
        }
    }

    // ===== USER MANAGEMENT (kept for backwards compatibility, delegated to UserController) =====

    /**
     * Show all users with advanced filtering.
     * NOTE: This is kept for backwards compatibility. See UserController for actual implementation.
     */
    public function users(Request $request)
    {
        // Delegate to UserController
        return app(\App\Http\Controllers\Admin\Users\UserController::class)->index($request);
    }

    /**
     * Show create user form.
     * NOTE: This is kept for backwards compatibility. See UserController for actual implementation.
     */
    public function createUser()
    {
        // Delegate to UserController
        return app(\App\Http\Controllers\Admin\Users\UserController::class)->create();
    }

    /**
     * Store new user.
     * NOTE: This is kept for backwards compatibility. See UserController for actual implementation.
     */
    public function storeUser(Request $request)
    {
        // Delegate to UserController
        return app(\App\Http\Controllers\Admin\Users\UserController::class)->store($request);
    }

    /**
     * Show edit user form.
     * NOTE: This is kept for backwards compatibility. See UserController for actual implementation.
     */
    public function editUser($id)
    {
        // Delegate to UserController
        return app(\App\Http\Controllers\Admin\Users\UserController::class)->edit($id);
    }

    /**
     * Update user.
     * NOTE: This is kept for backwards compatibility. See UserController for actual implementation.
     */
    public function updateUser(Request $request, $id)
    {
        // Delegate to UserController
        return app(\App\Http\Controllers\Admin\Users\UserController::class)->update($request, $id);
    }

    /**
     * Delete user.
     * NOTE: This is kept for backwards compatibility. See UserController for actual implementation.
     */
    public function deleteUser($id)
    {
        // Delegate to UserController
        return app(\App\Http\Controllers\Admin\Users\UserController::class)->destroy($id);
    }

    /**
     * Show bulk import form.
     * NOTE: This is kept for backwards compatibility. See UserController for actual implementation.
     */
    public function bulkImportForm()
    {
        // Delegate to UserController
        return app(\App\Http\Controllers\Admin\Users\UserController::class)->bulkImportForm();
    }

    /**
     * Handle bulk user import.
     * NOTE: This is kept for backwards compatibility. See UserController for actual implementation.
     */
    public function bulkImport(Request $request)
    {
        // Delegate to UserController
        return app(\App\Http\Controllers\Admin\Users\UserController::class)->bulkImport($request);
    }
}
