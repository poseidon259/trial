<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    protected $fillable = [
        'name',
        'image',
        'file_id',
    ];

    public function categoryChildren()
    {
        return $this->hasMany(CategoryChild::class, 'category_id', 'id');
    }
}
