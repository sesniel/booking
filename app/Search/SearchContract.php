<?php

namespace App\Search;

use Illuminate\Database\Eloquent\Builder;

interface SearchContract
{
    /**
     * Apply a given search value to the builder instance.
     *
     * @param Builder $builder
     * @param mixed $value
     * @return Builder $builder
     */
    public static function apply(Builder $builder, $value);
}
