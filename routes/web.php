<?php

use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\TokenAuthenticate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

// Root route
Route::get('/', function () {
    return response()->json([
        'message' => 'Laravel E-commerce API is running successfully!',
        'version' => '1.0.0',
        'endpoints' => [
            'health' => '/health',
            'product_info' => '/productIds',
            'authentication' => '/userLogin/{email}',
            'verify_otp' => '/verifyOTP/{email}/{otp}'
        ]
    ]);
});

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
    try {
        $products = DB::table('products')->select('id', 'name')->limit(10)->get();
        $categories = DB::table('categories')->select('id', 'category_name')->limit(10)->get();
        $brands = DB::table('brands')->select('id', 'brand_name')->limit(10)->get();
        
        return response()->json([
            'status' => 'success',
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
            'counts' => [
                'total_products' => DB::table('products')->count(),
                'total_categories' => DB::table('categories')->count(),
                'total_brands' => DB::table('brands')->count()
            ]
        ]);
    } catch (\Exception $e) {
    Log::error('productIds error', [ 'error' => $e->getMessage(), 'trace' => $e->getTraceAsString() ]);
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'type' => get_class($e)
        ], 500);
    }
});

// Minimal DB ping endpoint
Route::get('/db-ping', function () {
    try {
        $one = DB::select('SELECT 1 as one');
        return ['ok' => true, 'one' => $one[0]->one ?? null];
    } catch (\Throwable $t) {
        return response()->json(['ok' => false, 'error' => $t->getMessage(), 'type' => get_class($t)], 500);
    }
});

// Raw single row fetch tests
Route::get('/products-sample', function () {
    try {
        return [ 'rows' => DB::table('products')->select('id','name')->limit(1)->get() ];
    } catch (\Throwable $t) { return response()->json(['error' => $t->getMessage(), 'type' => get_class($t)], 500); }
});
Route::get('/categories-sample', function () {
    try {
        return [ 'rows' => DB::table('categories')->select('id','category_name')->limit(1)->get() ];
    } catch (\Throwable $t) { return response()->json(['error' => $t->getMessage(), 'type' => get_class($t)], 500); }
});
Route::get('/brands-sample', function () {
    try {
        return [ 'rows' => DB::table('brands')->select('id','brand_name')->limit(1)->get() ];
    } catch (\Throwable $t) { return response()->json(['error' => $t->getMessage(), 'type' => get_class($t)], 500); }
});

// Simple health check endpoint
Route::get('/health', function () {
    return response()->json([
        'status' => 'healthy',
        'timestamp' => now(),
        'php_version' => PHP_VERSION,
        'laravel_version' => app()->version(),
        'memory_usage' => memory_get_usage(true) / 1024 / 1024 . ' MB'
    ]);
});

// Simple database test endpoint
Route::get('/db-test', function () {
    try {
        // Increase memory limit
        ini_set('memory_limit', '256M');
        
        $result = DB::select('SELECT COUNT(*) as count FROM products');
        $product_count = $result[0]->count ?? 0;
        
        return response()->json([
            'status' => 'database_connected',
            'product_count' => $product_count,
            'memory_usage' => memory_get_usage(true) / 1024 / 1024 . ' MB'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'database_error',
            'message' => $e->getMessage()
        ], 500);
    }
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

// Route list debug (DO NOT enable in production permanently)
Route::get('/routes-debug', function () {
    $routes = collect(Route::getRoutes())->map(function ($route) {
        return [
            'method' => implode('|', $route->methods()),
            'uri' => $route->uri(),
            'name' => $route->getName(),
            'action' => $route->getActionName(),
        ];
    });
    return response()->json($routes->values());
});

// SSLCommerz config debug (mask password)
Route::get('/payment-config', function () {
    $row = \App\Models\SslcommerzAccount::first();
    if(!$row){ return ['configured'=>false]; }
    return [
        'configured'=>true,
        'store_id'=>$row->store_id,
        'store_password'=> $row->store_password ? substr($row->store_password,0,2).'***' : null,
        'currency'=>$row->currency,
        'init_url'=>$row->init_url,
        'success_url'=>$row->success_url,
        'fail_url'=>$row->fail_url,
        'cancel_url'=>$row->cancel_url,
        'ipn_url'=>$row->ipn_url,
    ];
});

