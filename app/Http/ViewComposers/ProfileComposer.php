<?php

namespace App\Http\ViewComposers;

use App\Couple;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class ProfileComposer
{
    public function compose(View $view)
    {
        $view->with('profile', $this->getProfile());
    }

    public function getProfile()
    {
        if (!Auth::user()) {
            return null;
        }

        if (Auth::user()->account === 'couple') {
            return Couple::where('userA_id', Auth::user()->id)->with([
                'ceremonyVenue',
                'bookedVenue',
                'receptionVenue'
            ])->orWhere('userB_id', Auth::user()->id)->firstOrFail();
        }

        if (Auth::user()->account === 'vendor') {
            return Auth::user()->vendorProfile()->with([
                'locations',
                'expertise'
            ])->firstOrFail();
        }

        return null;
    }
}
