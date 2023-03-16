<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Store extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'stores';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'manager_name',
        'company_name',
        'email',
        'phone_number',
        'postal_code',
        'province_id',
        'district_id',
        'ward_id',
        'house_number',
        'description_list',
        'description_detail',
        'logo',
        'background_image',
        'logo_file_id',
        'background_file_id',
        'status',
    ];
}
