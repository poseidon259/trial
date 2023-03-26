<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'created_by',
        'description_list',
        'description_detail',
        'category_id',
        'status',
        'image',
        'created_by',
        'updated_by',
    ];

    public function productImages()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'id');
    }

    public function masterFields()
    {
        return $this->hasMany(MasterField::class, 'product_id', 'id');
    }
}
