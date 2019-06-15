<?php

namespace App\Http\Controllers;

use App\User;
use App\Couple;
use App\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnboardingController extends Controller
{
    public function store(Request $request)
    {
        Auth::user()->settings()->create(['settings' => ['onboarding' => true]]);

        if (Auth::user()->account === 'vendor') {
            if ($request->avatar) {
                $request['profile_avatar'] = $request->avatar->store('user-uploads');
            }

            $vendor = Vendor::where('user_id', Auth::user()->id)->first();
            $vendor->update($request->all());

            if ($request->expertises) {
                $vendor->expertise()->sync($request->expertises);
            }

            if ($request->locations) {
                $vendor->locations()->sync($request->locations);
            }
        }

        if (Auth::user()->account === 'couple') {
            Auth::user()->update([
                'lname' => $request->userA_lname,
                'fname' => $request->userA_fname,
            ]);


            $request->merge(['linkUserId' => Auth::user()->id]);

            if ($request->userB_email) {
                $existingUser = User::whereEmail($request->userB_email)->first();

                if ($existingUser) {
                    $existingUser->update([
                        'lname' => $request->userB_lname,
                        'fname' => $request->userB_fname,
                    ]);
                    $couple = Couple::where('userA_id', Auth::user()->id)->first();
                    $request->merge([
                        'userB_id' => $existingUser->id
                    ]);
                } else {
                    User::create([
                        'email' => $request->userB_email,
                        'lname' => $request->userB_lname,
                        'fname' => $request->userB_fname,
                        'password' => bin2hex(random_bytes(10)),
                        'account' => 'couple',
                    ]);
                }
            }

            if ($request->avatar) {
                $request['profile_avatar'] = $request->avatar->store('user-uploads');
            }

            $request->merge([
                'title' => sprintf('%s & %s', Auth::user()->fname, $request->userB_fname)
            ]);

            $couple = Couple::where('userA_id', Auth::user()->id)->first();
            $couple->update($request->all());
        }

        return redirect()->back();
    }
}
