<?php

namespace App\Repositories;

use App\User;

class UserRepo
{
    public function create(array $data)
    {
        return User::create($data);
    }
}
