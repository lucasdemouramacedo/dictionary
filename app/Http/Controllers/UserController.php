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
     * @OA\Post(
     *     path="/auth/signup",
     *     summary="Cadastro de usuário",
     *     tags={"Autenticação"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password"},
     *             @OA\Property(property="name", type="string", example="João da Silva"),
     *             @OA\Property(property="email", type="string", format="email", example="usuario@exemplo.com"),
     *             @OA\Property(property="password", type="string", format="password", example="!senha123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="string", format="uuid", example="c79cdbfd-96e9-49e6-8c9c-375b6665fc82"),
     *             @OA\Property(property="name", type="string", example="João da Silva"),
     *             @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJh...")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Dados inválidos."),
     *             @OA\Property(
     *                 property="errors",
     *                 type="object",
     *                 @OA\Property(
     *                     property="name",
     *                     type="array",
     *                     @OA\Items(type="string", example="O nome é obrigatório.")
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="array",
     *                     @OA\Items(
     *                         oneOf={
     *                             @OA\Schema(type="string", example="O e-mail é obrigatório."),
     *                             @OA\Schema(type="string", example="Informe um e-mail válido."),
     *                         }
     *                     )
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="array",
     *                     @OA\Items(
     *                         oneOf={
     *                             @OA\Schema(type="string", example="A senha é obrigatória."),
     *                             @OA\Schema(type="string", example="A senha deve ter no mínimo 8 caracteres."),
     *                             @OA\Schema(type="string", example="A senha deve conter pelo menos uma letra maiúscula, uma minúscula, um número e um caractere especial.")
     *                         }
     *                     )
     *                 )
     *             )
     *         )
     *     )
     * )
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
     * @OA\Get(
     *     path="/user/me",
     *     summary="Perfil",
     *     tags={"Usuário"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="string", format="uuid", example="c79cdbfd-96e9-49e6-8c9c-375b6665fc82"),
     *             @OA\Property(property="name", type="string", example="João da Silva"),
     *             @OA\Property(property="email", type="string", example="usuario@exemplo.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Usuário não encontrado.")
     *         )
     *     )
     * )
     */
    public function profile()
    {
        $user = $this->userService->readUser(Auth::user()->id);

        return response()->json($user, 200);
    }
}
