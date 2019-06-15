<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'user_id', 'invoice_id', 'api_response', 'status'
    ];

    protected $casts = [
        'api_response' => 'array',
    ];

    public function user()
    {
        $this->belongsTo(User::class);
    }
}
