<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Search\JobPost\JobPostSearchManager;

class DashboardJobPostsController extends Controller
{
    public function index($status = 'live')
    {
        if (Auth::user()->account === 'couple') {
            $this->coupleDashboardJobs();
        } else {
            $this->vendorDashboardJobs();
        }

        $this->shareSavedJobs();

        return view(sprintf('dashboard.%s.job-posts', Auth::user()->account));
    }

    public function coupleDashboardJobs()
    {
        $jobPosts = JobPostSearchManager::keywordSearch(request('q'));

        return View::share([
            'jobPosts' => $jobPosts,
            'status' => request('status')
        ]);
    }

    public function vendorDashboardJobs()
    {
        $jobPosts = JobPostSearchManager::applyFilters(request()->all())
            ->paginate(env('APP_PAGINATION', 10));

        $this->shareEventTypes();
        $this->shareLocationsByState();
        $this->shareJobCategories();

        return View::share([
            'jobPosts' => $jobPosts,
            'status' => request('status')
        ]);
    }
}
