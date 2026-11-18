<?php

use App\Http\Middleware\HasRoleMiddleware;
use App\Http\Middleware\IsActiveUserMiddleware;
use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        $middleware->alias([
            'role' => HasRoleMiddleware::class,
            'active' => IsActiveUserMiddleware::class,
        ]);

        $middleware->appendToGroup('web', [
            SetLocale::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
         $exceptions->render(function (NotFoundHttpException $e, Request $request) {

        if ($request->is('api/*')) {
            return response()->json([
                'status' => false,
                'message' => 'Resource not found',
            ], 404);
        }

    });
    })
    ->create();
