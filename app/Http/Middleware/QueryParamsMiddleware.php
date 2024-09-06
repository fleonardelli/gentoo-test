<?php

namespace App\Http\Middleware;

use App\DTO\Request\QueryParams;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final readonly class QueryParamsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $validated = $request->query();

        $queryParams = new QueryParams($validated);

        // Attach DTO to the request
        $request->attributes->set('queryParams', $queryParams);

        return $next($request);
    }
}
