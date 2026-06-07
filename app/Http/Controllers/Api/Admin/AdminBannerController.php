<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminBannerController extends Controller
{
    public function index()
    {
        return response()->json(Banner::orderBy('order')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'is_active' => 'boolean',
            'order' => 'integer',
        ]);

        $path = $request->file('image')->store('banners', 'public');

        $banner = Banner::create([
            'image_path' => $path,
            'is_active' => $request->is_active ?? true,
            'order' => $request->order ?? 0,
        ]);

        return response()->json($banner, 201);
    }

    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'is_active' => 'boolean',
            'order' => 'integer',
        ]);

        if ($request->hasFile('image')) {
            if (Storage::disk('public')->exists($banner->image_path)) {
                Storage::disk('public')->delete($banner->image_path);
            }
            $banner->image_path = $request->file('image')->store('banners', 'public');
        }

        if ($request->has('is_active')) {
            $banner->is_active = $request->is_active;
        }
        
        if ($request->has('order')) {
            $banner->order = $request->order;
        }

        $banner->save();

        return response()->json($banner);
    }

    public function destroy(Banner $banner)
    {
        if (Storage::disk('public')->exists($banner->image_path)) {
            Storage::disk('public')->delete($banner->image_path);
        }
        
        $banner->delete();

        return response()->json(['message' => 'Banner deleted']);
    }

    public function toggleActive(Banner $banner)
    {
        $banner->is_active = !$banner->is_active;
        $banner->save();

        return response()->json($banner);
    }

    public function getImage(Request $request)
    {
        $path = $request->query('path');
        if (!$path || !Storage::disk('public')->exists($path)) {
            return response()->json(['error' => 'File not found'], 404);
        }

        $file = Storage::disk('public')->get($path);
        $mime = Storage::disk('public')->mimeType($path);
        
        return response($file, 200)
            ->header('Content-Type', $mime)
            ->header('Access-Control-Allow-Origin', '*');
    }
}
