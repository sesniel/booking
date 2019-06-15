<?php

namespace App\Http\Controllers;

use App\JobQuote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardDraftQuotesController extends Controller
{
    public function index()
    {
        $jobQuotes = JobQuote::whereUserId(Auth::user()->id)->with([
            'jobPost' => function ($q) {
                $q->with([
                    'locations' => function ($q) {
                        $q->addSelect(['locations.id', 'name']);
                    },
                    'userProfile' => function ($q) {
                        $q->addSelect(['id', 'userA_id', 'title', 'profile_avatar']);
                    },
                ])->addSelect(['id', 'user_id', 'event_date']);
            },
        ])->select([
            'id', 'user_id', 'job_post_id', 'total', 'message', 'status'
        ])->where('status', 0)->paginate(env('APP_PAGINATION', 10));

        return view('dashboard.vendor.draft-quotes', compact('jobQuotes'));
    }
}
