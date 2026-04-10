<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Reads the HTTP-only `access_token` cookie and injects it as a Bearer
 * Authorization header so Sanctum's existing token-guard picks it up
 * without any changes to the Sanctum configuration.
 *
 * This approach means the frontend NEVER touches the token value directly
 * (the cookie is HttpOnly), yet every API call is still authenticated.
 */
class InjectTokenFromCookie
{
    public function handle(Request $request, Closure $next): mixed
    {
        if (
            !$request->hasHeader('Authorization') &&
            $token = $request->cookie('access_token')
        ) {
            $request->headers->set('Authorization', 'Bearer ' . $token);
        }

        return $next($request);
    }
}
