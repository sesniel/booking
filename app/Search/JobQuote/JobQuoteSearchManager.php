<?php

namespace App\Search\JobPost;

use App\Couple;
use App\JobPost;
use App\JobQuote;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class JobQuoteSearchManager
{
    public static function keywordSearch($filter = null)
    {
        $query = JobQuote::where('status', '<>', 0);

        if (Auth::user()->account === 'couple') {
            $jobPostIds = JobPost::where('user_id', Auth::user()->id)
                ->get(['id', 'user_id'])->pluck('id')->toArray();
            $query = $query->whereIn('job_post_id', $jobPostIds);
            $query = self::fullQuery($query, $filter);
        } else {
            $quotes = $query->where('user_id', Auth::user()->id)
                ->get(['id', 'user_id'])->pluck('id')->toArray();
            $query = self::fullQuery($query, $filter);
        }

        return $query->with([
            'invoice',
            'jobPost' => function ($q) {
                $q->addSelect([
                    'id', 'user_id', 'event_date', 'event_id', 'job_category_id'
                ])->with(['locations', 'event', 'category']);
            },
            'user' => function ($q) {
                $q->with(['vendorProfile' => function ($q) {
                    $q->addSelect([
                        'id', 'user_id', 'business_name', 'business_name', 'profile_avatar'
                    ]);
                }])->addSelect('id');
            }
        ])->orderBy('created_at', 'DESC')->paginate(env('APP_PAGINATION', 10));
    }

    public static function fullQuery($query, $filter = null)
    {
        if (!$filter) {
            return $query;
        }

        return $query->where(function ($q) use ($filter) {
            $q->whereTotal($filter)
            ->orWhereHas('jobPost', function ($q) use ($filter) {
                $q->whereHas('category', function ($q) use ($filter) {
                    $q->where('name', 'like', '%'.$filter.'%');
                })->orwhereHas('locations', function ($q) use ($filter) {
                    $q->where('name', 'like', '%'.$filter.'%');
                })->orWhereHas('event', function ($q) use ($filter) {
                    $q->where('name', 'like', '%'.$filter.'%');
                })->orwhere(function ($q) use ($filter) {
                    if (strtotime($filter)) {
                        $q->whereDate('event_date', Carbon::parse($filter)->toDateString());
                    }
                });
            })->orwhere(function ($q) use ($filter) {
                if (strtotime($filter)) {
                    $q->whereDate('duration', Carbon::parse($filter)->toDateString());
                }
            })->orwhereHas('user', function ($q) use ($filter) {
                $q->whereHas('vendorProfile', function ($q) use ($filter) {
                    $q->where('business_name', 'like', '%'.$filter.'%');
                });
            });
        });
    }
}
