<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Support\Facades\DB;
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
     *   4. Issue a Sanctum token and set it as an HTTP-only cookie
     *   5. Redirect the browser back to the frontend home page
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
            // If we already have a social_accounts row, just load the user.
            $social = SocialAccount::where('provider', 'google')
                ->where('provider_id', $googleUser->getId())
                ->first();

            if ($social) {
                // Keep the avatar up-to-date
                $social->update(['avatar' => $googleUser->getAvatar()]);
                
                // Sync to user if they don't have a custom uploaded avatar
                $user = $social->user;
                if (!$user->avatar || \Illuminate\Support\Str::startsWith($user->avatar, 'http')) {
                    $user->update(['avatar' => $googleUser->getAvatar()]);
                }
                
                return $user;
            }

            // Check whether a user with this email already exists (password-based account)
            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                // New user — create the account (password is null → OAuth-only)
                $user = User::create([
                    'name'   => $googleUser->getName(),
                    'email'  => $googleUser->getEmail(),
                    'avatar' => $googleUser->getAvatar(),
                    // password stays null — nullable since our migration
                ]);
            } else {
                // Existing user — sync avatar if they don't have a custom one
                if (!$user->avatar || \Illuminate\Support\Str::startsWith($user->avatar, 'http')) {
                    $user->update(['avatar' => $googleUser->getAvatar()]);
                }
            }

            // Link this Google account to the user (works for both new & existing)
            SocialAccount::create([
                'user_id'     => $user->id,
                'provider'    => 'google',
                'provider_id' => $googleUser->getId(),
                'avatar'      => $googleUser->getAvatar(),
            ]);

            return $user;
        });

        // Rotate tokens: remove any old api-token, issue a fresh one
        $user->tokens()->where('name', 'api-token')->delete();
        $token = $user->createToken('api-token')->plainTextToken;

        // Build the HTTP-only cookie (reuses AuthController helper)
        $authController = app(AuthController::class);
        $cookie = cookie(
            'access_token',
            $token,
            60 * 24 * 30,   // 30 days
            '/',
            null,
            false,           // secure — set true in production (HTTPS)
            true,            // httpOnly
            false,
            'Lax'
        );

        // Redirect browser to the frontend. Cookie travels with the response.
        return redirect(env('FRONTEND_URL', 'http://localhost:5173'))
            ->withCookie($cookie);
    }
}
