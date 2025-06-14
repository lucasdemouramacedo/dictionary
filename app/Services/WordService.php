<?php

namespace App\Services;

use App\Models\Word;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\JsonResponse;

class WordService
{
    /**
     * Create a New word
     */
    public function createWord(array $data): Word
    {
        return Word::create([
            "name" => $data["name"]
        ]);
    }

    /**
     * List Words
     */
    public function listWords(array $data): JsonResponse
    {
        $limit = (int) $data['limit'] ?? 10;
        $page = (int) $data['page'] ?? 1;
        $offset = ($page - 1) * $limit;

        $total  = DB::table('words')->count();

        $results = DB::table('words')
            ->orderBy('word')
            ->offset($offset)
            ->limit($limit)
            ->pluck('word');

        return response()->json([
            'results' => $results,
            'totalDocs' => $total,
            'previous' => $page > 1 ? $page - 1 : null,
            'next' => ($offset + $limit) < $total ? $page + 1 : null,
            'hasNext' => ($offset + $limit) < $total,
            'hasPrev' => $page > 1,
        ]);
    }

    /**
     * Register words in bulk
     */
    public function bulkRegistration($data)
    {
        DB::table('words')->insertOrIgnore($data);
    }
}
