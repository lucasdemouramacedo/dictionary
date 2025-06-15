<?php

namespace App\Services\Integration\FreeDictionary;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\ConnectionException;

class FreeDictionaryHttpConnection
{
    protected string $baseUrl = 'https://api.dictionaryapi.dev/api';
    protected string $version = 'v2';

    public function get(string $endpoint): array
    {
        try {
            $response = Http::retry(3, 1000)
                ->timeout(5)
                ->get("{$this->baseUrl}/{$this->version}/{$endpoint}");

            $response->throw();

            return $response->json();
        } catch (ConnectionException | RequestException $e) {
            
            logger()->error('Dictionary API error: ' . $e->getMessage());
            throw $e;
        }
    }
}
