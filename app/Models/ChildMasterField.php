<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildMasterField extends Model
{
    use HasFactory;

    protected $table = 'child_master_fields';

    protected $fillable = [
        'name',
        'master_field_id',
        'product_id',
        'sale_price',
        'origin_price',
        'stock',
        'product_code',
        'created_at',
        'updated_at',
    ];
}
