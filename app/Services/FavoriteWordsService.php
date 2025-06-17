<?php

namespace App\Services;

use App\Exceptions\FavoriteWordCreationException;
use App\Exceptions\SearchException;
use App\Exceptions\WordAlreadyFavoritedException;
use App\Models\FavoriteWord;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FavoriteWordsService
{
    public function __construct(
        protected WordService $wordService
    ) {}

    /**
     * Find a favorite word by word
     */
    public function findByWord(string $word): ?FavoriteWord
    {
        $wordFound = $this->wordService->findWord($word);
        $favorite = FavoriteWord::where('word_id', $wordFound->id)
            ->where('user_id', Auth::id())
            ->first();

        return $favorite;
    }

    /**
     * Favorite a word
     */
    public function favoriteWord($word)
    {
        $favorite = $this->findByWord($word);

        if ($favorite) {
            throw new WordAlreadyFavoritedException();
        }

        try {
            $wordFound = $this->wordService->findWord($word);
            return FavoriteWord::create([
                'user_id' => Auth::id(),
                'word_id' =>  $wordFound->id,
            ]);
        } catch (QueryException $e) {
            throw new FavoriteWordCreationException();
        }
    }

    /**
     * Remove a word com favorite word if exists.
     */
    public function unfavoriteWord(string $word): bool
    {
        $favorite = $this->findByWord($word);

        if ($favorite) {
            return $favorite->delete();
        }

        return false;
    }

    /**
     * List favorite words
     */
    public function findFavoriteWords(array $data): array
    {
        $limit = (int) ($data['limit'] ?? 10);
        $page = (int) ($data['page'] ?? 1);
        $offset = ($page - 1) * $limit;

        try {
            $query = DB::table('favorite_words')
                ->join('words', 'favorite_words.word_id', '=', 'words.id')
                ->where('user_id', '=', Auth::user()->id)
                ->whereNull('deleted_at')
                ->orderBy('favorite_words.created_at')
                ->select('words.word as word', 'favorite_words.created_at as added');

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
            throw new SearchException('palavras favoritas');
        }
    }
}
