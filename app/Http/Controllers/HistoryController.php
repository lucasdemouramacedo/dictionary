<?php

namespace App\Http\Controllers;

use App\Services\HistoryService;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function __construct(
        protected HistoryService $historyService
    ) {}

    /**
     * @OA\Get(
     *     path="/user/me/history",
     *     summary="Histórico de busca",
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
        $result = $this->historyService->findHistory($request->all());
        return  response()->json($result, 200);
    }
}
