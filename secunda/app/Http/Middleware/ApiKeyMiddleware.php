<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
class ApiKeyMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $key = $request->header('X-API-KEY');
        if ($key !== config('api.key')) {
            return response()->json(['error' => 'Invalid API Key'], 401);
        }
        return $next($request);
    }
}
