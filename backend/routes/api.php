<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\WastageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReportController;

Route::post('auth/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('me', [AuthController::class, 'me']);

    Route::get('products', [ProductController::class, 'index']);
    Route::get('products/{id}', [ProductController::class, 'show']);
    Route::post('products', [ProductController::class, 'store']);
    Route::patch('products/{id}', [ProductController::class, 'update']);

    Route::get('inventory', [InventoryController::class, 'index']);
    Route::post('inventory', [InventoryController::class, 'store']);
    Route::post('inventory/wastage', [InventoryController::class, 'wastage']);

    Route::post('orders', [OrderController::class, 'store']);
    Route::patch('orders/{id}/weight', [OrderController::class, 'updateWeight']);
    Route::get('orders/{id}', [OrderController::class, 'show']);

    Route::get('suppliers', [SupplierController::class, 'index']);
    Route::post('suppliers', [SupplierController::class, 'store']);

    Route::post('wastage', [WastageController::class, 'record']);

    Route::get('reports/daily', [ReportController::class, 'daily']);
});
