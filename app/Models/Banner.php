<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $table = 'banner_general';

    protected $fillable = [
        'image',
        'link_url',
        'sort',
        'status',
        'created_by',
        'updated_by',
    ];
}
