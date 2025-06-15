<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CacheInfoMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $start = microtime(true);

        /** @var \Illuminate\Http\JsonResponse $response */
        $response = $next($request);

        $data = $response->getData(true);
        $isFromCache = $data['from_cache'] ?? false;

        $durationMs = round((microtime(true) - $start) * 1000);

        $response->headers->set('X-Cache', $isFromCache ? 'HIT' : 'MISS');
        $response->headers->set('X-Response-Time', "{$durationMs}ms");

        unset($data['from_cache']);
        $response->setData($data);

        return $response;
    }
}
