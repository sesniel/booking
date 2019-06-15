<?php

namespace App\Http\Controllers;

use App\JobPost;
use App\JobQuote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConfirmedBookingsController extends Controller
{
    public function index()
    {
        if (Auth::user()->account === 'couple') {
            $bookings = $this->coupleConfirmedBookings();
        } elseif (Auth::user()->account === 'vendor') {
            $bookings = $this->vendorConfirmedBookings();
        } else {
            abort(404);
        }

        $view = sprintf('dashboard.%s.confirmed-bookings', Auth::user()->account);
        return view($view);
    }

    public function coupleConfirmedBookings()
    {
        $jobPosts = JobPost::where('user_id', Auth::user()->id)
            ->get(['id'])->pluck('id')->toArray();
        return $jobQuotes = JobQuote::whereIn('job_post_id', $jobPosts)
            ->whereIn('status', [4, 5])
            ->with([
                'jobPost',
            ])->get();
    }

    public function vendorConfirmedBookings()
    {
    }
}
