<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\bookingCashier;
use Illuminate\Support\Facades\Auth;

class CreditCardAuthTokenController extends Controller
{
    public function store()
    {
        $bookingCashier = new bookingCashier;
        $user = Auth::user();
        $user->zip = request('zip');

        $userGatewayAccount = Auth::user()->paymentGatewayUser;

        if (!$userGatewayAccount) {
            $account = $bookingCashier->createUser($user);
            $userGatewayAccount = Auth::user()->paymentGatewayUser()->create([
                'gateway_account_id' => $account['id'],
                'gateway_account_details' => $account,
            ]);
        }

        $card = $bookingCashier->createCardToken($userGatewayAccount->gateway_account_id);

        return response()->json($card);
    }
}
