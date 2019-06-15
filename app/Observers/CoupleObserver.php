<?php

namespace App\Observers;

use Bouncer;
use App\User;
use App\Couple;
use Illuminate\Support\Facades\Auth;

class CoupleObserver
{
    public function created(Couple $couple)
    {
        $userA = User::whereId($couple->userA_id)->first(['id']);
        Bouncer::allow($userA)->toManage($couple);
    }
}
