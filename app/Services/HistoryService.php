<?php

namespace App\Services;

use App\Exceptions\SearchException;
use App\Models\History;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HistoryService
{
    public function __construct(
        protected WordService $wordService
    ) {}

    /**
     * Create a new history
     */
    public function create($word): History
    {
        $word = $this->wordService->findWord($word);
        return History::create([
            "user_id" => Auth::user()->id,
            "word_id" => $word->id
        ]);
    }

    /**
     * List History
     */
    public function findHistory(array $data): array
    {
        $limit = (int) ($data['limit'] ?? 10);
        $page = (int) ($data['page'] ?? 1);
        $offset = ($page - 1) * $limit;

        try {
            $query = DB::table('history')
                ->join('words', 'history.word_id', '=', 'words.id')
                ->where('user_id', '=', Auth::user()->id)
                ->orderBy('history.created_at')
                ->select('words.word as word', 'history.created_at as added');

            $total = $query->count();

            $results = $query
                ->offset($offset)
                ->limit($limit)
                ->get();

            return [
                'results' => $results,
                'totalDocs' => $total,
                'page' => $page,
                'totalPages' => (int) ceil($total / $limit),
                'hasNext' => ($offset + $limit) < $total,
                'hasPrev' => $page > 1,
            ];
        } catch (Exception $e) {
            throw new SearchException('histórico de busca');
        }
    }
}
