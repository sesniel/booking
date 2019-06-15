<?php

namespace App\Search\JobPost\Filters;

use App\Search\SearchContract;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;

class EventDateTo implements SearchContract
{
    public static function apply(Builder $builder, $value)
    {
        return $builder->whereDate('event_date', '<=', Carbon::parse($value)->toDateString());
    }
}
