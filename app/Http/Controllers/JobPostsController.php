<?php

namespace App\Http\Controllers;

use App\Vendor;
use App\JobPost;
use App\SavedJob;
use App\Repositories\JobPostRepo;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreJobPostRequest;
use App\Search\JobPost\JobPostSearchManager;

class JobPostsController extends Controller
{
    public function index()
    {
        $jobPosts = JobPostSearchManager::applyFilters(request()->all())
            ->with([
                'event',
                'category',
                'locations',
                'propertyTypes',
                'userProfile:id,userA_id,title,profile_avatar',
            ])->orderBy('created_at', 'DESC')
            ->paginate(env('APP_PAGINATION', 10));

        $this->shareLocations();
        $this->shareJobCategories();
        $this->shareSavedJobs();

        return view('job-posts.index', compact('jobPosts'));
    }

    public function create()
    {
        if (Auth::user()->cannot('create', JobPost::class)) {
            abort(403);
        }

        $this->shareExpertises();
        $this->shareLocationsByState();
        $this->shareEventTypes();
        $this->shareJobCategories();
        $this->sharePropertyTypes();
        $this->shareJobTimeRequirements();
        $this->shareOtherJobRequirements();

        return view('job-posts.create');
    }

    public function store(StoreJobPostRequest $request)
    {
        if (Auth::user()->cannot('create', JobPost::class)) {
            abort(403);
        }

        $newJobPostId = (new JobPostRepo)->create($request->all())
            ->withPhotos($request->photos)
            ->getJobPostId();

        if ($request->status === 'draft') {
            return redirect('/dashboard/job-posts/draft');
        }

        return redirect(sprintf('/job-posts/%s', $newJobPostId))
            ->with(
                'success',
                'Thank you for posting your job. Your job has now been sent to well-matched booking businesses, including your “favourites”.'
            );
    }

    public function show($jobPostId)
    {
        $jobPost = JobPost::whereId($jobPostId)->with([
            'event',
            'category',
            'locations',
            'propertyTypes',
            'otherJobRequirements',
            'timeRequirement',
            'userProfile:id,userA_id,title,profile_avatar',
        ])->firstOrFail();

        if (request('notification_id')) {
            Auth::user()->notifications()->where('id', request('notification_id'))
                ->update(['read_at' => now()]);
        }

        if (Auth::user()) {
            $isSaved = SavedJob::where('user_id', Auth::user()->id)
                ->where('job_post_id', $jobPost->id)->exists();
        } else {
            $isSaved = false;
        }

        $this->shareJobPostGallery($jobPost);

        $this->shareEditFlag('editOff');

        return view('job-posts.show', compact('jobPost', 'isSaved'));
    }

    public function edit($jobPostId)
    {
        $jobPost = JobPost::whereId($jobPostId)->with([
            'locations', 'category', 'propertyTypes', 'event'
        ])->firstOrFail();

        if (Auth::user()->cannot('edit', $jobPost)) {
            abort(403);
        }

        $this->shareExpertises();
        $this->shareLocationsByState();
        $this->shareEventTypes();
        $this->shareJobCategories();
        $this->sharePropertyTypes();
        $this->shareJobTimeRequirements();
        $this->shareOtherJobRequirements();
        $this->shareJobPostGallery($jobPost);

        return view('job-posts.edit', compact('jobPost'));
    }

    public function update(StoreJobPostRequest $request, $jobPostId)
    {
        $jobPost = JobPost::whereId($jobPostId)->firstOrFail();

        if (Auth::user()->cannot('edit', $jobPost)) {
            abort(403);
        }

        with(new JobPostRepo)->update($request->all(), $jobPost)
            ->withPhotos($request->photos);

        if ($request->status === 'draft') {
            return redirect('/dashboard/job-posts/draft');
        }

        return redirect(sprintf('/job-posts/%s', $jobPostId));
    }
}
