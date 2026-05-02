<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DonHang extends Model
{
    protected $table = 'don_hang';
    public $incrementing = false;
    protected $primaryKey = 'ma_don_hang';
    protected $keyType = 'string';

    protected $fillable = [
        'ma_don_hang',
        'ma_kh',
        'ngay_dat',
        'trang_thai_dh',
        'nguoi_duyet',
        'nguoi_huy',
        'ly_do_huy',
        'tong_tien',
        'dia_chi_giao',
        'ghi_chu',
    ];

    protected $casts = [
        'ngay_dat' => 'date',
        'tong_tien' => 'decimal:2',
    ];

    public function khachHang()
    {
        return $this->belongsTo(KhachHang::class, 'ma_kh', 'ma_kh');
    }

    public function chiTiet()
    {
        return $this->hasMany(ChiTietDonHang::class, 'ma_don_hang', 'ma_don_hang');
    }

    public function phieuXuat()
    {
        return $this->hasOne(PhieuXuat::class, 'ma_don_hang', 'ma_don_hang');
    }

    /**
     * Người duyệt đơn hàng (NV bán hàng / Admin)
     */
    public function nguoiDuyet()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_duyet', 'ma_nguoi_dung');
    }

    /**
     * Người hủy đơn hàng
     */
    public function nguoiHuy()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_huy', 'ma_nguoi_dung');
    }

    /**
     * Yêu cầu trả hàng liên quan
     */
    public function khachTraHang()
    {
        return $this->hasOne(KhachTraHang::class, 'ma_don_hang', 'ma_don_hang');
    }

    /**
     * Lấy tên trạng thái đơn hàng
     */
    public function getTenTrangThaiAttribute()
    {
        return [
            'cho_xu_ly' => 'Chờ xử lý',
            'da_duyet' => 'Đã duyệt',
            'dang_xuat_kho' => 'Đang xuất kho',
            'dang_van_chuyen' => 'Đang vận chuyển',
            'da_hoan_thanh' => 'Đã hoàn thành',
            'da_huy' => 'Đã hủy',
        ][$this->trang_thai_dh] ?? $this->trang_thai_dh;
    }

    public function getMauTrangThaiAttribute()
    {
        return [
            'cho_xu_ly' => 'warning',
            'da_duyet' => 'info',
            'dang_xuat_kho' => 'primary',
            'dang_van_chuyen' => 'info',
            'da_hoan_thanh' => 'success',
            'da_huy' => 'danger',
        ][$this->trang_thai_dh] ?? 'secondary';
    }
}
