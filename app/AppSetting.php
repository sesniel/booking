<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    protected $fillable = [
        'meta_type', 'meta_key', 'meta_value', 'status',
    ];
}
