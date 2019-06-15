<?php

namespace App\Search\JobPost\Filters;

use App\Search\SearchContract;
use App\JobCategory as JobCategoryModel;
use Illuminate\Database\Eloquent\Builder;

class JobCategory implements SearchContract
{
    public static function apply(Builder $builder, $value)
    {
        $catIds = JobCategoryModel::whereIn('name', $value)->get(['id'])->pluck('id')->toArray();
        return $builder->whereIn('job_category_id', $catIds);
    }
}
