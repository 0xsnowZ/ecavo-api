<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageUploadController extends Controller
{
    /**
     * POST /api/admin/upload/image
     *
     * Accepts a single image file, stores it in storage/app/public/products/,
     * and returns the publicly accessible URL.
     *
     * Allowed types : jpeg, jpg, png, webp, gif
     * Max size      : 4 MB
     */
    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|file|mimes:jpeg,jpg,png,webp,gif|max:4096',
        ]);

        $file      = $request->file('image');
        $filename  = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path      = $file->storeAs('products', $filename, 'public');

        return response()->json([
            'url'      => Storage::disk('public')->url($path),
            'path'     => $path,
            'filename' => $filename,
        ], 201);
    }

    /**
     * DELETE /api/admin/upload/image
     * Body: { "path": "products/uuid.jpg" }
     */
    public function destroy(Request $request)
    {
        $request->validate(['path' => 'required|string']);

        // Safety: only allow deletion inside products/ folder
        $path = $request->input('path');
        if (! Str::startsWith($path, 'products/')) {
            return response()->json(['message' => 'غير مسموح.'], 403);
        }

        Storage::disk('public')->delete($path);

        return response()->json(['message' => 'تم حذف الصورة.']);
    }
}
