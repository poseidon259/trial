<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    protected $table = 'users_address';

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'phone_number',
        'province_id',
        'district_id',
        'ward_id',
        'house_number',
        'gender',
        'postal_code',
        'birthday',
        'is_default',
    ];
}
