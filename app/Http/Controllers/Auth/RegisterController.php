<?php

namespace App\Http\Controllers\Auth;

use App\Couple;
use App\Repositories\UserRepo;
use App\NewsLetterSubscription;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make(
            $data,
            [
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|confirmed|string|min:6|max:100',
            'account' => ['required', Rule::in(['vendor', 'couple'])],
            // 'g-recaptcha-response' => 'required|captcha',
        ]
        );
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        if (request('subscribe') === 'on') {
            NewsLetterSubscription::firstOrCreate(['email' => request('email')]);
        }

        return (new UserRepo)->create($data);
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered()
    {
        if (Auth::user()->account === 'admin') {
            return redirect('/admin-dashboard');
        }

        return redirect('/dashboard');
    }
}
