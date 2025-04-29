<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\Jwt;
use App\Http\Middleware\ConvertCamelCaseToSnakeCase;
use App\Http\Middleware\checkApiPermission;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: [
            __DIR__.'/../routes/api.php',
            __DIR__.'/../routes/api/post_catalogue.php',
            __DIR__.'/../routes/api/posts.php',
        ],
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'jwt' => Jwt::class,
            'convertRequestKey' => ConvertCamelCaseToSnakeCase::class,
            'checkApiPermission' => checkApiPermission::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
