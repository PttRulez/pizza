<?php

namespace App\Http\Controllers;

use App\Models\Good;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $goods = Good::all();
        
        return response()->json($goods);
    }
}
