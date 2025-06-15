<?php

namespace App\Services;

use App\Services\Integration\FreeDictionary\FreeDictionaryService;
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
        $cacheKey = "free:dictionary:definition:{$word}";
        $cached = Redis::get($cacheKey);

        if ($cached) {
            $definition = json_decode($cached, true);
            return $definition;
        }

        $definition = $this->freeDictionaryService->getDefinition($word);

        Redis::setex($cacheKey, 3600, json_encode($definition));

        return $definition;
    }
}
