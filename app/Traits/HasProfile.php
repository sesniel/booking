<?php

namespace App\Traits;

use App\Media;
use App\Couple;
use App\Vendor;
use Illuminate\Support\Facades\Auth;

trait HasProfile
{
    public function getProfile()
    {
        if (Auth::user()->account === 'vendor') {
            return Auth::user()->vendorProfile()->with(['location', 'expertise'])->first();
        }

        return Couple::where('userA_id', Auth::user()->id)
            ->orWhere('userB_id', Auth::user()->id)
            ->with(['bookedVenue', 'ceremonyVenue', 'receptionVenue'])->first();
    }

    public function getProfileAvatar()
    {
        $data = Media::whereCommentableId(Auth::user()->id)
            ->whereCommentableType('App\\'.ucfirst(Auth::user()->account))
            ->where('meta_key', 'profile_avatar')->first(['meta_filename']);

        return $data ? $data->meta_filename : null;
    }

    public function getProfileCover()
    {
        $data = Media::whereCommentableId(Auth::user()->id)
            ->whereCommentableType('App\\'.ucfirst(Auth::user()->account))
            ->where('meta_key', 'profile_cover')->first(['meta_filename']);

        return $data ? $data->meta_filename : null;
    }

    public function getProfileGallery()
    {
        return $this->getProfile()->media()->where('meta_key', 'gallery')->get();
    }
}
