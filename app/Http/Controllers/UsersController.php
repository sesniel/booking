<?php

namespace App\Http\Controllers;

use Bouncer;
use App\User;
use Illuminate\Http\Request;
use App\Repositories\CoupleRepo;
use App\Repositories\VendorRepo;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateAccountRequest;

class UsersController extends Controller
{
    public function update(Request $request)
    {
        Auth::user()->update($request->all());
    }

    public function updateAccountType(UpdateAccountRequest $request, User $user)
    {
        if (Auth::user()->cannot('update', $user)) {
            abort(403);
        }

        $user->update(['account' => $request->account]);

        Bouncer::allow($user)->toManage($user->getUserManageableModels());

        if ($user->hasProfile()) {
            return redirect('/dashboard');
        }

        if ($user->account === 'couple') {
            with(new CoupleRepo)->updateOrCreate(['user_id' => $user->id]);
        }

        if ($user->account === 'vendor') {
            with(new VendorRepo)->create(['user_id' => $user->id]);
        }

        return redirect('/dashboard');
    }
}
