<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobTimeRequirement extends Model
{
    protected $fillable = [
        'icon', 'name',
    ];

    public function jobPosts()
    {
        return $this->belongsToMany(JobPost::class);
    }
}
