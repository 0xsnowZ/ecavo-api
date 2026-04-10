<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__.'/../routes/api.php',
        apiPrefix: 'api',
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // CORS + cookie-to-bearer token injection for HTTP-only auth cookies
        $middleware->api(prepend: [
            \Illuminate\Http\Middleware\HandleCors::class,
            \App\Http\Middleware\InjectTokenFromCookie::class,
        ]);

        // Register aliases
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);

        // NOTE: statefulApi() is intentionally NOT called here.
        // We use Bearer token auth only (not cookie-based SPA).
        // statefulApi() adds EnsureFrontendRequestsAreStateful which
        // enforces CSRF checks and breaks token-based auth clients.
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Always return JSON for API exceptions
        $exceptions->shouldRenderJsonWhen(function (Request $request, \Throwable $e) {
            return $request->is('api/*') || $request->wantsJson();
        });
    })->create();
