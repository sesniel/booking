<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobCategory extends Model
{
    protected $fillable = [
        'template', 'icon', 'name',
    ];

    public function jobPosts()
    {
        return $this->belongsToMany(JobPost::class);
    }
}
