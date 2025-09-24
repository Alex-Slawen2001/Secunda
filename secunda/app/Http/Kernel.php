<?php
class Kernel
{
    protected $routeMiddleware = [
        'auth.apikey' => \App\Http\Middleware\ApiKeyMiddleware::class,
    ];
}
