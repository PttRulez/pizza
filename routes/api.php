<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Admin\AdminGoodController;
use \App\Http\Controllers\Admin\AdminOrderController;

Route::post('/login', [AuthController::class, 'login']);
Route::get('/', [MenuController::class, 'index']);

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart', [CartController::class, 'store']);
    Route::put('/cart', [CartController::class, 'update']);
    
    Route::get('/order', [OrderController::class, 'index']);
    Route::post('/order', [OrderController::class, 'store']);
    
    Route::prefix('admin')->middleware('can:admin')->group(function () {
        Route::post('/goods', [AdminGoodController::class, 'store']);
        Route::patch('/goods/{good}', [AdminGoodController::class, 'update']);
        Route::delete('/goods/{good}', [AdminGoodController::class, 'destroy']);
        
        Route::get('/order', [AdminOrderController::class, 'index']);
        Route::patch('/order/{order}/change-status', [AdminOrderController::class, 'update']);
    });
});
