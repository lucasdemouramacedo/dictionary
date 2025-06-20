<?php

namespace App\Http\Controllers;

use App\Services\FavoriteService;
use App\Services\FavoriteWordsService;
use Illuminate\Http\Request;

class FavoriteWordsController extends Controller
{
    public function __construct(
        protected FavoriteWordsService $favoriteService
    ) {}

    /**
     * @OA\Post(
     *     path="/entries/en/{word}/favorite",
     *     summary="Adiciona uma palavra as favoritas",
     *     tags={"Palavras"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="word",
     *         in="path",
     *         required=true,
     *         description="Palavra para ser favoritada",
     *         @OA\Schema(type="string", example="fire")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="OK"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Palavra não encontrada.")
     *         )
     *     )
     * )
     */
    public function create($word)
    {
        $word = $this->favoriteService->favoriteWord($word);
        return response()->noContent();
    }

    /**
     * @OA\Delete(
     *     path="/entries/en/{word}/unfavorite",
     *     summary="Returar uma palavra dos favoritas",
     *     tags={"Palavras"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="word",
     *         in="path",
     *         required=true,
     *         description="Palavra para ser desfavoritada",
     *         @OA\Schema(type="string", example="fire")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="OK"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Palavra não encontrada.")
     *         )
     *     )
     * )
     */
    public function delete($word)
    {
        $word = $this->favoriteService->unfavoriteWord($word);
        return response()->noContent();
    }

    /**
     * @OA\Get(
     *     path="/user/me/favorites",
     *     summary="Lista de palavras favoritas",
     *     tags={"Usuário"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Quantidade de resultados por página",
     *         required=false,
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Número da página a ser retornada",
     *         required=false,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="results",
     *                 type="array",
     *                 @OA\Items(
     *                      type="object",
     *                      @OA\Property(property="word", type="string", example="fire"),
     *                      @OA\Property(property="added", type="string", format="date-time", example="2025-06-20 15:50:49")
     *                 )
     *             ),
     *             @OA\Property(property="totalDocs", type="integer", example=137),
     *             @OA\Property(property="page", type="integer", example=1),
     *             @OA\Property(property="totalPages", type="integer", example=14),
     *             @OA\Property(property="hasNext", type="boolean", example=true),
     *             @OA\Property(property="hasPrev", type="boolean", example=false)
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Invalid token.")
     *         )
     *     )
     * )
     */
    public function list(Request $request)
    {
        $result = $this->favoriteService->findFavoriteWords($request->all());
        return  response()->json($result, 200);
    }
}
