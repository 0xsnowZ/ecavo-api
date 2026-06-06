<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::with('children')->whereNull('parent_id')->orderBy('sort_order');

        $categories = $query->paginate($request->get('per_page', 50));

        return response()->json([
            'data' => $categories->items(),
            'meta' => [
                'total'        => $categories->total(),
                'current_page' => $categories->currentPage(),
                'last_page'    => $categories->lastPage(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name_ar'    => 'required|string|max:255',
            'name_en'    => 'required|string|max:255',
            'name_fr'    => 'nullable|string|max:255',
            'parent_id'  => 'nullable|exists:categories,id',
            'image'      => 'nullable|string',
            'sort_order' => 'integer',
        ]);

        $data['slug'] = Str::slug($data['name_en']) . '-' . Str::random(4);
        $category = Category::create($data);

        return response()->json([
            'message' => 'تم إضافة القسم.',
            'data'    => $category,
        ], 201);
    }

    public function update(Request $request, int $id)
    {
        $category = Category::findOrFail($id);

        $data = $request->validate([
            'name_ar'    => 'string|max:255',
            'name_en'    => 'string|max:255',
            'name_fr'    => 'nullable|string|max:255',
            'parent_id'  => 'nullable|exists:categories,id',
            'image'      => 'nullable|string',
            'is_active'  => 'boolean',
            'sort_order' => 'integer',
        ]);

        $category->update($data);

        return response()->json(['message' => 'تم تحديث القسم.', 'data' => $category]);
    }

    public function destroy(int $id)
    {
        $category = Category::findOrFail($id);

        if ($category->products()->exists()) {
            return response()->json([
                'message' => 'لا يمكن حذف قسم يحتوي على منتجات.',
            ], 422);
        }

        $category->delete();
        return response()->json(['message' => 'تم حذف القسم.']);
    }
}
