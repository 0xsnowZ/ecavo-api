<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\WishlistController;
use App\Http\Controllers\Api\Admin\DashboardController;
use App\Http\Controllers\Api\Admin\AdminOrderController;
use App\Http\Controllers\Api\Admin\AdminProductController;
use App\Http\Controllers\Api\Admin\AdminCategoryController;

// ─── Public endpoints ──────────────────────────────────────────────────────────

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login',    [AuthController::class, 'login']);
});

Route::get('categories',              [CategoryController::class, 'index']);
Route::get('categories/{slug}',       [CategoryController::class, 'show']);
Route::get('categories/{slug}/products', [ProductController::class, 'byCategory']);

Route::get('products',       [ProductController::class, 'index']);
Route::get('products/{slug}', [ProductController::class, 'show']);

// Cart (guest + auth)
Route::prefix('cart')->group(function () {
    Route::get('/',                [CartController::class, 'index']);
    Route::post('add',             [CartController::class, 'add']);
    Route::patch('update/{id}',    [CartController::class, 'update']);
    Route::delete('remove/{id}',   [CartController::class, 'remove']);
    Route::post('apply-coupon',    [CartController::class, 'applyCoupon']);
});

// ─── Authenticated (Sanctum) endpoints ────────────────────────────────────────

Route::middleware('auth:sanctum')->group(function () {

    Route::get('auth/me',      [AuthController::class, 'me']);
    Route::post('auth/logout', [AuthController::class, 'logout']);

    // Orders
    Route::prefix('orders')->group(function () {
        Route::post('checkout',   [OrderController::class, 'checkout']);
        Route::get('/',           [OrderController::class, 'index']);
        Route::get('{id}',        [OrderController::class, 'show']);
        Route::get('{id}/track',  [OrderController::class, 'track']);
    });

    // Wishlist
    Route::prefix('wishlist')->group(function () {
        Route::get('/',                        [WishlistController::class, 'index']);
        Route::post('toggle/{product_id}',     [WishlistController::class, 'toggle']);
    });

    // ─── Admin (auth + admin role) ────────────────────────────────────────────

    Route::middleware('admin')->prefix('admin')->group(function () {

        Route::get('dashboard/stats',    [DashboardController::class, 'stats']);

        // Orders management
        Route::prefix('orders')->group(function () {
            Route::get('/',              [AdminOrderController::class, 'index']);
            Route::get('{id}',           [AdminOrderController::class, 'show']);
            Route::patch('{id}/status',  [AdminOrderController::class, 'updateStatus']);
            Route::put('{id}',           [AdminOrderController::class, 'update']);
        });

        // Products management
        Route::prefix('products')->group(function () {
            Route::get('/',     [AdminProductController::class, 'index']);
            Route::post('/',    [AdminProductController::class, 'store']);
            Route::put('{id}',  [AdminProductController::class, 'update']);
            Route::delete('{id}', [AdminProductController::class, 'destroy']);
        });

        // Categories management
        Route::prefix('categories')->group(function () {
            Route::get('/',     [AdminCategoryController::class, 'index']);
            Route::post('/',    [AdminCategoryController::class, 'store']);
            Route::put('{id}',  [AdminCategoryController::class, 'update']);
            Route::delete('{id}', [AdminCategoryController::class, 'destroy']);
        });
    });
});
