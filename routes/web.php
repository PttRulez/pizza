<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\PizzaController;

Route::get('/check', function () {
    return "check";
});

