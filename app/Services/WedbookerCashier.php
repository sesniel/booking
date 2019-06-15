<?php

namespace App\Services;

use PromisePay\PromisePay;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class bookingCashier
{
    public function __construct()
    {
        PromisePay::Configuration()->environment(env('PROMISEPAY_ENVIRONMENT'));
        PromisePay::Configuration()->login(env('PROMISEPAY_EMAIL'));
        PromisePay::Configuration()->password(env('PROMISEPAY_TOKEN'));
    }

    public function createUser($user)
    {
        $randomletters = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 10);
        $userId = sprintf('%s-%s-%s', $user->id, $randomletters, time());

        return PromisePay::User()->create(array(
            'id'            => $userId,
            'first_name'    => $user->fname,
            'last_name'     => $user->lname,
            'email'         => $user->email,
            'zip'           => $user->zip,
            'country'       => 'AUS'
        ));
    }

    public function createCardToken($userGatewayId, $type = 'card')
    {
        return PromisePay::Token()->generateCardToken([
            'token_type' => $type,
            'user_id' => $userGatewayId
        ]);
    }

    public function pay($amount = null, $accountId = null, $zip = null)
    {
        return PromisePay::Charges()->create([
                "account_id" => $accountId,
                "amount" => $amount,
                "email" => Auth::user()->email,
                "zip" => $zip,
                "country" => 'AUS',
                "currency" => 'AUD',
                "retain_account" => true
            ]);
    }
}
