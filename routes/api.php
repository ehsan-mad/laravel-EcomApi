<?php

use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/brandList', [BrandController::class, 'BrandList']);
Route::get('/categoryList', [CategoryController::class, 'CategoryList']);

Route::get('/listProductByCategory/{id}', [ProductController::class, 'ListProductByCategory']);
Route::get('/listProductByBrand/{id}', [ProductController::class, 'ListProductByBrand']);
Route::get('/listProductByRemarks/{remark}', [ProductController::class, 'ListProductByRemarks']);
Route::get('/listProductSlider', [ProductController::class, 'ListProductSlider']);
Route::get('/productDetails/{id}', [ProductController::class, 'ProductDetailsById']);
Route::get('/listReviewByProduct/{id}', [ProductController::class, 'ListReviewByProduct']);


Route::post('/PaymentIPN', [InvoiceController::class, 'PaymentIPN']);
