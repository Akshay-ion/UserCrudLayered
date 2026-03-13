<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function all($perPage = 5)
    {
        return User::select('id', 'name', 'email')->paginate($perPage);
    }

    public function create($data)
    {
        return User::create($data);
    }
}
