<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ThanhToan extends Model
{
    protected $table = 'thanh_toan';
    public $incrementing = false;
    protected $primaryKey = 'ma_thanh_toan';
    protected $keyType = 'string';

    protected $fillable = [
        'ma_thanh_toan',
        'loai_thanh_toan',
        'ma_phieu_nhap',
        'ma_phieu_xuat',
        'tong_tien',
        'so_tien_tt',
        'so_tien_con_no',
        'trang_thai_tt',
        'phuong_thuc_tt',
        'ngay_thanh_toan',
        'giay_phep_tt_image',
        'ghi_chu',
    ];

    /**
     * Thuộc về 1 Phiếu Nhập
     */
    public function phieuNhap()
    {
        return $this->belongsTo(PhieuNhap::class, 'ma_phieu_nhap', 'ma_phieu_nhap');
    }

    /**
     * Thuộc về 1 Phiếu Xuất
     */
    public function phieuXuat()
    {
        return $this->belongsTo(PhieuXuat::class, 'ma_phieu_xuat', 'ma_phieu_xuat');
    }
}
