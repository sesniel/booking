<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardReviewsController extends Controller
{
    public function index()
    {
        return view('dashboard.vendor.reviews');
    }
}
