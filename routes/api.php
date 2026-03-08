<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\ReviewController;
use App\Http\Controllers\API\VendorController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{slug}', [ProductController::class, 'show']);

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{slug}', [CategoryController::class, 'show']);

Route::get('/vendors', [VendorController::class, 'index']);
Route::get('/vendors/{slug}', [VendorController::class, 'show']);

// Authenticated routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Cart
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart', [CartController::class, 'add']);
    Route::put('/cart/{productId}', [CartController::class, 'update']);
    Route::delete('/cart/{productId}', [CartController::class, 'remove']);
    Route::delete('/cart', [CartController::class, 'clear']);

    // Orders
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::post('/checkout', [OrderController::class, 'checkout']);

    // Reviews
    Route::post('/products/{productId}/reviews', [ReviewController::class, 'store']);

    // Vendor routes
    Route::prefix('vendor')->middleware('vendor')->group(function () {
        Route::get('/profile', [\App\Http\Controllers\API\Vendor\ProfileController::class, 'show']);
        Route::put('/profile', [\App\Http\Controllers\API\Vendor\ProfileController::class, 'update']);

        Route::get('/products', [\App\Http\Controllers\API\Vendor\ProductController::class, 'index']);
        Route::post('/products', [\App\Http\Controllers\API\Vendor\ProductController::class, 'store']);
        Route::get('/products/{id}', [\App\Http\Controllers\API\Vendor\ProductController::class, 'show']);
        Route::put('/products/{id}', [\App\Http\Controllers\API\Vendor\ProductController::class, 'update']);
        Route::delete('/products/{id}', [\App\Http\Controllers\API\Vendor\ProductController::class, 'destroy']);

        Route::get('/orders', [\App\Http\Controllers\API\Vendor\OrderController::class, 'index']);
        Route::get('/stats', [\App\Http\Controllers\API\Vendor\OrderController::class, 'stats']);
    });

    // Admin routes
    Route::prefix('admin')->middleware('admin')->group(function () {
        Route::get('/stats', [\App\Http\Controllers\API\Admin\OrderController::class, 'stats']);

        Route::get('/vendors', [\App\Http\Controllers\API\Admin\VendorController::class, 'index']);
        Route::get('/vendors/{id}', [\App\Http\Controllers\API\Admin\VendorController::class, 'show']);
        Route::put('/vendors/{id}/approve', [\App\Http\Controllers\API\Admin\VendorController::class, 'approve']);
        Route::put('/vendors/{id}/suspend', [\App\Http\Controllers\API\Admin\VendorController::class, 'suspend']);
        Route::put('/vendors/{id}/commission', [\App\Http\Controllers\API\Admin\VendorController::class, 'updateCommission']);

        Route::get('/orders', [\App\Http\Controllers\API\Admin\OrderController::class, 'index']);
        Route::get('/orders/{id}', [\App\Http\Controllers\API\Admin\OrderController::class, 'show']);
        Route::put('/orders/{id}/status', [\App\Http\Controllers\API\Admin\OrderController::class, 'updateStatus']);

        Route::get('/categories', [\App\Http\Controllers\API\Admin\CategoryController::class, 'index']);
        Route::post('/categories', [\App\Http\Controllers\API\Admin\CategoryController::class, 'store']);
        Route::put('/categories/{id}', [\App\Http\Controllers\API\Admin\CategoryController::class, 'update']);
        Route::delete('/categories/{id}', [\App\Http\Controllers\API\Admin\CategoryController::class, 'destroy']);
    });
});
