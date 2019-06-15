<?php

namespace App\Http\Controllers;

use App\Event;
use App\Media;
use App\Couple;
use App\Vendor;
use App\JobPost;
use App\Location;
use App\SavedJob;
use App\Expertise;
use App\GlobalList;
use App\JobCategory;
use App\PropertyType;
use App\FavoriteVendor;
use App\JobTimeRequirement;
use App\OtherJobRequirement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function shareProfileGallery($profile)
    {
        return View::share([
            'gallery' => Media::whereCommentableId($profile->id)
                ->whereCommentableType(get_class($profile))
                ->where('meta_key', 'gallery')->get()
        ]);
    }

    public function shareJobPostGallery($jobPost)
    {
        return View::share([
            'gallery' => Media::where('commentable_id', $jobPost->id)
                ->where('commentable_type', get_class($jobPost))
                ->where('meta_key', 'jobPostGallery')
                ->get(['meta_filename'])
        ]);
    }

    public function shareLocations()
    {
        return View::share([
            'locations' => Location::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function shareLocationsByState()
    {
        $locations = Location::orderBy('name')
            ->get(['id', 'name', 'state'])
            ->sortBy('name')
            ->groupBy('state')
            ->toArray();

        ksort($locations);

        return View::share([
            'locations' => collect($locations)->chunk(3)->toArray(),
        ]);
    }

    public function shareExpertises()
    {
        return View::share([
            'expertises' => Expertise::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function shareEditFlag($flag = 'editOn')
    {
        return View::share([
            'editing' => $flag,
        ]);
    }

    public function shareEventTypes()
    {
        return View::share([
            'eventTypes' => Event::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function sharePropertyTypes()
    {
        return View::share([
            'propertyTypes' => PropertyType::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function shareJobCategories()
    {
        return View::share([
            'jobCategories' => JobCategory::orderBy('name')->get(['id', 'template', 'name']),
        ]);
    }

    public function shareOtherJobRequirements()
    {
        return View::share([
            'otherJobRequirements' => OtherJobRequirement::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function shareJobTimeRequirements()
    {
        return View::share([
            'jobTimeRequirements' => JobTimeRequirement::all(['id', 'name']),
        ]);
    }

    public function shareCoupleActiveJobs(Couple $couple)
    {
        return View::share([
            'activeJobs' => JobPost::where('user_id', $couple->userA_id)
                ->liveStatus()
                ->with('category')
                ->get(['id', 'user_id', 'job_category_id'])
                ->split(2)
        ]);
    }

    public function shareFavoriteVendors($userId = null)
    {
        if (!$userId && !Auth::user()) {
            return [];
        }

        return FavoriteVendor::where('user_id', $userId ?: Auth::user()->id)
            ->pluck('vendor_id')->toArray();
    }

    public function shareSavedJobs($userId = null)
    {
        if (!$userId && !Auth::user()) {
            $savedJobs = [];
        } else {
            $savedJobs = SavedJob::where('user_id', $userId ?: Auth::user()->id)
            ->pluck('job_post_id')->toArray();
        }
        return View::share([
            'savedJobs' => $savedJobs,
        ]);
    }
}
