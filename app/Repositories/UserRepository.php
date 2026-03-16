<?php

namespace App\Repositories;

use App\DTO\UserDTO;
use App\Models\User;

class UserRepository
{
    public function all($perPage = 5, $search = null)
    {
        $query = User::query();

        if ($search) {
            $query->where(function($q) use ($search){
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $page = request()->get('page', 1);

        $paginator = $query->paginate($perPage, ['*'], 'page', $page);

        // If requested page exceeds last page, reload using last page
        if ($page > $paginator->lastPage()) {
            $paginator = $query->paginate($perPage, ['*'], 'page', $paginator->lastPage());
        }

        return $paginator->appends(request()->all());
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
