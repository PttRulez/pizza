<?php

namespace App\Http\Controllers;

use App\Enums\GoodType;
use App\Models\Good;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GoodController extends Controller
{
    public function store(Request $request): string
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'type' => ['required', Rule::enum(GoodType::class)],
            'price' => 'required|decimal:0,1',
        ]);
        
        Good::create($validated);
        
        return 'Добавлено';
    }
    
    public function update(Request $request, Good $good)
    {
        $validated = $request->validate([
            'name' => ['sometimes', 'max:255'],
            'price' => 'sometimes|decimal:0,1',
        ]);
        
        $good->update($validated);
        
        return response("Заапдейчено", 200);
    }
    
    public function destroy(Request $request, Good $good)
    {
        if ($good->delete()) {
            return response("Удалено", 200);
        }
        
        return response("Удалено", 400);
    }
}
