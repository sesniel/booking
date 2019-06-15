<?php

namespace App\Observers;

use Bouncer;
use App\User;
use App\Vendor;
use Illuminate\Support\Facades\Auth;

class VendorObserver
{
    public function created(Vendor $vendor)
    {
        $user = User::whereId($vendor->user_id)->first(['id']);
        Bouncer::allow($user)->toManage($vendor);
    }
}
