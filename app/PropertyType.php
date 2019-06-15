<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PropertyType extends Model
{
    protected $fillable = [
        'icon', 'name',
    ];

    public function jobPosts()
    {
        return $this->belongsToMany(JobPost::class);
    }
}
