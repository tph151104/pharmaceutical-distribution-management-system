<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LichSuDichChuyenKho extends Model
{
    protected $table = 'lich_su_dich_chuyen_kho';
    protected $primaryKey = 'ma_phieu_chuyen';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'ma_phieu_chuyen',
        'ma_thuoc',
        'ma_phieu_nhap',
        'so_lo',
        'tu_khu_vuc',
        'den_khu_vuc',
        'so_luong_chuyen',
        'nguoi_thuc_hien',
        'ngay_chuyen',
        'ly_do_chuyen',
    ];

    protected $casts = [
        'ngay_chuyen' => 'datetime',
    ];

    public function thuoc()
    {
        return $this->belongsTo(Thuoc::class, 'ma_thuoc', 'ma_thuoc');
    }

    protected $tonKhoCache = null;

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

    public function tuKhuVucKho()
    {
        return $this->belongsTo(KhuVucKho::class, 'tu_khu_vuc', 'ma_khu_vuc');
    }

    public function denKhuVucKho()
    {
        return $this->belongsTo(KhuVucKho::class, 'den_khu_vuc', 'ma_khu_vuc');
    }

    public function nguoiThucHien()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_thuc_hien', 'ma_nguoi_dung');
    }
}
