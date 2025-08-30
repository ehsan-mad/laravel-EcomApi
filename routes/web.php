<?php

use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\TokenAuthenticate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

// Web routes for auth, wishlist/cart, invoices, and payment callbacks

// Public: authentication
Route::get('/userLogin/{email}', [UserController::class, 'login']);
Route::get('/verifyOTP/{email}/{otp}', [UserController::class, 'verifyOtp']);
Route::get('/userLogout', [UserController::class, 'logout']);

// Protected: requires valid token
Route::middleware([TokenAuthenticate::class])->group(function () {
    // Wishlist
    Route::get('/ProductWishList', [ProductController::class, 'ProductWishList']);
    Route::get('/CreateWishList/{product_id}', [ProductController::class, 'CreateWishList']);
    Route::get('/RemoveWishList/{product_id}', [ProductController::class, 'RemoveWishList']);

    // Cart
    Route::post('/CreateCartList', [ProductController::class, 'CreateCartList']);
    Route::get('/CartList', [ProductController::class, 'CartList']);
    Route::get('/DeleteCartList/{product_id}', [ProductController::class, 'DeleteCartList']);

    // Invoices
    Route::get('/InvoiceCreate', [InvoiceController::class, 'InvoiceCreate']);
    Route::get('/InvoiceList', [InvoiceController::class, 'InvoiceList']);
    Route::get('/InvoiceProductList/{invoice_id}', [InvoiceController::class, 'InvoiceProductList']);
});

// Public: payment callbacks (support both GET and POST)
Route::match(['get', 'post'], '/PaymentSuccess', [InvoiceController::class, 'PaymentSuccess']);
Route::match(['get', 'post'], '/PaymentFail', [InvoiceController::class, 'PaymentFail']);
Route::match(['get', 'post'], '/PaymentCancel', [InvoiceController::class, 'PaymentCancel']);
Route::match(['get', 'post'], '/PaymentIPN', [InvoiceController::class, 'PaymentIPN']);

// Helper endpoint for CockroachDB product IDs
Route::get('/productIds', function () {
    return response()->json([
        'products' => DB::table('products')->select('id', 'name')->get(),
        'categories' => DB::table('categories')->select('id', 'category_name')->get(),
        'brands' => DB::table('brands')->select('id', 'brand_name')->get()
    ]);
});

// Debug endpoint to check authentication
Route::middleware([TokenAuthenticate::class])->get('/debug-auth', function (Request $request) {
    return response()->json([
        'user_id' => $request->headers->get('id'),
        'email' => $request->headers->get('email'),
        'token' => $request->cookie('token'),
        'message' => 'Authentication working'
    ]);
});

