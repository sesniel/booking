<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactUsRecord extends Model
{
    protected $fillable = [
        'email', 'details', 'message',
    ];

    protected $casts = [
        'details' => 'array',
    ];
}
