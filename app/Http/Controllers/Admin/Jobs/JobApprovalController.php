<?php

namespace App\Http\Controllers\Admin\Jobs;

use App\Http\Controllers\Controller;

class JobApprovalController extends Controller
{
    public function index()
    {
        return view('users.admin.approvals.jobs');
    }

    public function approve($id)
    {
        /* Blank - functionality later */
    }

    public function reject($id)
    {
        /* Blank - functionality later */
    }
}
