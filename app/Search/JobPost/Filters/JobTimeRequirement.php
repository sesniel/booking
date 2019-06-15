<?php

namespace App\Search\JobPost\Filters;

use App\Search\SearchContract;
use Illuminate\Database\Eloquent\Builder;

class JobTimeRequirement implements SearchContract
{
    public static function apply(Builder $builder, $value)
    {
        return $builder->where('job_time_requirement_id', $value);
    }
}
