<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Order extends Model
{
    protected $guarded = [];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }
    
    public function goods(): BelongsToMany
    {
        return $this->belongsToMany(Good::class, 'order_items', 'order_id', 'good_id')->withPivot('quantity');
    }
}
