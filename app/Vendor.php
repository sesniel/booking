<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Vendor extends Model
{
    protected $fillable = [
        'user_id', 'business_name', 'trading_name', 'desc', 'abn', 'contact_name',
        'contact_email', 'location_id', 'website', 'contact_phone_number', 'profile_cover',
        'profile_avatar',
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

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
    }

    public function expertise()
    {
        return $this->belongsToMany(Expertise::class);
    }

    public function locations()
    {
        return $this->belongsToMany(Location::class);
    }

    public function location()
    {
        return $this->hasOne(Location::class, 'id', 'location_id')->withDefault();
    }
}
