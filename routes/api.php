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
use App\Http\Controllers\Api\Admin\AdminReviewController;
use App\Http\Controllers\Api\Admin\ImageUploadController;
use App\Http\Controllers\Api\RecentlyViewedController;
use App\Http\Controllers\Api\GoogleAuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\Admin\AdminBannerController;
use App\Http\Controllers\Api\Admin\AdminCouponController;
use App\Http\Controllers\Api\Admin\AdminNotificationController;
use App\Http\Controllers\Api\ReviewController;

// ─── Public endpoints ──────────────────────────────────────────────────────────

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register'])->middleware('throttle:10,1');
    Route::post('login',    [AuthController::class, 'login'])->middleware('throttle:5,1');
    Route::post('forgot-password', [AuthController::class, 'forgotPassword'])->middleware('throttle:3,1');
    Route::post('reset-password', [AuthController::class, 'resetPassword'])->middleware('throttle:3,1');
    
    // Google OAuth — browser navigates to these directly (not XHR)
    Route::get('google/redirect',       [GoogleAuthController::class, 'redirect']);
    Route::get('google/callback',       [GoogleAuthController::class, 'callback']);
    Route::post('google/token-login',   [GoogleAuthController::class, 'tokenLogin'])->middleware('throttle:10,1');
});

Route::get('categories',              [CategoryController::class, 'index']);
Route::get('categories/{slug}',       [CategoryController::class, 'show']);
Route::get('categories/{slug}/products', [ProductController::class, 'byCategory']);

Route::get('products',       [ProductController::class, 'index']);
Route::get('products/{slug}', [ProductController::class, 'show']);

// Recently viewed — public GET (guest: pass ?ids=1,2,3 from localStorage)
Route::get('recently-viewed', [RecentlyViewedController::class, 'index']);

// Banners - public GET
Route::get('banners', [BannerController::class, 'index']);

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
    Route::post('auth/profile',[ProfileController::class, 'update']);

    // Orders
    Route::prefix('orders')->group(function () {
        Route::post('checkout',               [OrderController::class, 'checkout']);
        Route::post('create-payment-intent',  [OrderController::class, 'createPaymentIntent']);
        Route::get('/',                       [OrderController::class, 'index']);
        Route::get('{id}',                    [OrderController::class, 'show']);
        Route::get('{id}/track',              [OrderController::class, 'track']);
    });

    // Wishlist
    Route::prefix('wishlist')->group(function () {
        Route::get('/',                        [WishlistController::class, 'index']);
        Route::post('toggle/{product_id}',     [WishlistController::class, 'toggle']);
    });

    // Recently viewed — auth routes
    Route::post('recently-viewed/{product_id}', [RecentlyViewedController::class, 'track']);

    // Reviews (customer-facing)
    Route::prefix('reviews')->group(function () {
        Route::get('eligible', [ReviewController::class, 'eligible']);
        Route::post('/',       [ReviewController::class, 'store']);
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
        // Image upload
        Route::post('upload/image',   [ImageUploadController::class, 'store']);
        Route::delete('upload/image', [ImageUploadController::class, 'destroy']);

        // Reviews moderation
        Route::prefix('reviews')->group(function () {
            Route::get('/',            [AdminReviewController::class, 'index']);
            Route::patch('{id}/approve', [AdminReviewController::class, 'approve']);
            Route::delete('{id}',      [AdminReviewController::class, 'destroy']);
        });

        // Banners
        Route::get('/banners/image', [AdminBannerController::class, 'getImage']);
        Route::apiResource('banners', AdminBannerController::class);
        Route::patch('/banners/{banner}/toggle-active', [AdminBannerController::class, 'toggleActive']);

        // Coupons
        Route::apiResource('coupons', AdminCouponController::class);
        Route::patch('/coupons/{coupon}/toggle-active', [AdminCouponController::class, 'toggleActive']);

        // Notifications
        Route::get('notifications', [AdminNotificationController::class, 'index']);
        Route::patch('notifications/{id}/read', [AdminNotificationController::class, 'markAsRead']);
    });
});
