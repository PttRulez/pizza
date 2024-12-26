<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Good extends Model
{
    protected  $guarded = [];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'good_id');
    }
}
