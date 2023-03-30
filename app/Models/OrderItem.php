<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'order_items';

    protected $fillable = [
        'order_id',
        'product_id',
        'product_code',
        'product_name',
        'sale_price',
        'origin_price',
        'quantity',
        'sub_total',
        'total',
        'child_master_field_id',
        'created_at',
        'updated_at',
    ];
}
