<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhieuNhap extends Model
{
    protected $table = 'phieu_nhap';
    public $incrementing = false;
    protected $primaryKey = 'ma_phieu_nhap';
    protected $keyType = 'string';

    protected $fillable = [
        'ma_phieu_nhap',
        'ma_ncc',
        'nguoi_nhap',
        'ngay_nhap',
        'tong_tien',
        'trang_thai_tt',
        'trang_thai_phieu_nhap',
        'image1',
        'giay_to_lien_quan',
        'tieu_lieu_lien_quan',
    ];

    protected $casts = [
        'ngay_nhap' => 'date',
        'tong_tien' => 'decimal:2',
    ];

    public function nhaCungCap()
    {
        return $this->belongsTo(NhaCungCap::class, 'ma_ncc', 'ma_ncc');
    }

    public function chiTiet()
    {
        return $this->hasMany(ChiTietPhieuNhap::class, 'ma_phieu_nhap', 'ma_phieu_nhap');
    }
    /**
     * Chi tiết các lần thanh toán của phiếu nhập này.
     */
    public function cacThanhToan()
    {
        return $this->hasMany(ThanhToan::class, 'ma_phieu_nhap', 'ma_phieu_nhap');
    }

    /**
     * Người lập phiếu nhập
     */
    public function nguoiLap()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_nhap', 'ma_nguoi_dung');
    }
}
