<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Couple extends Model
{
    protected $fillable = [
        'userA_id', 'userB_id', 'title', 'desc', 'contact_email', 'contact_phone_number',
        'booked_venue_id', 'ceremony_venue_id', 'reception_venue_id', 'total_guest_invited',
        'total_guest_confirmed', 'booked_date', 'profile_avatar', 'profile_cover'
    ];

    protected $casts = [
        'expertise' => 'array',
    ];

    public function getProfileAvatarAttribute($value)
    {
        if (!$value) {
            return null;
        }

        if (filter_var($value, FILTER_VALIDATE_URL) === false) {
            return Storage::url($value);
        }

        return $value;
    }

    public function getRawProfileAvatarFilename()
    {
        $parts = explode('/', $this->profile_avatar);

        return end($parts);
    }

    public function getProfileCoverAttribute($value)
    {
        if (!$value) {
            return null;
        }

        if (filter_var($value, FILTER_VALIDATE_URL) === false) {
            return Storage::url($value);
        }

        return $value;
    }

    public function getRawProfileCoverFilename()
    {
        $parts = explode('/', $this->profile_cover);

        return end($parts);
    }

    public function media()
    {
        return $this->morphMany('App\Media', 'commentable');
    }

    public function getFillableFields()
    {
        return $this->fillable;
    }

    public function setbookedDateAttribute($value)
    {
        $this->attributes['booked_date'] = Carbon::parse($value)->toDateString();
    }

    public function getbookedDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('F j, Y') : '';
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function bookedVenue()
    {
        return $this->hasOne(Location::class, 'id', 'booked_venue_id');
    }

    public function ceremonyVenue()
    {
        return $this->hasOne(Location::class, 'id', 'ceremony_venue_id');
    }

    public function receptionVenue()
    {
        return $this->hasOne(Location::class, 'id', 'reception_venue_id');
    }
}
