<?php

namespace App;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;

class JobQuote extends Model
{
    protected $fillable = [
        'user_id', 'job_post_id', 'message', 'specs', 'total', 'duration',
        'tc_file_id', 'confirmed_dates', 'apply_gst', 'locked', 'status',
    ];

    protected $statusValue = [
        0 => 'draft',
        1 => 'pending response',
        2 => 'pending admin review',
        3 => 'request changes',
        4 => 'accepted & awaiting invoice',
        5 => 'awaiting payment',
        6 => 'deposit received',
        7 => 'paid',
        8 => 'declined',
    ];

    protected $casts = [
        'specs' => 'array',
        'confirmed_dates' => 'array',
    ];

    public function setDurationAttribute($value)
    {
        $this->attributes['duration'] = Carbon::parse($value)->toDateString();
    }

    public function getDurationAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('F j, Y') : '';
    }

    public function statusText()
    {
        return isset($this->statusValue[$this->status]) ? $this->statusValue[$this->status] : '';
    }

    public function tcFile()
    {
        return $this->hasOne(File::class, 'id', 'tc_file_id');
    }

    public function milestones()
    {
        return $this->hasMany(JobQuoteMilestone::class);
    }

    public function additionalFiles()
    {
        return $this->belongsToMany(File::class);
    }

    public function jobPost()
    {
        return $this->belongsTo(JobPost::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }
}
