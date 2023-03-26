<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryChild extends Model
{
    use HasFactory;

    protected $table = 'category_child';

    protected $fillable = [
        'name',
        'category_id'
    ];
}
