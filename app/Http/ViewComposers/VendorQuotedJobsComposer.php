<?php

namespace App\Http\ViewComposers;

use Chat;
use App\User;
use App\Couple;
use App\JobQuote;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class VendorQuotedJobsComposer
{
    public function compose(View $view)
    {
        $view->with(
            'quotedJobs',
            JobQuote::where('user_id', Auth::user()->id)
                ->get(['job_post_id'])
                ->pluck('job_post_id')
                ->toArray()
        );
    }
}
