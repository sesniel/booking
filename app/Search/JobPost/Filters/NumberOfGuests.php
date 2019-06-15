<?php

namespace App\Search\JobPost\Filters;

use App\Search\SearchContract;
use Illuminate\Database\Eloquent\Builder;

class NumberOfGuests implements SearchContract
{
    public static function apply(Builder $builder, $value)
    {
        return $builder->where('number_of_guests', $value);
    }
}
