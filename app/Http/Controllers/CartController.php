<?php

namespace App\Http\Controllers;

use App\Enums\GoodType;
use App\Models\CartItem;
use App\Models\Good;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $user = Auth::user();
        $data = $request->validate([
            'good_id' => 'required|integer|exists:goods,id',
            'quantity' => 'required|integer|min:1',
        ]);
        
        // Достаем текущую корзину из базы
        $items = $user->cartItems->load('good');
        
        $good = Good::findOrFail($data['good_id']);
        
        // Подсчитываем кол-во товаров того типа что нам прислали
        $count = $items->reduce(function (int $acc, CartItem $item) use ($good) {
            if ($item->good->type == $good->type) {
                return $item->quantity + $acc;
            }
            
            return $acc;
        },  $data['quantity']);
        
        // Валидируем кол-во по видам товаров
        if (($count > 10 && $good->type == GoodType::Pizza->value)) {
            return response()->json(['message' => 'Максимум 10 пицц'], 400);
        } else if ($count > 20 && $good->type == GoodType::Drink->value) {
            return response()->json(['message' => 'Максимум 20 напитков'], 400);
        }
        
        // Сохраняем в БД
        $item = $items->firstWhere('good_id', $data['good_id']);
        if ($item) {
            $item->increment('quantity', $data['quantity']);
            $item->save();
        } else {
            $data['user_id'] = $user->id;
            CartItem::create($data);
        }
        
        return response()->json(['message' => 'Item added to cart successfully']);
    }
    
    public function update(Request $request): JsonResponse
    {
        $user = Auth::user();
        $data = $request->validate([
            '*.good_id' => 'required|integer|exists:goods,id',
            '*.quantity' => 'required|integer|min:1',
        ]);
        
        // Удаляем всё старое
        $user->cartItems()->delete();
        
        // собираем пришедшие данные в коллецию картитемов
        $c = collect([]);
        foreach ($data as $item) {
            $c->push(new CartItem($item));
        }
        
        // Подсчитываем пиццу и напитки
        $q = $c->reduce(function($acc, $item) {
            $acc[$item->good->type] += $item->quantity;
            
            return $acc;
        }, [GoodType::Pizza->value => 0,
            GoodType::Drink->value => 0]);
        
        // Валидируем их кол-во
        if ($q[GoodType::Pizza->value] > 10) {
            return response()->json(['message' => 'Максимум 10 пицц', 'cart' => $c], 400);
        }
        if ($q[GoodType::Drink->value] > 20) {
            return response()->json(['message' => 'Максимум 20 напитков', 'cart' => $c], 400);
        }
        
        
        foreach ($c as $item) {
            $item->user_id = $user->id;
            $item->save();
        }
        
        return response()->json(['message' => 'Items added to cart successfully']);
    }
    
    public function index(Request $request): JsonResponse
    {
        $user = auth()->user();
        
        $items = $user->cartItems->map(function ($cartItem) {
            return [
                'id' => $cartItem->id,
                'type' => $cartItem->item_type, // e.g., 'App\Models\Pizza'
                'details' => $cartItem->item, // Morph to the related model (Pizza or Drink)
                'quantity' => $cartItem->quantity,
            ];
        });
        
        return response()->json($items);
    }
    
    public function removeFromCart(Request $request)
    {
        $user = auth()->user();
        
        $cartItem = $user->cartItems()->where('item_id', $request->item_id)
            ->where('item_type', $request->item_type)
            ->first();
        
        if ($cartItem) {
            $cartItem->delete();
            return response()->json(['message' => 'Item removed from cart successfully']);
        }
        
        return response()->json(['message' => 'Item not found in cart'], 404);
    }
}
