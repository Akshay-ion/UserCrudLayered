<?php

namespace App\Services;

use App\DTO\UserDTO;
use App\Repositories\UserRepository;
use function Laravel\Prompts\search;

class UserService
{
    protected $repo;

    public function __construct(UserRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getUsers($perPage = 5, $search = null)
    {
        return $this->repo->all($perPage, $search);
    }

    public function createUser(UserDTO $dto)
    {
        return $this->repo->create([
            'name' => $dto->name,
            'email' => $dto->email
        ]);
    }

    public function getUserById($id)
    {
        return $this->repo->getUserById($id);
    }

    public function updateUser($id, UserDTO $dto)
    {
        return $this->repo->updateUser($id, $dto);
    }

    public function deleteUser($id)
    {
        return $this->repo->deleteUser($id);
    }
}
