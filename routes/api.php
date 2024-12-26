<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\GoodController;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AuthController;
use \App\Http\Controllers\OrderController;

Route::post('/login', [AuthController::class, 'login']);
Route::get('/', [MenuController::class, 'index']);

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart', [CartController::class, 'store']);
    Route::put('/cart', [CartController::class, 'update']);
    
    Route::post('/order', [OrderController::class, 'store']);
    
    Route::middleware('can:admin')->group(function () {
        // GOODS
        Route::post('/goods', [GoodController::class, 'store']);
        Route::patch('/goods/{good}', [GoodController::class, 'update']);
        Route::delete('/goods/{good}', [GoodController::class, 'destroy']);
    });
});
