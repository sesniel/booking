<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardAcceptedJobQuotesController extends Controller
{
    public function index()
    {
        return view('dashboard.vendor.accepted-job-quotes');
    }
}
