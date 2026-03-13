<?php

namespace App\Services;

use App\DTO\UserDTO;
use App\Repositories\UserRepository;

class UserService
{
    protected $repo;

    public function __construct(UserRepository $repo)
    {
        $this->repo = $repo;
    }

    public function getUsers($perPage = 5)
    {
        return $this->repo->all($perPage);
    }

    public function createUser(UserDTO $dto)
    {
        return $this->repo->create([
            'name' => $dto->name,
            'email' => $dto->email
        ]);
    }

    public function udpateUser(UserDTO $dto)
    {
        return $this->repo->create([
            'name' => $dto->name,
            'email' => $dto->email
        ]);
    }
}
