<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct (
        protected AuthService $authService
    ) {}
    
    public function login(Request $request) {
        $user = $this->authService->authenticateUser($request->only('email', 'password'));
        return response()->json($user, 200);
    }
}
