<?php

namespace App\Observers;

use Bouncer;
use App\User;
use App\Couple;
use App\Mail\WelcomeEmail;
use App\Mail\NewUserSignup;
use App\Repositories\CoupleRepo;
use App\Repositories\VendorRepo;
use Illuminate\Support\Facades\Mail;

class UserObserver
{
    public function created(User $user)
    {
        Bouncer::allow($user)->toManage($user);

        if ($user->account === 'admin') {
            return Bouncer::allow($user)->everything();
        }

        foreach ($user->getUserManageableModels() as $model) {
            Bouncer::allow($user)->to('create', $model);
        }

        if ($user->account === 'couple') {
            if (request('linkUserId')) {
                $couple = Couple::where('userA_id', request('linkUserId'))->first();

                $couple->update([
                    'userB_id' => $user->id,
                ]);
            } else {
                (new CoupleRepo)->create(['user_id' => $user->id]);
            }
        }

        if ($user->account === 'vendor') {
            (new VendorRepo)->create(['user_id' => $user->id]);
        }

        Mail::to($user->email)->send(new WelcomeEmail($user));

        Mail::to(config('mail.from.address'))->send(new NewUserSignup($user));
    }
}
