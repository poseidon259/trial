<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BannerStore extends Model
{
    use HasFactory;
    
    protected $table = 'banner_stores';

    protected $fillable = [
        'store_id',
        'image',
        'link_url',
        'sort',
        'display',
        'created_at',
        'updated_at',
    ];
}
