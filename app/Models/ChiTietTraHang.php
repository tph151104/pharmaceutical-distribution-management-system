<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChiTietTraHang extends Model
{
    protected $table = 'chi_tiet_tra_hang';

    protected $fillable = [
        'ma_tra_hang',
        'ma_thuoc',
        'so_luong_tra',
        'don_gia_tra',
        'thanh_tien',
        'ly_do_chi_tiet',
        'image_minh_chung',
    ];

    public function khachTraHang()
    {
        return $this->belongsTo(KhachTraHang::class, 'ma_tra_hang', 'ma_tra_hang');
    }

    public function thuoc()
    {
        return $this->belongsTo(Thuoc::class, 'ma_thuoc', 'ma_thuoc');
    }
}
