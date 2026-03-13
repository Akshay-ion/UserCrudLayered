<?php

namespace App\Http\Controllers;

use App\DTO\UserDTO;
use App\Http\Requests\StoreUserRequest;
use App\Repositories\UserRepository;
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

    public function index(Request $request)
    {
        $users = $this->userService->getUsers(5, $request->search);

        return response()->json([
            'status' => 200,
            'data' => $users
        ]);

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
        try{
            $dto = UserDTO::fromRequest($request);

            $this->userService->udpateUser($dto);

            return response()->json([
                'status' => 200,
                'message' => 'User Updated Successfully'
            ]);

        }catch(Exception $e){
            return response()->json([
                'status' => 500,
                'message' => 'Something Went Wrong: '. $e->getMessage(),
            ]);
        }
    }

    public function destroy($id)
    {
        try{
            $this->userService->deleteUser($id);

            return response()->json([
                'status' => 200,
                'message' => 'User deleted successfully'
            ]);

        }catch(Exception $e){
            dd($e);
        }

    }

}
