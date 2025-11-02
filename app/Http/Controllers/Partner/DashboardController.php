<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\JobPosting;
use App\Models\Partnership;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display partner dashboard
     */
    public function index()
    {
        $partner = auth()->user();

        $data = [
            'active_jobs' => JobPosting::where('partner_id', $partner->id)
                ->where('status', 'published')
                ->count(),

            'active_partnerships' => Partnership::where('partner_id', $partner->id)
                ->where('status', 'approved')
                ->count(),

            'total_applications' => JobPosting::where('partner_id', $partner->id)
                ->withCount('applications')
                ->get()
                ->sum('applications_count'),

            'pending_reviews' => JobPosting::where('partner_id', $partner->id)
                ->where('status', 'pending')
                ->count() + Partnership::where('partner_id', $partner->id)
                ->whereIn('status', ['submitted', 'under_review'])
                ->count(),

            'recent_jobs' => JobPosting::where('partner_id', $partner->id)
                ->latest('created_at')
                ->take(3)
                ->get(),

            'recent_partnerships' => Partnership::where('partner_id', $partner->id)
                ->latest('created_at')
                ->take(3)
                ->get(),
        ];

        return view('users.partner.dashboard', $data);
    }
}
