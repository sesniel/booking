<?php

namespace App\Observers;

use Bouncer;
use App\User;
use App\Couple;
use App\JobPost;
use App\JobQuote;
use Illuminate\Support\Facades\Auth;
use App\Notifications\JobQuoteReceived;
use App\Notifications\JobQuoteResponse;
use App\Notifications\JobQuoteInvoiceReceived;

class JobQuoteObserver
{
    public function created(JobQuote $jobQuote)
    {
        $jobQuoteOwner = User::whereId($jobQuote->user_id)->firstOrFail(['id']);
        Bouncer::allow($jobQuoteOwner)->toManage($jobQuote);

        $jobPost = JobPost::whereId($jobQuote->job_post_id)->firstOrFail(['user_id']);
        $jobPostOwner = User::whereId($jobPost->user_id)->firstOrFail(['id']);
        Bouncer::allow($jobPostOwner)->to('respond', $jobQuote);

        if ($jobQuote->status === 1) {
            $jobPostOwner->notify(new JobQuoteReceived($jobQuote));
        }
    }

    public function updated(JobQuote $jobQuote)
    {
        if (request('status') && request('status') === 'pending response') {
            $jobPost = JobPost::whereId($jobQuote->job_post_id)->firstOrFail(['user_id']);
            $jobPostOwner = User::whereId($jobPost->user_id)->firstOrFail(['id']);
            return $jobPostOwner->notify(new JobQuoteReceived($jobQuote));
        }
    }
}
