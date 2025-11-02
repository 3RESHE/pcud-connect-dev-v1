<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index() { return view('users.admin.users.index'); }
    public function create() { return view('users.admin.users.create'); }
    public function store() { /* Blank - functionality later */ }
    public function show($id) { /* Blank - functionality later */ }
    public function edit($id) { return view('users.admin.users.edit'); }
    public function update($id) { /* Blank - functionality later */ }
    public function destroy($id) { /* Blank - functionality later */ }
    public function bulkImportForm() { return view('users.admin.users.bulk-import'); }
    public function bulkImport() { /* Blank - functionality later */ }
}
