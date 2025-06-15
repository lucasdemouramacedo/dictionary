<?php

namespace App\Services\Integration\FreeDictionary;

use App\Services\Integration\FreeDictionary\FreeDictionaryHttpConnection;

class FreeDictionaryService
{
    public function __construct(
        protected FreeDictionaryHttpConnection $freeDictionary
    ) {}

    /**
     * Search a word definition
     */
    public function getDefinition(string $word): array
    {
        return $this->freeDictionary->get("entries/en/{$word}");
    }
}