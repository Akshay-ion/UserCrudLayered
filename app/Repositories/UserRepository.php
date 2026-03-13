<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function all($perPage = 5, $search = null)
    {
        $query = User::query();

        if($search){
            $query->where(function($q) use ($search){
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
            });
        }

        return $query->paginate($perPage);
    }

    public function create($data)
    {
        return User::create($data);
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        return $user->delete();
    }
}
