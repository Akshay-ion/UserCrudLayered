<?php

namespace App\Repositories;

use App\DTO\UserDTO;
use App\Models\User;

class UserRepository
{
    public function all($perPage = 5, $search = null)
    {
        $query = User::query()
            ->select('id', 'name', 'email');

        if ($search) {
            $query->whereFullText(['name', 'email'], $search);
        }

        return $query->paginate($perPage)
                    ->appends(request()->query());
    }

    public function create($data)
    {
        return User::create($data);
    }

    public function getUserById($id)
    {
        return User::select('id', 'name', 'email')
            ->where('id', $id)
            ->first();
    }

    public function updateUser($id, UserDTO $dto)
    {
        $user = User::findOrFail($id);

        $user->update([
            'name'  => $dto->name,
            'email' => $dto->email
        ]);

        return $user;
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        return $user->delete();
    }
}
