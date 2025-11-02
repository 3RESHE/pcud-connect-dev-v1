<?php

namespace App\Http\Controllers\Admin\Partnerships;

use App\Http\Controllers\Controller;

class PartnershipApprovalController extends Controller
{
    public function index()
    {
        return view('users.admin.approvals.partnerships');
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
