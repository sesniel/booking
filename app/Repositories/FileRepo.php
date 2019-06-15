<?php

namespace App\Repositories;

use App\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FileRepo
{
    public function store($userId, $file, $metaKey)
    {
        return File::create([
            'user_id' => $userId,
            'meta_key' => $metaKey,
            'meta_original_filename' => $file->getClientOriginalName(),
            'meta_filename' => $file->store('user-uploads'),
        ]);
    }
}
