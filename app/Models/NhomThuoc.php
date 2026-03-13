<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NhomThuoc extends Model
{
    protected $table = 'nhom_thuoc';
    public $incrementing = false;
    protected $primaryKey = 'ma_nhom';
    protected $keyType = 'string';

    protected $fillable = [
        'ma_nhom',
        'ten_nhom',
        'mo_ta',
    ];

    public function cacThuoc()
    {
        return $this->hasMany(Thuoc::class, 'ma_nhom', 'ma_nhom');
    }
}
