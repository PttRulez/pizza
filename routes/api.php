<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\GoodController;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\AuthController;

Route::post('/login', [AuthController::class, 'login']);
Route::get('/', [MenuController::class, 'index']);

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    
    Route::post('/cart', [CartController::class, 'addToCart']);
    Route::put('/cart', [CartController::class, 'addMultipleToCart']);
    
    Route::post('/order', [CartController::class, 'addToCart']);
    
    Route::middleware('can:admin')->group(function () {
        // GOODS
        Route::post('/goods', [GoodController::class, 'store']);
        Route::patch('/goods/{good}', [GoodController::class, 'update']);
        Route::delete('/goods/{good}', [GoodController::class, 'destroy']);
    });
});
