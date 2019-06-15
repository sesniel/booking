<?php

namespace App\Http\Controllers;

use App\JobQuote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Search\JobPost\JobQuoteSearchManager;

class DashboardSentQuotesController extends Controller
{
    public function index()
    {
        $jobQuotes = JobQuoteSearchManager::keywordSearch(request('q'));

        return view('dashboard.vendor.sent-quotes', compact('jobQuotes'));
    }
}
