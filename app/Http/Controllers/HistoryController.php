<?php

namespace App\Http\Controllers;

use App\Services\HistoryService;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function __construct(
        protected HistoryService $historyService
    ) {}

    public function list(Request $request)
    {
        $result = $this->historyService->findHistory($request->all());
        return  response()->json($result, 200);
    }
}
