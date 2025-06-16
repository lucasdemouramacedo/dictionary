<?php

namespace App\Http\Controllers;

class DefaultController extends Controller
{
    public function __invoke()
    {
        return response()->json([
            "message" => "Fullstack Challenge 🏅 - Dictionary"
        ], 200);
    }
}
