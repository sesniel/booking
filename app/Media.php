<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    protected $fillable = [
        'commentable_id', 'commentable_type', 'meta_key', 'meta_title',
        'meta_description', 'meta_filename',
    ];

    public function getFileUrl()
    {
        if (filter_var($this->meta_filename, FILTER_VALIDATE_URL) === false) {
            return Storage::url($this->meta_filename);
        }

        return $this->meta_filename;
    }

    public function commentable()
    {
        return $this->morphTo();
    }
}
