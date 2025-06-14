<?php

namespace App\Http\Controllers;

use App\Services\WordService;
use Illuminate\Http\Request;

class WordController extends Controller
{
    public function __construct(
        protected WordService $wordService
    ) {}

    public function list(Request $request)
    {
        $result = $this->wordService->findWords($request->all());
        return  response()->json($result, 200);
    }
}
