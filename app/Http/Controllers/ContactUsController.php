<?php

namespace App\Http\Controllers;

use App\Mail\ContactUs;
use App\ContactUsRecord;
use Illuminate\Http\Request;
use App\NewsLetterSubscription;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\ContactUsRequest;

class ContactUsController extends Controller
{
    public function index()
    {
        return view('contact-us.index');
    }

    public function store(ContactUsRequest $request)
    {
        if ($request->subscribe === 'on') {
            NewsLetterSubscription::firstOrCreate(['email' => $request->email]);
        }

        ContactUsRecord::create([
            'email' => $request->email,
            'message' => $request->message,
            'details' => [
                'name' => $request->name,
                'phone' => $request->phone,
                'source' => $request->source,
                'reason' => $request->reason,
            ]
        ]);

        Mail::to(config('mail.from.address'))->send(new ContactUs($request->all()));

        return redirect()->back()->with('success', true);
    }
}
