<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class JobPost extends Model
{
    protected $fillable = [
        'user_id', 'job_category_id', 'event_id', 'event_date', 'budget', 'number_of_guests',
        'time_required', 'required_address', 'specifics', 'shipping_address', 'completion_date',
        'job_time_requirement_id', 'status',
    ];

    protected $casts = [
        'other_requirements' => 'array',
        'shipping_address' => 'array',
    ];

    protected $status = [
        'draft' => 0, 'live' => 1, 'closed' => 2,
    ];

    public function setStatusAttribute($value)
    {
        $status = isset($this->status[$value]) ? $this->status[$value] : $this->status['draft'];

        return $this->attributes['status'] = $status;
    }

    public function getStatusAttribute($value)
    {
        return array_search($value, $this->status);
    }

    public function setNumberOfGuestsAttribute($value)
    {
        return $this->attributes['number_of_guests'] = (int) $value;
    }

    public function setEventDateAttribute($value)
    {
        $this->attributes['event_date'] = Carbon::parse($value)->toDateString();
    }

    public function getEventDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('F j, Y') : '';
    }

    public function setCompletionDateAttribute($value)
    {
        $this->attributes['completion_date'] = Carbon::parse($value)->toDateString();
    }

    public function getCompletionDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('F j, Y') : '';
    }

    public function getOtherRequirementsAttribute($value)
    {
        return $value ? json_decode($value) : [];
    }

    public function scopeLiveStatus($q)
    {
        return $q->whereStatus(1);
    }

    public function scopeOfStatus($q, $status = 'live')
    {
        $status = isset($this->status[$status]) ? $this->status[$status] : 1;
        return $q->whereStatus($status);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function event()
    {
        return $this->hasOne(Event::class, 'id', 'event_id');
    }

    public function category()
    {
        return $this->hasOne(JobCategory::class, 'id', 'job_category_id');
    }

    public function timeRequirement()
    {
        return $this->hasOne(JobTimeRequirement::class, 'id', 'job_time_requirement_id');
    }

    public function locations()
    {
        return $this->belongsToMany(Location::class);
    }

    public function propertyTypes()
    {
        return $this->belongsToMany(PropertyType::class);
    }

    public function otherJobRequirements()
    {
        return $this->belongsToMany(OtherJobRequirement::class);
    }

    public function quotes()
    {
        return $this->hasMany(JobQuote::class);
    }

    public function userProfile()
    {
        return $this->belongsTo(Couple::class, 'user_id', 'userA_id');
    }
}
