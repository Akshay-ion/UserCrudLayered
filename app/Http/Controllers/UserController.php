<?php

namespace App\Http\Controllers;

use App\DTO\UserDTO;
use App\Http\Requests\StoreUserRequest;
use App\Services\UserService;
use Exception;
use Illuminate\Cache\Repository;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(){
        return view('index');
    }

    public function store(StoreUserRequest $request)
    {
        try{
            $dto = UserDTO::fromRequest($request);

            $this->userService->createUser($dto);

            return response()->json([
                'status' => 200,
                'message' => 'User Created Successfully'
            ]);

        }catch(Exception $e){
            return response()->json([
                'status' => 500,
                'message' => 'Something Went Wrong: '. $e->getMessage(),
            ]);
        }
    }

    public function update(StoreUserRequest $request)
    {
        $dto = UserDTO::fromRequest($request);

        $this->userService->createUser($dto);

        return back();
    }

}
