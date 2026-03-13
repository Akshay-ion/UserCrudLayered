<?php

namespace App\Http\Controllers;

use App\DTO\UserDTO;
use App\Http\Requests\StoreUserRequest;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function store(StoreUserRequest $request)
    {
        $dto = UserDTO::fromRequest($request);

        $this->userService->createUser($dto);

        return back();
    }
}
