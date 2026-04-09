<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{
    /** GET /api/admin/products */
    public function index(Request $request)
    {
        $query = Product::with('category')->withTrashed();

        if ($search = $request->get('search')) {
            $query->where('name_ar', 'like', "%{$search}%")
                  ->orWhere('name_en', 'like', "%{$search}%");
        }
        if ($category = $request->get('category_id')) {
            $query->where('category_id', $category);
        }

        $products = $query->latest()->paginate($request->get('per_page', 20));

        return response()->json([
            'data' => $products->items(),
            'meta' => ['total' => $products->total(), 'last_page' => $products->lastPage()],
        ]);
    }

    /** POST /api/admin/products */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name_ar'          => 'required|string|max:255',
            'name_en'          => 'required|string|max:255',
            'description_ar'   => 'nullable|string',
            'description_en'   => 'nullable|string',
            'price'            => 'required|numeric|min:0',
            'original_price'   => 'nullable|numeric|min:0',
            'discount_percent' => 'nullable|integer|min:0|max:100',
            'stock'            => 'required|integer|min:0',
            'category_id'      => 'required|exists:categories,id',
            'images'           => 'nullable|array',
            'specifications'   => 'nullable|array',
            'is_featured'      => 'boolean',
            'deal_ends_at'     => 'nullable|date',
        ]);

        $data['slug'] = Str::slug($data['name_en']) . '-' . Str::random(5);
        $product = Product::create($data);

        return response()->json([
            'message' => 'تم إضافة المنتج بنجاح.',
            'data'    => $product->load('category'),
        ], 201);
    }

    /** PUT /api/admin/products/:id */
    public function update(Request $request, int $id)
    {
        $product = Product::withTrashed()->findOrFail($id);

        $data = $request->validate([
            'name_ar'          => 'string|max:255',
            'name_en'          => 'string|max:255',
            'description_ar'   => 'nullable|string',
            'description_en'   => 'nullable|string',
            'price'            => 'numeric|min:0',
            'original_price'   => 'nullable|numeric|min:0',
            'discount_percent' => 'nullable|integer|min:0|max:100',
            'stock'            => 'integer|min:0',
            'category_id'      => 'exists:categories,id',
            'images'           => 'nullable|array',
            'specifications'   => 'nullable|array',
            'is_active'        => 'boolean',
            'is_featured'      => 'boolean',
            'deal_ends_at'     => 'nullable|date',
        ]);

        $product->update($data);

        return response()->json([
            'message' => 'تم تحديث المنتج بنجاح.',
            'data'    => $product->fresh('category'),
        ]);
    }

    /** DELETE /api/admin/products/:id (soft delete) */
    public function destroy(int $id)
    {
        Product::findOrFail($id)->delete();
        return response()->json(['message' => 'تم حذف المنتج.']);
    }
}
