<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhieuTraNcc extends Model
{
    protected $table = 'phieu_tra_ncc';
    public $incrementing = false;
    protected $primaryKey = 'ma_phieu_tra_ncc';
    protected $keyType = 'string';

    protected $fillable = [
        'ma_phieu_tra_ncc',
        'ma_ncc',
        'nguoi_tao',
        'ngay_tao',
        'tong_tien',
        'trang_thai',
        'ly_do_tra',
        'ghi_chu',
    ];

    protected $casts = [
        'ngay_tao' => 'date',
        'tong_tien' => 'decimal:2',
    ];

    public function nhaCungCap()
    {
        return $this->belongsTo(NhaCungCap::class, 'ma_ncc', 'ma_ncc');
    }

    public function nguoiTao()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_tao', 'ma_nguoi_dung');
    }

    public function chiTiet()
    {
        return $this->hasMany(ChiTietPhieuTraNcc::class, 'ma_phieu_tra_ncc', 'ma_phieu_tra_ncc');
    }

    public function thanhToans()
    {
        return $this->hasMany(ThanhToan::class, 'ma_phieu_tra_ncc', 'ma_phieu_tra_ncc');
    }
}
