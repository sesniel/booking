<?php

namespace App\Observers;

use Bouncer;
use App\User;
use App\Invoice;
use App\JobPost;
use App\Payment;
use App\JobQuote;
use Illuminate\Support\Facades\Auth;
use App\Notifications\PaymentReceived;
use App\Notifications\JobQuoteInvoiceReceived;

class PaymentObserver
{
    public function created(Payment $payment)
    {
        $invoice = Invoice::whereId($payment->invoice_id)->firstOrFail(['id', 'user_id', 'job_quote_id']);
        $jobQuote = JobQuote::whereId($invoice->job_quote_id)->first();
        $invoiceOwner = User::whereId($invoice->user_id)->firstOrFail(['id']);

        return $invoiceOwner->notify(new PaymentReceived($jobQuote, $invoice, $payment));
    }
}
