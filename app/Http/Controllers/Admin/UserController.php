<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = Report::query();

        $report = $query->get();

        return view('admin.pages.kelola-user.index', compact('report'));
    }
}
