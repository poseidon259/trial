<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'cart';

    protected $fillable = [
        'user_id',
        'product_id',
        'total_price',
        'delivery_fee_total',
        'created_at',
        'updated_at'
    ];

    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'cart_id', 'id');
    }
}
