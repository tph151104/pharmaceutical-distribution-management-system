<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChiTietPhieuTraNcc extends Model
{
    protected $table = 'chi_tiet_phieu_tra_ncc';

    protected $fillable = [
        'ma_phieu_tra_ncc',
        'ma_thuoc',
        'ma_phieu_nhap',
        'so_lo',
        'so_luong_tra',
        'don_gia',
        'thanh_tien',
    ];

    protected $casts = [
        'don_gia' => 'decimal:2',
        'thanh_tien' => 'decimal:2',
    ];

    public function phieuTraNcc()
    {
        return $this->belongsTo(PhieuTraNcc::class, 'ma_phieu_tra_ncc', 'ma_phieu_tra_ncc');
    }

    public function thuoc()
    {
        return $this->belongsTo(Thuoc::class, 'ma_thuoc', 'ma_thuoc');
    }
}
