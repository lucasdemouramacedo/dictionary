<?php

namespace App\Http\Controllers;

use App\Services\Integration\FreeDictionary\FreeDictionaryService;
use App\Services\SearchService;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __construct(
        protected SearchService $searchService
    ) {}
    
    public function search($word) {
        $definition = $this->searchService->getDefinition($word);
        return $definition;
    }
}
