<?php

namespace App\Http\Controllers;

use App\User;
use App\JobPost;
use App\JobQuote;
use App\Repositories\JobQuoteRepo;
use Illuminate\Support\Facades\Auth;
use App\Notifications\JobQuoteUpdated;
use App\Http\Requests\StoreJobQuoteRequest;
use App\Repositories\JobQuoteMilestoneRepo;
use App\Http\Requests\UpdateJobQuoteRequest;

class JobQuotesController extends Controller
{
    public function create()
    {
        if (Auth::user()->cannot('create', JobQuote::class)) {
            abort(403);
        }

        $jobPost = JobPost::whereId(request('job_post_id'))
            ->with([
                'timeRequirement' => function ($q) {
                    $q->addSelect(['job_time_requirements.id', 'name']);
                },
                'category' => function ($q) {
                    $q->addSelect(['id', 'name']);
                },
                'locations' => function ($q) {
                    $q->addSelect(['locations.id', 'name']);
                },
                'event' => function ($q) {
                    $q->addSelect(['id', 'name']);
                },
                'userProfile' => function ($q) {
                    $q->addSelect(['id', 'userA_id', 'title', 'profile_avatar']);
                }
            ])->whereStatus(1)->firstOrFail([
                'id', 'user_id', 'event_id', 'specifics', 'budget', 'event_date', 'job_category_id', 'status',
                'number_of_guests', 'job_time_requirement_id'
            ]);

        if ($jobPost->status === 'draft') {
            abort(403);
        }

        return view('job-quotes.create', compact('jobPost'));
    }

    public function store(StoreJobQuoteRequest $request)
    {
        if (Auth::user()->cannot('create', JobQuote::class)) {
            abort(403);
        }

        JobPost::whereId(request('job_post_id'))->whereStatus(1)->firstOrFail(['id']);

        $jobQuoteRepo = new JobQuoteRepo;
        $jobQuoteRepo->uploadTC($request->tac_file);
        $jobQuote = $jobQuoteRepo->create(request()->all());

        with(new JobQuoteMilestoneRepo)->store($jobQuote, $request->milestones);

        if ($request->status === 'draft') {
            return redirect('/dashboard');
        }

        return redirect(sprintf('job-quotes/%s', $jobQuote->id))
            ->with('success', 'Your job quote has now been sent to the couple.');
        ;
    }

    public function edit($jobQuoteId)
    {
        $jobQuote = JobQuote::whereId($jobQuoteId)->with([
            'additionalFiles',
            'tcFile',
            'milestones' => function ($q) {
                $q->addSelect(['id', 'job_quote_id', 'percent', 'due_date', 'desc']);
            }
        ])->firstOrFail();

        if (Auth::user()->cannot('edit', $jobQuote) || $jobQuote->locked === 1) {
            abort(403);
        }

        $jobPost = JobPost::whereId($jobQuote->job_post_id)
            ->with([
                'timeRequirement' => function ($q) {
                    $q->addSelect(['job_time_requirements.id', 'name']);
                },
                'category' => function ($q) {
                    $q->addSelect(['id', 'name']);
                },
                'locations' => function ($q) {
                    $q->addSelect(['locations.id', 'name']);
                },
                'event' => function ($q) {
                    $q->addSelect(['id', 'name']);
                },
                'userProfile' => function ($q) {
                    $q->addSelect(['id', 'userA_id', 'title', 'profile_avatar']);
                }
            ])
            ->firstOrFail([
                'id', 'user_id', 'event_id', 'specifics', 'budget', 'event_date', 'job_category_id', 'status',
                'number_of_guests', 'job_time_requirement_id'
            ]);

        return view('job-quotes.edit', compact('jobPost', 'jobQuote'));
    }

    public function update(UpdateJobQuoteRequest $request, $jobQuoteId)
    {
        $jobQuote = JobQuote::whereId($jobQuoteId)->firstOrFail();

        if (Auth::user()->cannot('edit', $jobQuote)) {
            abort(403);
        }

        $previousStatus = $jobQuote->status;

        $jobQuoteRepo = new JobQuoteRepo;
        $jobQuoteRepo->uploadTC($request->tc_file);
        $jobQuoteRepo->update(request()->all(), $jobQuote);

        with(new JobQuoteMilestoneRepo)->update($jobQuote, $request->milestones);

        if ($previousStatus === 3) {
            $jobPost = JobPost::whereId($jobQuote->job_post_id)->firstOrFail(['user_id']);
            $jobPostOwner = User::whereId($jobPost->user_id)->firstOrFail(['id']);
            $jobPostOwner->notify(new JobQuoteUpdated($jobQuote));
        }

        if ($request->status === 'draft') {
            return redirect('/dashboard');
        }

        return redirect()->back()->with('success', 'Job Quote was updated successfully!');
    }

    public function show($jobQuoteId)
    {
        $jobQuoteRepo = new JobQuoteRepo;
        $jobQuote = $jobQuoteRepo->get($jobQuoteId);

        if (Auth::user()->cannot('view', $jobQuote) && Auth::user()->cannot('respond', $jobQuote)) {
            abort(403);
        }

        return view('job-quotes.show', compact('jobQuote'));
    }
}
