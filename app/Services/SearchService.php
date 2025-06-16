<?php

namespace App\Services;

use App\Events\SearchProcessed;
use App\Exceptions\SearchException;
use App\Services\Integration\FreeDictionary\FreeDictionaryService;
use Exception;
use Illuminate\Support\Facades\Redis;

class SearchService
{
    public function __construct(
        protected FreeDictionaryService $freeDictionaryService,
        protected WordService $wordService
    ) {}

    /**
     * Busca definição da palavra no Redis, se não encontrar, consulta a API e salva no Redis
     */
    public function getDefinition(string $word): array
    {
        try {
            $wordFound = $this->wordService->findWord($word);
            $cacheKey = "free:dictionary:definition:{$wordFound->word}";
            $cached = Redis::get($cacheKey);

            if ($cached) {
                $definition = json_decode($cached, true);

                SearchProcessed::dispatch($wordFound->word);

                return [...$definition, 'from_cache' => true];
            }

            $definition = $this->freeDictionaryService->getDefinition($wordFound->word);

            Redis::setex($cacheKey, 3600, json_encode($definition));

            SearchProcessed::dispatch($wordFound->word);

            return [...$definition, 'from_cache' => false];
        } catch (Exception $e) {
            throw new SearchException("word");
        }
    }
}
