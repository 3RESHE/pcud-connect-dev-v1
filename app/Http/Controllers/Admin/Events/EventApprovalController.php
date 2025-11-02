<?php

namespace App\Http\Controllers\Admin\Events;

use App\Http\Controllers\Controller;

class EventApprovalController extends Controller
{
    public function index()
    {
        return view('users.admin.approvals.events');
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
