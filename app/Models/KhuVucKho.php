<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KhuVucKho extends Model
{
    protected $table = 'khu_vuc_kho';
    protected $primaryKey = 'ma_khu_vuc';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'ma_khu_vuc',
        'ten_khu_vuc',
        'loai_khu_vuc',
        'mo_ta',
        'trang_thai',
    ];

    public function tonKhoKhuVuc()
    {
        return $this->hasMany(TonKhoKhuVuc::class, 'ma_khu_vuc', 'ma_khu_vuc');
    }
}
