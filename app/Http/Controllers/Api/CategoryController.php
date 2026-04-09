<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /** GET /api/categories — full tree with children */
    public function index()
    {
        $categories = Category::with('children')
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(fn($c) => $this->formatCategory($c));

        return response()->json(['data' => $categories]);
    }

    /** GET /api/categories/:slug */
    public function show(string $slug)
    {
        $category = Category::with(['children', 'products' => fn($q) => $q->active()])
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return response()->json(['data' => $this->formatCategory($category)]);
    }

    private function formatCategory(Category $c): array
    {
        return [
            'id'       => $c->id,
            'name_ar'  => $c->name_ar,
            'name_en'  => $c->name_en,
            'slug'     => $c->slug,
            'image'    => $c->image,
            'children' => $c->children->map(fn($child) => $this->formatCategory($child)),
        ];
    }
}
