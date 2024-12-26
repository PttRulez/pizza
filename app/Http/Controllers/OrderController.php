<?php

namespace App\Http\Controllers;

use App\Enums\GoodType;
use App\Models\Good;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $user = Auth::user();
        $data = $request->validate([
            'address' => 'required|string',
            'phone_number' => 'required|string',
            'email' => 'required|email',
            'delivery_time' => 'required|date_format:Y-m-d H:i:s',
            'items' => 'required|array',
            'items.*.good_id' => 'required|integer|exists:goods,id',
            'items.*.quantity' => 'required|integer',
        ]);
        
        $pizzasCount = 0;
        $drinksCount = 0;
        
        foreach ($data['items'] as $item) {
            $i = Good::find($item['good_id']);
            
            // Поидее это уже проверено в $request->validate(), но мало ли
            if (!$i) {
                return response()->json(["message" => "Товара с id {$item['good_id']} не существует" ], 400);
            }
            
            if ($i->type == GoodType::Pizza->value) {
                $pizzasCount++;
            } else if ($i->type == GoodType::Drink->value) {
                $drinksCount++;
            }
        }
        
        if ($pizzasCount > 10) {
            return response()->json(['message' => 'Максимум 10 пицц'], 400);
        }
        if ($drinksCount > 20) {
            return response()->json(['message' => 'Максимум 20 напитков'], 400);
        }
        
        
        DB::transaction(function () use($user, $data) {
            $order = $user->orders()->create([
                'address' => $data['address'],
                'phone_number' => $data['phone_number'],
                'email' => $data['email'],
                'delivery_time' => $data['delivery_time'],
            ]);
            
            $order->items()->createMany($data['items']);
        });
        
        return response()->json(["message" => "HOWDYHO"]);
    }
}
