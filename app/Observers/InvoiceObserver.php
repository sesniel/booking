<?php

namespace App\Observers;

use Bouncer;
use App\User;
use App\Invoice;
use App\JobPost;
use App\JobQuote;
use Illuminate\Support\Facades\Auth;
use App\Notifications\InvoiceReceived;
use App\Notifications\InvoiceResponse;

class InvoiceObserver
{
    public function created(Invoice $invoice)
    {
        $user = User::whereId($invoice->user_id)->first(['id']);
        Bouncer::allow($user)->toManage($invoice);

        $jobQuote = JobQuote::whereId($invoice->job_quote_id)->first(['id', 'job_post_id']);
        $jobPost = JobPost::whereId($jobQuote->job_post_id)->first(['id', 'user_id']);
        $jobPostOwner = User::whereId($jobPost->user_id)->first(['id']);
        Bouncer::allow($jobPostOwner)->to('view', $invoice);

        $jobQuote->update(['status' => 5]);
        $jobPostOwner->notify(new InvoiceReceived($invoice));
    }
}
