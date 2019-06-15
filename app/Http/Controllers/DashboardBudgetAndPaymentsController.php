<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardBudgetAndPaymentsController extends Controller
{
    public function index()
    {
        return view('dashboard.couple.budget-and-payments');
    }
}
