<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $table = 'cart_items';

    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
        'sale_price',
        'origin_price',
        'created_at',
        'updated_at'
    ];

    public function productImages()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'product_id');
    }
}
