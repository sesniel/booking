<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $this->shareLocationsByState();
        $this->shareExpertises();
        $this->shareEditFlag('editOff');

        return view(sprintf('dashboard.%s.home', Auth::user()->account));
    }
}
