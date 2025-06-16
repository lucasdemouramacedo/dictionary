<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Services\AuthService;
use App\Services\UserService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService,
        protected AuthService $authService
    ) {}

    /**
     * Create a new User
     */
    public function create(CreateUserRequest $request)
    {
        $user = $this->userService->createUser($request->all());

        $userAuthenticated = $this->authService->authenticateUser([
            'email' => $user->email,
            'password' => $request->password,
        ]);

        return response()->json($userAuthenticated, 200);
    }
}
