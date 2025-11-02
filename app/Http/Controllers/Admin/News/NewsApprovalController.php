<?php

namespace App\Http\Controllers\Admin\News;

use App\Http\Controllers\Controller;

class NewsApprovalController extends Controller
{
    public function index()
    {
        return view('users.admin.approvals.news');
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
