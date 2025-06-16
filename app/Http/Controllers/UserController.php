<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Services\AuthService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    /**
     * Create a new User
     */
    public function profile()
    {
        $user = $this->userService->readUser(Auth::user()->id);

        return response()->json($user, 200);
    }
}
