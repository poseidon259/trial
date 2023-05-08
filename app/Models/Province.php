<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    use HasFactory;

    protected $table = 'provinces';

    protected $fillable = [
        'province_id',
        'name'
    ];

    public function districts()
    {
        return $this->hasMany(District::class, 'province_id', 'province_id');
    }
}
