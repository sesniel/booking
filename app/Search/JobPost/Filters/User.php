<?php

namespace App\Search\JobPost\Filters;

use App\Couple;
use App\User as UserModel;
use App\Search\SearchContract;
use Illuminate\Database\Eloquent\Builder;

class User implements SearchContract
{
    public static function apply(Builder $builder, $value)
    {
        $user = UserModel::whereId($value)->first(['id', 'account']);

        if ($user->account === 'couple') {
            $couple = Couple::where('userA_id', $user->id)
                ->orWhere('userB_id', $user->id)->first();

            return $builder->where(function ($q) use ($couple) {
                $q->where('user_id', $couple->userA_id)
                ->orWhere('user_id', $couple->userB_id);
            });
        }

        return $builder->where('user_id', $value);
    }
}
