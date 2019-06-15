<?php

namespace App\Http\Controllers;

use App\JobQuote;
use Illuminate\Support\Facades\Auth;
use App\Search\JobPost\JobQuoteSearchManager;

class ReceivedQuotesController extends Controller
{
    public function index()
    {
        $jobQuotes = JobQuoteSearchManager::keywordSearch(request('q'));

        return view('dashboard.couple.received-quotes', compact('jobQuotes'));
    }
}
