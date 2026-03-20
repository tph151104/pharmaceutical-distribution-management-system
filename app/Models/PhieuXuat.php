<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhieuXuat extends Model
{
    protected $table = 'phieu_xuat';
    public $incrementing = false;
    protected $primaryKey = 'ma_phieu_xuat';
    protected $keyType = 'string';

    protected $fillable = [
        'ma_phieu_xuat',
        'ma_kh',
        'ma_don_hang',
        'nguoi_tao_phieu',
        'ngay_xuat',
        'tong_tien',
        'trang_thai_tt',
        'trang_thai_phieu_xuat',
        'image1',
        'image2',
        'giay_to_an_toan',
        'tai_lieu_lien_quan'
    ];

    /**
     * Thuộc về Khách hàng
     */
    public function khachHang()
    {
        return $this->belongsTo(KhachHang::class, 'ma_kh', 'ma_kh');
    }

    /**
     * Thuộc về Đơn hàng
     */
    public function donHang()
    {
        return $this->belongsTo(DonHang::class, 'ma_don_hang', 'ma_don_hang');
    }

    /**
     * Thuộc về Người dùng (Người tạo phiếu)
     */
    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_tao_phieu', 'ma_nguoi_dung');
    }

    /**
     * Chi tiết các lần thanh toán của phiếu xuất này.
     */
    public function cacThanhToan()
    {
        return $this->hasMany(ThanhToan::class, 'ma_phieu_xuat', 'ma_phieu_xuat');
    }

    /**
     * Chi tiết phiếu xuất
     */
    public function chiTiet()
    {
        return $this->hasMany(ChiTietPhieuXuat::class, 'ma_phieu_xuat', 'ma_phieu_xuat');
    }

    public function getTenTrangThaiAttribute()
    {
        $status = [
            'dang_chuan_bi' => 'Đang chuẩn bị',
            'da_xuat_kho' => 'Đã xuất kho',
            'da_van_chuyen' => 'Đã vận chuyển',
            'da_hoan_thanh' => 'Đã hoàn thành',
            'da_huy' => 'Đã hủy',
        ];

        return $status[$this->trang_thai_phieu_xuat] ?? $this->trang_thai_phieu_xuat;
    }

    public function getMauTrangThaiAttribute()
    {
        $colors = [
            'dang_chuan_bi' => 'warning',
            'da_xuat_kho' => 'primary',
            'da_van_chuyen' => 'info',
            'da_hoan_thanh' => 'success',
            'da_huy' => 'danger',
        ];

        return $colors[$this->trang_thai_phieu_xuat] ?? 'secondary';
    }
}
