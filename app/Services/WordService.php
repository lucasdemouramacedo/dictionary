<?php

namespace App\Services;

use App\Exceptions\SearchException;
use App\Exceptions\WordNotFoundException;
use App\Models\Word;
use Exception;
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
     * Find a word
     */
    public function findWord($word): Word
    {
        $wordFound = Word::where("word", $word)->find();

        if(!$wordFound) {
            throw new WordNotFoundException();
        }

        return $$wordFound;
    }

    /**
     * List Words
     */
    public function findWords(array $data): array
    {
        $limit = (int) ($data['limit'] ?? 10);
        $page = (int) ($data['page'] ?? 1);
        $offset = ($page - 1) * $limit;
        $search = ($data['search'] ?? null);

        try {
            $query = DB::table('words')->orderBy('word');

            if ($search) {
                $query->where('word', 'like', $search . '%');
            }

            $total = $query->count();

            $results = $query
                ->offset($offset)
                ->limit($limit)
                ->pluck('word');

            return [
                'results' => $results,
                'totalDocs' => $total,
                'page' => $page,
                'totalPages' => (int) ceil($total / $limit),
                'hasNext' => ($offset + $limit) < $total,
                'hasPrev' => $page > 1,
            ];
        } catch (Exception $e) {
            throw new SearchException('words list');
        }
    }

    /**
     * Register words in bulk
     */
    public function bulkRegistration($data)
    {
        DB::table('words')->insertOrIgnore($data);
    }
}
