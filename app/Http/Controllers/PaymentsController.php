<?php

namespace App\Http\Controllers;

use App\Invoice;
use App\JobQuote;
use App\JobQuoteMilestone;
use Illuminate\Http\Request;
use App\Services\bookingCashier;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StorePaymentRequest;

class PaymentsController extends Controller
{
    public function index()
    {
        return view('dashboard.vendor.payments');
    }

    public function create()
    {
        $invoice = Invoice::whereId(request('invoice_id'))->with([
            'jobQuote' => function ($q) {
                $q->with([
                    'milestones' => function ($q) {
                        $q->where('paid', 0);
                    },
                    'jobPost' => function ($q) {
                        $q->addSelect([
                        'id', 'user_id', 'specifics', 'budget', 'event_id', 'event_date', 'job_category_id', 'status'
                    ])->with([
                        'category' => function ($q) {
                            $q->addSelect(['id', 'name']);
                        },
                        'locations' => function ($q) {
                            $q->addSelect(['locations.id', 'name']);
                        },
                        'userProfile' => function ($q) {
                            $q->addSelect(['id', 'userA_id', 'title', 'profile_avatar']);
                        }
                    ]);
                    }]);
            },
            'user' => function ($q) {
                $q->addSelect(['id'])->with([
                    'vendorProfile' => function ($q) {
                        $q->addSelect(['id', 'user_id', 'business_name', 'location_id', 'profile_avatar'])
                            ->with(['location' => function ($q) {
                                $q->addSelect(['id', 'name']);
                            }]);
                    }
                ]);
            },
        ])->firstOrFail();

        return view('payments.create', compact('invoice', 'accountSetting'));
    }

    public function store(Request $request)
    {
        $invoice = Invoice::whereId($request->invoice_id)->firstOrFail();
        $jobQuote = JobQuote::whereId($invoice->job_quote_id)->firstOrFail(['id', 'total']);
        $jobQuoteMilestone = JobQuoteMilestone::whereId($request->quote_milestone_id)
            ->where('job_quote_id', $jobQuote->id)
            ->where('paid', 0)->firstOrFail();

        $amount = (($jobQuoteMilestone->percent / 100) * $jobQuote->total) * 100;

        $result = (new bookingCashier)->pay($amount, $request->account_id, $request->zip);

        if ($result && $result['status'] === 22500 && $result['state'] === 'completed') {
            $jobQuoteMilestone->update(['paid' => 1]);

            Auth::user()->payments()->create([
                'api_response' => $result,
                'invoice_id' => $invoice->id,
            ]);

            $hasBalance = $hasBalance = JobQuoteMilestone::where('job_quote_id', $jobQuote->id)
                ->where('paid', 0)->exists();

            if ($hasBalance) {
                $jobQuote->update(['status' => 6]);
            } else {
                $jobQuote->update(['status' => 7]);
            }
        }

        return response()->json($result);
    }
}
