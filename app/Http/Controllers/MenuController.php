<?php

namespace App\Http\Controllers;

use App\Models\Drink;
use App\Models\Pizza;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $pizzas = Pizza::all()->map(function (Pizza $pizza) {
            return $pizza->toMenuItem();
        });
        
        $drinks = Drink::all()->map(function (Drink $drink) {
            return $drink->toMenuItem();
        });;
        
        return response()->json([
            'pizzas' => $pizzas,
            'drinks' => $drinks,
        ]);
    }
}
