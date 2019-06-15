<?php

namespace App\Search\JobPost\Filters;

use App\Location;
use App\Search\SearchContract;
use Illuminate\Database\Eloquent\Builder;

class Locations implements SearchContract
{
    public static function apply(Builder $builder, $value)
    {
        return $builder->whereHas('locations', function ($q) use ($value) {
            $locIds = Location::whereIn('name', $value)->get(['id'])->pluck('id')->toArray();
            $q->whereIn('location_id', $locIds);
        });
    }
}
