<?php

namespace App\Http\Controllers;

use App\Invoice;
use App\JobPost;
use App\JobQuote;
use Illuminate\Http\Request;
use App\Repositories\JobQuoteRepo;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreInvoiceRequest;

class InvoicesController extends Controller
{
    public function create()
    {
        $jobQuoteRepo = new JobQuoteRepo;
        $jobQuote = $jobQuoteRepo->get(request('job_quote_id'));

        if (Auth::user()->cannot('manage', $jobQuote)) {
            abort(403);
        }

        return view('invoices.create', compact('jobQuote'));
    }

    public function store(StoreInvoiceRequest $request)
    {
        $jobQuote = JobQuote::whereId($request->job_quote_id)->firstOrFail();

        if (Auth::user()->cannot('manage', $jobQuote)) {
            abort(403);
        }

        $invoice = Invoice::firstOrCreate([
            'user_id' => $jobQuote->user_id,
            'job_quote_id' => $jobQuote->id,
        ]);

        return redirect(sprintf('/invoices/%s', $invoice->id));
    }

    public function show($invoiceId)
    {
        $invoice = Invoice::whereId($invoiceId)->firstOrFail();
        $jobQuoteRepo = new JobQuoteRepo;
        $jobQuote = $jobQuoteRepo->get($invoice->job_quote_id);

        if (Auth::user()->cannot('manage', $invoice) && Auth::user()->cannot('view', $invoice)) {
            abort(403);
        }

        return view('invoices.show', compact('jobQuote', 'invoice'));
    }
}
