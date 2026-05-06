<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TonKhoKhuVuc extends Model
{
    protected $table = 'ton_kho_khu_vuc';

    protected $fillable = [
        'ma_thuoc',
        'ma_phieu_nhap',
        'so_lo',
        'ma_khu_vuc',
        'so_luong',
    ];

    public function khuVuc()
    {
        return $this->belongsTo(KhuVucKho::class, 'ma_khu_vuc', 'ma_khu_vuc');
    }

    public function thuoc()
    {
        return $this->belongsTo(Thuoc::class, 'ma_thuoc', 'ma_thuoc');
    }

    public function phieuNhap()
    {
        return $this->belongsTo(PhieuNhap::class, 'ma_phieu_nhap', 'ma_phieu_nhap');
    }

    protected $tonKhoCache = null;

    // Accessor thay thế mối quan hệ khóa chính phức tạp bị lỗi
    public function getTonKhoAttribute()
    {
        if ($this->tonKhoCache !== null) {
            return $this->tonKhoCache;
        }

        $this->tonKhoCache = TonKho::where('ma_thuoc', $this->ma_thuoc)
            ->where('ma_phieu_nhap', $this->ma_phieu_nhap)
            ->where('so_lo', $this->so_lo)
            ->first();
            
        return $this->tonKhoCache;
    }
}
