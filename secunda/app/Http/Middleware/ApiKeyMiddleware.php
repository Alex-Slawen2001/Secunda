<?php
namespace App\Http\Middleware;
use Closure;
class ApiKeyMiddleware
{
    public function handle($request, Closure $next)
    {
        $key = $request->header('X-API-KEY');
        if ($key !== config('app.api_key')) {
            return response()->json(['error' => 'Invalid API Key'], 401);
        }
        return $next($request);
    }
}
