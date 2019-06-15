<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentGatewayUser extends Model
{
    protected $fillable = [
        'user_id', 'gateway_account_id', 'gateway_account_details',
    ];

    protected $casts = [
        'gateway_account_details' => 'array',
    ];

    public function user()
    {
        return $this->belongsto(User::class)->withDefault();
    }
}
