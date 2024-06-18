<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CkeditorController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('v1/')->group(function () {
    Route::controller(ProfileController::class)->group(function () {
        Route::get('user/{id}/profile', 'show');
        Route::put('user/{id}/profile', 'update');
    });

    Route::controller(OrderController::class)->group(function () {
        Route::get('user/{id}/orders', 'getOrderByUser');
        Route::patch('orders/{id}', 'update');
        Route::get('orders/revenue', 'getRevenue');
        Route::get('orders/total', 'countOrder');
        Route::get('orders/search', 'search');
    });

    Route::controller(ProductController::class)->prefix('products/')->group(function () {
        Route::get('total', 'countProduct');
        Route::patch('{id}', 'updateStatus');
        Route::post('', 'create');
        Route::put('{id}', 'update');
        Route::get('best-selling-products', 'getBestSellingProducts');
        Route::get('worst-selling-products', 'getWorstSellingProducts');
    });

    Route::get('/products/{id}/detail', [ProductController::class, 'edit']);

    Route::controller(UserController::class)->group(function () {
        Route::get('customers/total', 'countCustomer');
        Route::get('staff/log', 'getStaffLog');
    });
});

Route::prefix('v1/')->group(function () {
    Route::controller(CategoryController::class)->prefix('categories')->group(function () {
        Route::get('/', 'index');
        Route::get('/{slug}', 'show');
    });

    Route::controller(ProductController::class)->prefix('products/')->group(function () {
        Route::get('special', 'getSpecial');
        Route::get('sale', 'getSale');
        Route::get('suggest', 'getSuggest');
        Route::get('other', 'getOther');
        Route::get('', 'index');
        Route::get('export', 'exportProduct');
        Route::post('import', 'importProduct');
        Route::get('{slug}', 'getProductBySlug');
    });

    Route::get('/categories/{id}/products', [ProductController::class, 'getProductByCategoryId']);

    Route::controller(AuthController::class)->group(function () {
        Route::post('login', 'login');
        Route::post('register', 'register');
        Route::post('forgot', 'forgot');
        Route::get('reset', 'checkToken');
        Route::patch('reset', 'resetPassword');
        Route::get('logout', 'logout');
    });

    Route::controller(CartController::class)->group(function () {
        Route::get('user/{id}/cart/', 'getCartByUserId');
        Route::post('cart', 'store');
        Route::patch('cart/{id}', 'update');
        Route::delete('cart/{id}', 'destroy');
    });

    Route::get('cities', [CityController::class, 'index']);
    Route::get('cities/{id}/districts', [DistrictController::class, 'getDistrictByCityId']);

    Route::post('payment/init', [PaymentController::class, 'init']);

    Route::controller(OrderController::class)->prefix('orders/')->group(function () {
        Route::post('', 'store');
        Route::get('{id}/export', 'export');
    });

    Route::post('ckeditor/upload', [CkeditorController::class, 'upload']);

    Route::controller(CommentController::class)->prefix('comments/')->group(function () {
        Route::post('', 'store');
    });
});
