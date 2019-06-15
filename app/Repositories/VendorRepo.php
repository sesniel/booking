<?php

namespace App\Repositories;

use Bouncer;
use App\User;
use App\Vendor;
use App\Location;
use App\Expertise;
use App\Repositories\FileRepo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VendorRepo
{
    public function create(array $data)
    {
        return Vendor::create($data);
    }

    public function update(array $data)
    {
        $vendor = Vendor::whereId($data['vendorId'])->firstOrFail();

        if (isset($data['expertises'])) {
            $vendor->expertise()->sync(
                Expertise::whereIn('name', json_decode($data['expertises']))->get(['id'])->pluck('id')
            );
        }

        if (isset($data['locations'])) {
            $vendor->locations()->sync(
                Location::whereIn('name', json_decode($data['locations']))->get(['id'])->pluck('id')
            );
        }

        return $vendor->update($data);
    }

    public function updateProfileAvatar($file, Vendor $vendor)
    {
        if ($file) {
            $oldFile = $vendor->getRawProfileAvatarFilename();
            if ($vendor->profile_avatar && $oldFile) {
                Storage::delete(sprintf('user-uploads/%s', $oldFile));
            }
            return $vendor->update(['profile_avatar' => $this->upload($file)]);
        }
    }

    public function updateProfileCover($file, Vendor $vendor)
    {
        if ($file) {
            $oldFile = $vendor->getRawProfileCoverFilename();
            if ($vendor->profile_cover && $oldFile) {
                Storage::delete(sprintf('user-uploads/%s', $oldFile));
            }
            return $vendor->update(['profile_cover' => $this->upload($file)]);
        }
    }

    public function updateTC($file, User $user)
    {
        if ($file) {
            return with(new FileRepo)->store($user->id, $file, 'tc');
        }
    }

    public function upload($file)
    {
        $staticFilename = request('staticFilename') && request('staticFilename') == true;

        if ($staticFilename) {
            return $file->storeAs(
                'user-uploads',
                $file->getClientOriginalName()
            );
        }

        return $file->store('user-uploads');
    }
}
