<?php

namespace App\Http\Controllers\Admin\Analytics;

use App\Http\Controllers\Controller;

class ActivityLogController extends Controller
{
    public function index()
    {
        return view('users.admin.activity-logs');
    }
}
