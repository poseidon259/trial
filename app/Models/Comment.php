<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'comments';

    protected $fillable = [
        'product_id',
        'user_id',
        'content',
        'rating',
        'status',
        'first_name',
        'last_name',
        'fake_avatar',
    ];

    public function commentImages()
    {
        return $this->hasMany(CommentImage::class, 'comment_id', 'id');
    }
}
