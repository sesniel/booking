<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = [
        'icon', 'name',
    ];

    public function jobPosts()
    {
        return $this->belongsToMany(JobPost::class);
    }

    public function vendors()
    {
        return $this->belongsToMany(Location::class);
    }
}
