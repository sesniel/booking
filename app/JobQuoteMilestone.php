<?php

namespace App;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;

class JobQuoteMilestone extends Model
{
    protected $fillable = [
        'job_quote_id', 'percent', 'due_date', 'desc', 'paid'
    ];

    public function jobQuote()
    {
        return $this->belongsTo(JobQuote::class);
    }

    public function setDueDateAttribute($value)
    {
        $this->attributes['due_date'] = Carbon::parse($value)->toDateString();
    }

    public function getDueDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('F j, Y') : '';
    }
}
