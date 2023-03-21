<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductFavorite extends Model
{
    use HasFactory;

    protected $table = 'user_favorite_product';

    protected $fillable = [
        'user_id',
        'product_id',
        'status',
        'created_at',
        'updated_at',
    ];
}
