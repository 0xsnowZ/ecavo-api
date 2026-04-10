<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Controllers\Api\AuthController;

class ProfileController extends Controller
{
    /**
     * POST /api/auth/profile
     * Updates user profile (name, phone) and handles avatar upload.
     * We use POST because multipart/form-data (required for file uploads)
     * is difficult to parse with PUT in standard PHP via Axios.
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name'   => 'required|string|max:255',
            'phone'  => 'nullable|string|max:20',
            'avatar' => 'nullable|file|mimes:jpeg,jpg,png,webp|max:4096',
        ]);

        $data = $request->only(['name', 'phone']);

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('avatars', $filename, 'public');
            
            // Delete old avatar if it's not a generic gravatar/external URL
            if ($user->avatar && !Str::startsWith($user->avatar, 'http')) {
                // Determine the relative path from the absolute URL if necessary,
                // but since we only stored the URL, we might need to extract the path.
                // Assuming we store full URLs in DB based on prior behavior.
                // Wait, if it's a full URL, we extract the path to delete:
                $baseUrl = Storage::disk('public')->url('');
                if (Str::startsWith($user->avatar, $baseUrl)) {
                    $oldPath = str_replace($baseUrl, '', $user->avatar);
                    Storage::disk('public')->delete($oldPath);
                }
            }

            $data['avatar'] = Storage::disk('public')->url($path);
        }

        $user->update($data);

        return app(AuthController::class)->me($request);
    }
}
