<?php

namespace App\Repositories;

use App\Media;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MediaRepo
{
    public function store($meta, $file, $owningModel)
    {
        $staticFilename = request('staticFilename') && request('staticFilename') == true;

        if ($staticFilename) {
            $newFilename = $file->storeAs(
                'user-uploads',
                $file->getClientOriginalName()
            );
        } else {
            $newFilename = $file->store('user-uploads');
        }

        $meta['meta_filename'] = $newFilename;
        $meta['commentable_id'] = $owningModel->id;
        $meta['commentable_type'] = get_class($owningModel);

        return Media::create($meta);
    }

    public function storeMany($meta, $files, $owningModel)
    {
        $meta['commentable_type'] = get_class($owningModel);
        $meta['commentable_id'] = $owningModel->id;

        $media = [];

        foreach ($files as $file) {
            $meta['meta_filename'] = $file->store('user-uploads');
            $media[] = Media::create($meta);
        }

        return $media;
    }

    public function destroy($mediaId)
    {
        $media = Media::whereId($mediaId)->firstOrFail();
        Storage::delete($media->meta_filename);

        return $media->delete();
    }
}
