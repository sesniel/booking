<?php

namespace App\Repositories;

use Bouncer;
use App\User;
use App\Media;
use App\Couple;
use App\JobPost;
use App\Location;
use App\PropertyType;
use Illuminate\Support\Facades\Auth;

class JobPostRepo
{
    private $newJobPost = null;

    public function create(array $data)
    {
        if (Auth::user()->account === 'couple') {
            $couple = Couple::where('userA_id', Auth::user()->id)
                ->orWhere('userB_id', Auth::user()->id)
                ->firstOrFail(['id', 'userA_id', 'userB_id']);

            $userA = User::whereId($couple->userA_id)->first(['id']);
            $this->newJobPost = $userA->jobPosts()->create($data);

            Bouncer::allow($userA)->toManage($this->newJobPost);
            $userB = $couple->userB_id ? User::whereId($couple->userB_id)->first(['id']) : null;

            if ($userB) {
                Bouncer::allow($userB)->toManage($this->newJobPost);
            }
        }

        if (Auth::user()->account === 'admin') {
            $this->newJobPost = Auth::user()->jobPosts()->create($data);
            Bouncer::allow(Auth::user())->toManage($this->newJobPost);

            return $this;
        }

        if (isset($data['locations'])) {
            $loc = Location::whereIn('name', $data['locations'])->get(['id'])->pluck('id')->toArray();
            $this->newJobPost->locations()->sync($loc);
        }

        if (isset($data['property_types'])) {
            $propertyTypes = explode(',', $data['property_types']);
            $prop = PropertyType::whereIn('name', $propertyTypes)->get(['id'])->pluck('id');

            $this->newJobPost->propertyTypes()->sync($prop);
        }

        if (isset($data['other_requirements'])) {
            $this->newJobPost->otherJobRequirements()->sync($data['other_requirements']);
        }

        return $this;
    }

    public function update(array $data, $jobPost)
    {
        $this->newJobPost = $jobPost;

        if (isset($data['locations'])) {
            $loc = Location::whereIn('name', $data['locations'])->get(['id'])->pluck('id')->toArray();
            $this->newJobPost->locations()->sync($loc);
        } else {
            $this->newJobPost->locations()->sync([]);
        }

        if (isset($data['property_types'])) {
            $propertyTypes = explode(',', $data['property_types']);
            $prop = PropertyType::whereIn('name', $propertyTypes)->get(['id'])->pluck('id');

            $this->newJobPost->propertyTypes()->sync($prop);
        } else {
            $this->newJobPost->propertyTypes()->sync([]);
        }

        if (isset($data['other_requirements'])) {
            $this->newJobPost->otherJobRequirements()->sync($data['other_requirements']);
        } else {
            $this->newJobPost->otherJobRequirements()->sync([]);
        }

        $jobPost->update($data);

        return $this;
    }

    public function withPhotos($files)
    {
        if ($files) {
            foreach ($files as $file) {
                $filename = $file->store('user-uploads');
                Media::create([
                    'commentable_id' => $this->newJobPost->id,
                    'commentable_type' => 'App\\JobPost',
                    'meta_key' => 'jobPostGallery',
                    'meta_filename' => $filename
                ]);
            }
        }

        return $this;
    }

    public function getJobPost()
    {
        return $this->newJobPost;
    }

    public function getJobPostId()
    {
        return $this->newJobPost->id;
    }

    public function search($filter = null, $profile)
    {
        $query = JobPost::whereStatus(request('status') === 'live' ? 1 : 0)
            ->where(function ($q) use ($profile) {
                if ($profile->userA_id) {
                    $q->where('user_id', $profile->userA_id);
                }
                if ($profile->userB_id) {
                    $q->orWhere('user_id', $profile->userB_id);
                }
            });

        if ($filter) {
            $query->where(function ($q) use ($filter) {
                $q->where('specifics', 'like', '%'.$filter.'%')
                    ->orWhere('number_of_guests', $filter)
                    ->orWhere('budget', $filter)
                    ->orWhereHas('category', function ($q) use ($filter) {
                        $q->where('name', 'like', '%'.$filter.'%');
                    })
                    ->orWhereHas('locations', function ($q) use ($filter) {
                        $q->where('name', 'like', '%'.$filter.'%');
                    })
                    ->orWhereHas('event', function ($q) use ($filter) {
                        $q->where('name', 'like', '%'.$filter.'%');
                    });
            });
        }

        return $query->with(['category', 'event', 'locations'])->orderBy('created_at', 'DESC')->paginate(10);
    }
}
