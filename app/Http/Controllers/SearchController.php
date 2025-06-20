<?php

namespace App\Http\Controllers;

use App\Services\Integration\FreeDictionary\FreeDictionaryService;
use App\Services\SearchService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __construct(
        protected SearchService $searchService
    ) {}

    /**
     * @OA\Get(
     *     path="/entries/en/{word}",
     *     summary="Busca uma palavra",
     *     description="Endpoint que busca uma palavra específica.",
     *     tags={"Palavras"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="word",
     *         in="path",
     *         required=true,
     *         description="Palavra a ser buscada",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(
     *             example={
     *                   {
     *                       "word": "moon",
     *                       "phonetic": "/muːn/",
     *                       "phonetics": {
     *                           {
     *                               "text": "/muːn/",
     *                               "audio": "https://...mp3"
     *                           }
     *                       },
     *                       "meanings": {
     *                           {
     *                               "partOfSpeech": "noun",
     *                               "definitions": {
     *                                   {
     *                                       "definition": "A natural satellite of a planet.",
     *                                       "example": "That's no moon, it's a space station!"
     *                                   }
     *                               }
     *                           }
     *                       }
     *                   }
     *               }
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Palavra não encontrada.")
     *         )
     *     )
     * )
     */
    public function search($word)
    {
        $definition = $this->searchService->getDefinition($word);
        return $definition;
    }
}
