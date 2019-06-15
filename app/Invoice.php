<?php

namespace App;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'user_id', 'job_quote_id', 'status',
    ];

    protected $status = [
        'draft' => 0,
        'live' => 1,
    ];

    protected $casts = [
        'specs' => 'array',
        'confirmed_dates' => 'array',
    ];

    public function jobQuote()
    {
        return $this->belongsTo(JobQuote::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
