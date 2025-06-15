<?php

namespace App\Services;

use App\Exceptions\SearchException;
use App\Services\Integration\FreeDictionary\FreeDictionaryService;
use Exception;
use Illuminate\Support\Facades\Redis;

class SearchService
{
    public function __construct(
        protected FreeDictionaryService $freeDictionaryService
    ) {}

    /**
     * Busca definição da palavra no Redis, se não encontrar, consulta a API e salva no Redis
     */
    public function getDefinition(string $word): array
    {
        try {

            $cacheKey = "free:dictionary:definition:{$word}";
            $cached = Redis::get($cacheKey);

            if ($cached) {
                $definition = json_decode($cached, true);
                return [...$definition, 'from_cache' => true];
            }

            $definition = $this->freeDictionaryService->getDefinition($word);

            Redis::setex($cacheKey, 3600, json_encode($definition));
            
            return [...$definition, 'from_cache' => false];
        } catch (Exception $e) {
            throw new SearchException("word");
        }
    }
}
