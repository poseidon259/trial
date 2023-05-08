<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    protected $table = 'districts';

    protected $fillable = [
        'province_id',
        'district_id',
        'name'
    ];

    public function wards()
    {
        return $this->hasMany(Ward::class, 'district_id', 'district_id');
    }
}
