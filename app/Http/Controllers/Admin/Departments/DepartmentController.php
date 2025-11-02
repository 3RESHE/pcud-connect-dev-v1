<?php

namespace App\Http\Controllers\Admin\Departments;

use App\Http\Controllers\Controller;

class DepartmentController extends Controller
{
    public function index() { return view('users.admin.departments.index'); }
    public function create() { return view('users.admin.departments.create'); }
    public function store() { /* Blank - functionality later */ }
    public function show($id) { /* Blank - functionality later */ }
    public function edit($id) { return view('users.admin.departments.edit'); }
    public function update($id) { /* Blank - functionality later */ }
    public function destroy($id) { /* Blank - functionality later */ }
}
