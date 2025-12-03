<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        // then: function () {
        //     Route::prefix('')
        //         ->group(base_path('routes/user.php'));
        // }

    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->validateCsrfTokens(except: [
            'https://newDeniz.com/users/orders/pay-verify',
            'https://my.newDeniz.com/users/orders/pay-verify',
            'https://newDeniz.com/users/course-orders/pay-verify',
            'https://my.newDeniz.com/users/course-orders/pay-verify',
        ]);
        // $middleware->append(StartSession::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
