<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\Middleware\Authenticate;
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

        // Prevent auth:sanctum from redirecting to route('login') on API routes.
        // Returning null tells the Authenticate middleware to throw
        // AuthenticationException without a redirect target, which our
        // withExceptions handler then converts to a clean 401 JSON response.
        Authenticate::redirectUsing(function (Request $request): ?string {
            return $request->is('api/*') ? null : route('login');
        });

        // NOTE: statefulApi() is intentionally NOT called here.
        // We use Bearer token auth only (not cookie-based SPA).
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Always return JSON for API exceptions (covers 404, 422, 500, etc.)
        $exceptions->shouldRenderJsonWhen(function (Request $request, \Throwable $e) {
            return $request->is('api/*') || $request->wantsJson();
        });

        // Prevent auth:sanctum from redirecting to the named route 'login'
        // (which doesn't exist in this API-only project). Return 401 JSON instead.
        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, Request $request) {
            if ($request->is('api/*') || $request->wantsJson()) {
                return response()->json(['message' => 'غير مصرح. يرجى تسجيل الدخول.'], 401);
            }
        });
    })->create();
