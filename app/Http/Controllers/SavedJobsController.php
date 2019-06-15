<?php

namespace App\Http\Controllers;

use App\SavedJob;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreSavedJobRequest;

class SavedJobsController extends Controller
{
    public function index()
    {
        $q = SavedJob::where('user_id', Auth::user()->id);
        if (request('keyword')) {
            $q = $q->whereHas('jobPost', function ($q) {
                $q->whereHas('category', function ($q) {
                    $q->where('name', 'like', '%'.request('keyword').'%');
                })
                ->where('number_of_guests', request('keyword'))
                ->orWhere('budget', request('keyword'))
                ->orWhere('specifics', 'like', '%'.request('keyword').'%')
                ->orWhereHas('locations', function ($q) {
                    $q->where('name', 'like', '%'.request('keyword').'%');
                })
                ->orWhereHas('event', function ($q) {
                    $q->where('name', 'like', '%'.request('keyword').'%');
                });
            });
        }
        $savedJobs =  $q->with(['jobPost' => function ($q) {
            $q->with([
                'event',
                'category',
                'locations',
                'propertyTypes',
                'userProfile:id,userA_id,title,profile_avatar',
            ]);
        }])->paginate(env('APP_PAGINATION', 10));

        return view('dashboard.vendor.saved-jobs', compact('savedJobs'));
    }

    public function store(StoreSavedJobRequest $request)
    {
        Auth::user()->savedJobs()->create([
            'job_post_id' => $request->job_post_id
        ]);

        return response()->json();
    }

    public function destroy($id)
    {
        Auth::user()->savedJobs()->where('job_post_id', $id)->delete();

        return response()->json();
    }
}
