<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LichSuKho extends Model
{
    protected $table = 'lich_su_kho';
    protected $primaryKey = 'ma_log';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'ma_log',
        'ma_thuoc',
        'so_lo',
        'nguoi_thuc_hien',
        'ma_chung_tu',
        'loai_giao_dich',
        'nguon_giao_dich',
        'so_luong',
        'ton_truoc',
        'ton_sau',
        'don_gia',
        'thoi_gian',
        'ghi_chu',
    ];

    protected $casts = [
        'thoi_gian' => 'datetime',
        'so_luong' => 'integer',
        'ton_truoc' => 'integer',
        'ton_sau' => 'integer',
        'don_gia' => 'decimal:2',
    ];

    public function thuoc()
    {
        return $this->belongsTo(Thuoc::class, 'ma_thuoc', 'ma_thuoc');
    }

    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_thuc_hien', 'ma_nguoi_dung');
    }
}
