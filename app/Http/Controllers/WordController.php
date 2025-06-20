<?php

namespace App\Http\Controllers;

use App\Services\WordService;
use Illuminate\Http\Request;

class WordController extends Controller
{
    public function __construct(
        protected WordService $wordService
    ) {}

    /**
     * @OA\Get(
     *     path="/entries/en",
     *     summary="Lista de palavras",
     *     tags={"Palavras"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Palavra",
     *         required=false,
     *         @OA\Schema(type="string", example="fire")
     *     ),
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
     *                 @OA\Items(type="string", example="fire")
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
        $result = $this->wordService->findWords($request->all());
        return  response()->json($result, 200);
    }
}
