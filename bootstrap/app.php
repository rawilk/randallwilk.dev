<?php

declare(strict_types=1);

use App\Http\Middleware\AddLogContext;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function (): void {
            Route::middleware('web')->group(base_path('routes/redirects.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(AddLogContext::class);

        $middleware->redirectGuestsTo(fn (): string => filament()->getLoginUrl());

        $middleware->redirectUsersTo(fn (): string => match (filament()->getCurrentPanel()?->getId()) {
            'admin' => '/admin',
            default => '/',
        });
    })
    ->withBroadcasting(
        channels: __DIR__ . '/../routes/channels.php',
        attributes: [
            'prefix' => 'api/v1',
            'middleware' => [
                'web',
            ],
        ],
    )
    ->withExceptions(function (Exceptions $exceptions) {
    })->create();
