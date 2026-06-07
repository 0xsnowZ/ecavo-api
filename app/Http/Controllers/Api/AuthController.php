<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;

class AuthController extends Controller
{
    /** POST /api/auth/register */
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone'    => 'nullable|string|max:20',
        ]);

        $user  = User::create($data);
        $token = $user->createToken('api-token')->plainTextToken;

        return $this->respondWithToken($user, $token, 201);
    }

    /** POST /api/auth/login */
    public function login(Request $request)
    {
        $data = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $data['email'])->first();

        if (!$user || !$user->password || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['البريد الإلكتروني أو كلمة المرور غير صحيحة.'],
            ]);
        }

        // Rotate: delete old api-token, issue fresh one
        $user->tokens()->where('name', 'api-token')->delete();
        $token = $user->createToken('api-token')->plainTextToken;

        return $this->respondWithToken($user, $token);
    }

    /** POST /api/auth/logout */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()
            ->json(['message' => 'تم تسجيل الخروج بنجاح.'])
            ->withoutCookie('access_token');
    }

    /** POST /api/auth/forgot-password */
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $token = Str::random(60);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'email' => $request->email,
                'token' => Hash::make($token),
                'created_at' => now()
            ]
        );

        $frontendUrl = env('FRONTEND_URL', 'http://localhost:5173');
        $resetUrl = $frontendUrl . '/reset-password?token=' . $token . '&email=' . urlencode($request->email);

        Mail::to($request->email)->send(new ResetPasswordMail($resetUrl));

        return response()->json(['message' => 'لقد قمنا بإرسال رابط إعادة تعيين كلمة المرور إلى بريدك الإلكتروني.']);
    }

    /** POST /api/auth/reset-password */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $record = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (!$record || !Hash::check($request->token, $record->token)) {
            throw ValidationException::withMessages([
                'email' => ['رابط إعادة تعيين كلمة المرور غير صالح أو منتهي الصلاحية.'],
            ]);
        }

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return response()->json(['message' => 'تم إعادة تعيين كلمة المرور بنجاح.']);
    }

    /** GET /api/auth/me */
    public function me(Request $request)
    {
        return response()->json(['user' => $this->formatUser($request->user())]);
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    /**
     * Returns the user JSON and sets an HTTP-only `access_token` cookie.
     * The cookie is read by InjectTokenFromCookie middleware on all future
     * requests — the JS client never sees the token value itself.
     */
    public function respondWithToken(User $user, string $token, int $status = 200)
    {
        $cookie = cookie(
            'access_token',      // name
            $token,              // value
            60 * 24 * 30,        // 30 days in minutes
            '/',                 // path
            null,                // domain (null = current)
            true,                // secure
            true,                // httpOnly — JS cannot read this cookie
            false,               // raw
            'None'               // sameSite
        );

        return response()
            ->json(['user' => $this->formatUser($user)], $status)
            ->withCookie($cookie);
    }

    private function formatUser(User $user): array
    {
        // Use the stored avatar, or fall back to Gravatar derived from the email.
        // ?d=mp → shows a generic silhouette for emails with no Gravatar account.
        // ?s=200 → 200×200 px, enough for any UI size.
        $avatar = $user->avatar
            ?: sprintf(
                'https://www.gravatar.com/avatar/%s?s=200&d=mp',
                md5(strtolower(trim($user->email)))
            );

        return [
            'id'     => $user->id,
            'name'   => $user->name,
            'email'  => $user->email,
            'phone'  => $user->phone,
            'avatar' => $avatar,
            'role'   => $user->role,
        ];
    }
}
