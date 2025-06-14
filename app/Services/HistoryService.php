<?php

namespace App\Services;

use App\Models\History;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HistoryService
{
    /**
     * Create a new history
     */
    public function create(array $data): History
    {
        return History::create([
            "name" => $data["name"]
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
    }
}
