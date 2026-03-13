<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DonViTinh extends Model
{
    protected $table = 'don_vi_tinh';
    public $incrementing = false;
    protected $primaryKey = 'ma_dvt';
    protected $keyType = 'string';

    protected $fillable = [
        'ma_dvt',
        'ten_dvt',
        'mo_ta',
    ];

    public function cacThuoc()
    {
        return $this->hasMany(Thuoc::class, 'ma_dvt', 'ma_dvt');
    }
}
