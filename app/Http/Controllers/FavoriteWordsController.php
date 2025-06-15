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
     * Create a new favorite word
     */
    public function create($word)
    {
        $word = $this->favoriteService->favoriteWord($word);
        return response()->noContent();
    }

    /**
     * Delete a favorite word
     */
    public function delete($word)
    {
        $word = $this->favoriteService->unfavoriteWord($word);
        return response()->noContent();
    }
    
    /**
     * List favorite words
     */
    public function list(Request $request)
    {
        $result = $this->favoriteService->findFavoriteWords($request->all());
        return  response()->json($result, 200);
    }
}
