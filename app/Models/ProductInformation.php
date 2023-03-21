<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductInformation extends Model
{
    use HasFactory;

    protected $table = 'product_information';

    protected $fillable = [
        'product_id',
        'stock',
        'sale_price',
        'origin_price',
        'product_code',
        'created_at',
        'updated_at',
    ];
}
