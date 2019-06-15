<?php

namespace App\Http\Controllers;

use PDF;
use App\Invoice;
use Illuminate\Http\Request;
use App\Repositories\JobQuoteRepo;
use Illuminate\Support\Facades\Auth;

class PdfInvoiceGeneratorController extends Controller
{
    public function create($invoiceId)
    {
        $invoice = Invoice::whereId($invoiceId)->firstOrFail();
        $jobQuoteRepo = new JobQuoteRepo;
        $jobQuote = $jobQuoteRepo->get($invoice->job_quote_id);

        if (Auth::user()->cannot('manage', $invoice) && Auth::user()->cannot('view', $invoice)) {
            abort(403);
        }
        view()->share('jobQuote', $jobQuote);
        view()->share('invoice', $invoice);
        // Set extra option
        PDF::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif']);
        // pass view file
        $pdf = PDF::loadView('pdf.invoice');
        // download pdf
        return $pdf->stream();
    }
}
