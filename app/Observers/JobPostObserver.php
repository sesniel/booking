<?php

namespace App\Observers;

use Bouncer;
use App\User;
use App\Couple;
use App\Vendor;
use App\JobPost;
use App\Location;
use App\Notifications\NewJobPosted;
use Illuminate\Support\Facades\Auth;

class JobPostObserver
{
    public function created(JobPost $jobPost)
    {
        $jobPostOwner = $jobPost->user;

        if ($jobPostOwner->account === 'admin') {
            return Bouncer::allow($jobPostOwnerP)->toManage($jobPost);
        }

        if ($jobPostOwner->account !== 'couple') {
            return false;
        }

        $couple = Couple::where('userA_id', $jobPostOwner->id)
            ->orWhere('userB_id', $jobPostOwner->id)->first(['id', 'userA_id', 'userB_id']);

        $userA = User::whereId($couple->userA_id)->first(['id']);
        Bouncer::allow($userA)->toManage($jobPost);

        if ($couple->userB_id) {
            $userB = User::whereId($couple->userB_id)->first(['id']);
            Bouncer::allow($userB)->toManage($jobPost);
        }

        $this->sendJobAlert($jobPost);
    }

    public function sendJobAlert($jobPost)
    {
        $fVendors = $jobPost->user->favoriteVendors->pluck('vendor_id')->toArray();
        if (request('locations') || request('job_category_id')) {
            $vendors = Vendor::where(function ($q) {
                if (request('job_category_id')) {
                    $q->whereHas('expertise', function ($q) {
                        $q->where('expertises.id', request('job_category_id'));
                    });
                }
                if (request('locations')) {
                    $locationIds = Location::whereIn('name', request('locations'))->get()->pluck('id');
                    $q->orWhere('location_id', $locationIds);
                }
            })->get(['id', 'user_id'])->pluck('id')->toArray();
            $fVendors = array_unique(array_merge($fVendors, $vendors));
        }

        if (count($fVendors) > 0) {
            $vIds = Vendor::whereIn('id', $fVendors)->get(['id', 'user_id'])->pluck('user_id')->toArray();
            $users = User::whereIn('id', $vIds)->get();
            foreach ($users as $user) {
                $user->notify(new NewJobPosted($jobPost));
            }
        }
    }
}
