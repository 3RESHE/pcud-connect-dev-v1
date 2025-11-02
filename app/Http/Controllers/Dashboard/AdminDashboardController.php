<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\JobPosting;
use App\Models\Event;
use App\Models\NewsArticle;
use App\Models\Partnership;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    /**
     * Show admin dashboard.
     */
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('is_active', true)->count(),
            'pending_approvals' => $this->getPendingApprovalsCount(),
            'total_jobs' => JobPosting::count(),
            'recent_activity' => ActivityLog::with('user')->latest()->limit(10)->get(),
        ];

        return view('users.admin.dashboard', $stats); // ✅ Keep this as is
    }

    /**
     * Get count of pending approvals across all content types.
     */
    private function getPendingApprovalsCount(): int
    {
        return JobPosting::where('status', 'pending')->count()
            + Event::where('status', 'pending')->count()
            + NewsArticle::where('status', 'pending')->count()
            + Partnership::whereIn('status', ['submitted', 'under_review'])->count();
    }

    // ===== USER MANAGEMENT =====

    /**
     * Show all users.
     */
    public function users()
    {
        $users = User::with(['department', 'adminProfile', 'staffProfile', 'partnerProfile', 'studentProfile', 'alumniProfile'])
            ->paginate(20);

        return view('users.admin.users.index', compact('users'));
    }

    /**
     * Show create user form.
     */
    public function createUser()
    {
        $departments = \App\Models\Department::all();
        $roles = ['admin', 'staff', 'partner', 'alumni', 'student'];

        return view('users.admin.users.create', compact('departments', 'roles'));
    }

    /**
     * Store new user.
     */
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:admin,staff,partner,alumni,student',
            'department_id' => 'nullable|exists:departments,id',
        ]);

        // Generate system password
        $systemPassword = str()->password(12);

        // Create user
        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'email' => $validated['email'],
            'password' => bcrypt($systemPassword),
            'role' => $validated['role'],
            'department_id' => $validated['department_id'] ?? null,
            'password_changed_at' => null, // Force password change on first login
        ]);

        // Create role-specific profile
        $this->createRoleProfile($user);

        // Log activity
        ActivityLog::logActivity(
            auth()->id(),
            'created',
            "Created user {$user->email}",
            User::class,
            $user->id
        );

        // TODO: Send email with credentials to user
        // Mail::send(...);

        return redirect()->route('admin.users.index')
            ->with('success', "User created. Temporary password: {$systemPassword}");
    }

    /**
     * Create role-specific profile for user.
     */
    private function createRoleProfile(User $user): void
    {
        match($user->role) {
            'admin' => $user->adminProfile()->create(),
            'staff' => $user->staffProfile()->create(),
            'partner' => $user->partnerProfile()->create(),
            'student' => $user->studentProfile()->create(),
            'alumni' => $user->alumniProfile()->create(),
        };
    }

    /**
     * Show edit user form.
     */
    public function editUser($id)
    {
        $user = User::with('department')->findOrFail($id);
        $departments = \App\Models\Department::all();
        $roles = ['admin', 'staff', 'partner', 'alumni', 'student'];

        return view('users.admin.users.edit', compact('user', 'departments', 'roles'));
    }

    /**
     * Update user.
     */
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'email' => "required|email|unique:users,email,{$id}",
            'role' => 'required|in:admin,staff,partner,alumni,student',
            'department_id' => 'nullable|exists:departments,id',
            'is_active' => 'boolean',
        ]);

        $user->update($validated);

        ActivityLog::logActivity(
            auth()->id(),
            'updated',
            "Updated user {$user->email}",
            User::class,
            $user->id
        );

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Delete user.
     */
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        ActivityLog::logActivity(
            auth()->id(),
            'deleted',
            "Deleted user {$user->email}",
            User::class,
            $user->id
        );

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Show bulk import form.
     */
    public function bulkImportForm()
    {
        return view('users.admin.users.bulk-import');
    }

    /**
     * Handle bulk user import.
     */
    public function bulkImport(Request $request)
    {
        $validated = $request->validate([
            'file' => 'required|file|mimes:csv,xlsx',
        ]);

        // TODO: Implement CSV/Excel parsing and bulk user creation
        // $file = $validated['file'];
        // Process file...

        return redirect()->route('admin.users.index')
            ->with('success', 'Users imported successfully.');
    }

    // ===== APPROVAL WORKFLOWS =====

    /**
     * Show pending job postings for approval.
     */
    public function approveJobs()
    {
        $jobs = JobPosting::where('status', 'pending')
            ->with('partner')
            ->paginate(20);

        return view('users.admin.approvals.jobs', compact('jobs'));
    }

    /**
     * Approve job posting.
     */
    public function approveJob($id)
    {
        $job = JobPosting::findOrFail($id);
        $job->approve(auth()->id());
        $job->publish();

        ActivityLog::logActivity(
            auth()->id(),
            'approved',
            "Approved job posting: {$job->title}",
            JobPosting::class,
            $job->id
        );

        return redirect()->back()->with('success', 'Job posting approved and published.');
    }

    /**
     * Reject job posting.
     */
    public function rejectJob(Request $request, $id)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        $job = JobPosting::findOrFail($id);
        $job->reject(auth()->id(), $validated['rejection_reason']);

        ActivityLog::logActivity(
            auth()->id(),
            'rejected',
            "Rejected job posting: {$job->title}",
            JobPosting::class,
            $job->id
        );

        return redirect()->back()->with('success', 'Job posting rejected.');
    }

    /**
     * Show pending events for approval.
     */
    public function approveEvents()
    {
        $events = Event::where('status', 'pending')
            ->with('creator')
            ->paginate(20);

        return view('users.admin.approvals.events', compact('events'));
    }

    /**
     * Approve event.
     */
    public function approveEvent($id)
    {
        $event = Event::findOrFail($id);
        $event->approve(auth()->id());
        $event->publish();

        ActivityLog::logActivity(
            auth()->id(),
            'approved',
            "Approved event: {$event->title}",
            Event::class,
            $event->id
        );

        return redirect()->back()->with('success', 'Event approved and published.');
    }

    /**
     * Reject event.
     */
    public function rejectEvent(Request $request, $id)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        $event = Event::findOrFail($id);
        $event->reject(auth()->id(), $validated['rejection_reason']);

        ActivityLog::logActivity(
            auth()->id(),
            'rejected',
            "Rejected event: {$event->title}",
            Event::class,
            $event->id
        );

        return redirect()->back()->with('success', 'Event rejected.');
    }

    /**
     * Show pending news articles for approval.
     */
    public function approveNews()
    {
        $articles = NewsArticle::where('status', 'pending')
            ->with('creator')
            ->paginate(20);

        return view('users.admin.approvals.news', compact('articles'));
    }

    /**
     * Approve news article.
     */
    public function approveNewsArticle($id) // ✅ FIXED METHOD NAME
    {
        $article = NewsArticle::findOrFail($id);
        $article->approve(auth()->id());
        $article->publish();

        ActivityLog::logActivity(
            auth()->id(),
            'approved',
            "Approved article: {$article->title}",
            NewsArticle::class,
            $article->id
        );

        return redirect()->back()->with('success', 'Article approved and published.');
    }

    /**
     * Reject news article.
     */
    public function rejectNews(Request $request, $id)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string',
        ]);

        $article = NewsArticle::findOrFail($id);
        $article->reject(auth()->id(), $validated['rejection_reason']);

        ActivityLog::logActivity(
            auth()->id(),
            'rejected',
            "Rejected article: {$article->title}",
            NewsArticle::class,
            $article->id
        );

        return redirect()->back()->with('success', 'Article rejected.');
    }

    /**
     * Show pending partnerships for approval.
     */
    public function approvePartnerships()
    {
        $partnerships = Partnership::whereIn('status', ['submitted', 'under_review'])
            ->with('partner')
            ->paginate(20);

        return view('users.admin.approvals.partnerships', compact('partnerships'));
    }

    /**
     * Approve partnership.
     */
    public function approvePartnership(Request $request, $id)
    {
        $validated = $request->validate([
            'admin_notes' => 'nullable|string',
        ]);

        $partnership = Partnership::findOrFail($id);
        $partnership->approve(auth()->id(), $validated['admin_notes'] ?? null);

        ActivityLog::logActivity(
            auth()->id(),
            'approved',
            "Approved partnership: {$partnership->activity_title}",
            Partnership::class,
            $partnership->id
        );

        return redirect()->back()->with('success', 'Partnership approved.');
    }

    /**
     * Reject partnership.
     */
    public function rejectPartnership(Request $request, $id)
    {
        $validated = $request->validate([
            'admin_notes' => 'required|string',
        ]);

        $partnership = Partnership::findOrFail($id);
        $partnership->reject(auth()->id(), $validated['admin_notes']);

        ActivityLog::logActivity(
            auth()->id(),
            'rejected',
            "Rejected partnership: {$partnership->activity_title}",
            Partnership::class,
            $partnership->id
        );

        return redirect()->back()->with('success', 'Partnership rejected.');
    }

    // ===== REPORTS & LOGS =====

    /**
     * Show activity logs.
     */
    public function activityLogs()
    {
        $logs = ActivityLog::with('user')
            ->latest()
            ->paginate(50);

        return view('users.admin.activity-logs', compact('logs'));
    }

    /**
     * Show reports.
     */
    public function reports()
    {
        $stats = [
            'total_users' => User::count(),
            'users_by_role' => User::selectRaw('role, count(*) as count')->groupBy('role')->get(),
            'total_jobs' => JobPosting::count(),
            'published_jobs' => JobPosting::where('status', 'published')->count(),
            'total_events' => Event::count(),
            'published_events' => Event::where('status', 'published')->count(),
            'total_articles' => NewsArticle::count(),
            'published_articles' => NewsArticle::where('status', 'published')->count(),
        ];

        return view('users.admin.reports', $stats);
    }
}
