<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'order_no',
        'note',
        'first_name',
        'last_name',
        'email',
        'phone_number',
        'postal_code',
        'province_id',
        'district_id',
        'ward_id',
        'house_number',
        'gender',
        'sub_total',
        'total',
        'discount',
        'delivery_fee',
        'payment_date',
        'status',
        'payment_method',
        'shipping_time',
        'discount_freeship',
        'created_at',
        'updated_at',
    ];
}