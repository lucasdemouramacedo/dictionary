<?php

namespace App\Services;

use App\Models\FavoriteWord;
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
        $word = $this->wordService->findWord($word);
        return FavoriteWord::where('word_id', $word->id)
            ->where('user_id', Auth::id())
            ->first();
    }

    /**
     * Favorite a word
     */
    public function favoriteWord($word)
    {
        $word = $this->wordService->findWord($word);

        $existing = FavoriteWord::where('user_id', Auth::id())
            ->where('word_id', $word->id)
            ->first();

        return FavoriteWord::create([
            'user_id' => Auth::id(),
            'word_id' => $word->id,
        ]);
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
}
