<?php

namespace App\Http\Controllers;

use App\JobQuote;

class DashboardQuotesPerJobPostController extends Controller
{
    public function index($jobPostId)
    {
        $jobQuotes = JobQuote::where('job_post_id', $jobPostId)->with([
            'user' => function ($q) {
                $q->with([
                    'vendorProfile' => function ($q) {
                        $q->addSelect(['id', 'user_id', 'business_name', 'profile_avatar']);
                    }
                ])->addSelect(['id']);
            }
        ])->get(['id', 'user_id', 'job_post_id', 'total', 'message', 'duration', 'status']);

        return response()->json($jobQuotes);
    }
}
