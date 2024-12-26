<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AdminOrderController
{
    public function index()
    {
          return Order::with('user:id,name')
              ->select(['id', 'user_id', 'address', 'delivery_time', 'status'])
              ->get();
    }
    
    public function update(Request $request, Order $order): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::enum(OrderStatus::class)]
        ]);
        
        $order->update([
            'status' => $validated['status'],
        ]);
        
        return response()->json(['message' => 'status updated successfully']);
    }
}
