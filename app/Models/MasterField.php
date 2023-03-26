<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterField extends Model
{
    use HasFactory;

    protected $table = 'master_fields';

    protected $fillable = [
        'name',
        'product_id',
        'parent_id',
        'sale_price',
        'origin_price',
        'stock',
        'product_code',
        'created_at',
        'updated_at',
    ];

    public function childs()
    {
        return $this->hasMany(MasterField::class, 'parent_id', 'id');
    }
}
