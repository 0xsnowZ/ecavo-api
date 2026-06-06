<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * GET /api/auth/google/redirect
     *
     * Browser navigates here directly. Socialite builds the Google OAuth
     * URL with state param (CSRF protection) and redirects the browser.
     */
    public function redirect()
    {
        return Socialite::driver('google')
            ->stateless()   // SPA flow — no session
            ->redirect();
    }

    /**
     * GET /api/auth/google/callback
     *
     * Google redirects back here. We:
     *   1. Retrieve the Google user (state validated by Socialite)
     *   2. Find or create the local user (account linking by email)
     *   3. Upsert the social_accounts row
     *   4. Issue a Sanctum token
     *   5. Store the token under a random One-Time Code (OTC) in cache (TTL 60s)
     *   6. Redirect the browser to the frontend with only the OTC in the URL
     */
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Throwable $e) {
            // OAuth error or cancelled — send back to login with an error flag
            return redirect(env('FRONTEND_URL', 'http://localhost:5173') . '/login?error=oauth_failed');
        }

        $user = DB::transaction(function () use ($googleUser) {
            // ── Account Linking ──────────────────────────────────────────────
            $social = SocialAccount::where('provider', 'google')
                ->where('provider_id', $googleUser->getId())
                ->first();

            if ($social) {
                $social->update(['avatar' => $googleUser->getAvatar()]);
                $user = $social->user;
                if (!$user->avatar || Str::startsWith($user->avatar, 'http')) {
                    $user->update(['avatar' => $googleUser->getAvatar()]);
                }
                return $user;
            }

            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'name'   => $googleUser->getName(),
                    'email'  => $googleUser->getEmail(),
                    'avatar' => $googleUser->getAvatar(),
                ]);
            } else {
                if (!$user->avatar || Str::startsWith($user->avatar, 'http')) {
                    $user->update(['avatar' => $googleUser->getAvatar()]);
                }
            }

            SocialAccount::create([
                'user_id'     => $user->id,
                'provider'    => 'google',
                'provider_id' => $googleUser->getId(),
                'avatar'      => $googleUser->getAvatar(),
            ]);

            return $user;
        });

        // Rotate tokens
        $user->tokens()->where('name', 'api-token')->delete();
        $token = $user->createToken('api-token')->plainTextToken;

        // ── OTC: store token server-side, only send a random code in the URL ──
        // Prevents the Sanctum token from appearing in browser history, server
        // access logs, or referrer headers.  TTL: 60 s — single use.
        $otc = Str::random(64);
        Cache::put('google_otc:' . $otc, $token, now()->addSeconds(60));

        return redirect(env('FRONTEND_URL', 'http://localhost:5173') . '/login?otc=' . $otc);
    }

    /**
     * POST /api/auth/google/token-login
     *
     * Frontend sends the OTC received from the URL. We resolve the real Sanctum
     * token from cache (Cache::pull → single-use, auto-deleted), then issue the
     * HTTP-only cookie. The raw token is never exposed to browser JS.
     */
    public function tokenLogin(\Illuminate\Http\Request $request)
    {
        $data = $request->validate(['otc' => 'required|string|size:64']);

        // Cache::pull retrieves AND deletes atomically — true single use
        $token = Cache::pull('google_otc:' . $data['otc']);

        if (!$token) {
            return response()->json(['message' => 'رمز الدخول غير صالح أو منتهي الصلاحية.'], 401);
        }

        $pat = PersonalAccessToken::findToken($token);

        if (!$pat || !$pat->tokenable) {
            return response()->json(['message' => 'رمز الدخول غير صالح أو منتهي الصلاحية.'], 401);
        }

        return app(AuthController::class)->respondWithToken($pat->tokenable, $token);
    }
}
