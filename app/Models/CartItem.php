<?php

namespace App\Models;

use App\Exceptions\PizzaLimitExceededException;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class CartItem extends Model
{
    protected $guarded = [];
    
    
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
   public function good()
   {
       return $this->belongsTo(Good::class);
   }
   
   public static function checkCart(Collection $cartItems)
   {
   
   }
}
