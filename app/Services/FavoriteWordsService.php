<?php

namespace App\Services;

use App\Exceptions\FavoriteWordCreationException;
use App\Exceptions\WordAlreadyFavoritedException;
use App\Models\FavoriteWord;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;

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

        if($favorite) {
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
}
