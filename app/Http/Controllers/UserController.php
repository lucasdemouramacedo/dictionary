<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Services\UserService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {}

    /**
     * Create a new User
     */
    public function create(CreateUserRequest $request)
    {
            $user = $this->userService->createUser($request->all());
            return response()->json($user, 201);
    }
}
