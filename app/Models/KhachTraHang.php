<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KhachTraHang extends Model
{
    protected $table = 'khach_tra_hang';
    public $incrementing = false;
    protected $primaryKey = 'ma_tra_hang';
    protected $keyType = 'string';

    protected $fillable = [
        'ma_tra_hang',
        'ma_don_hang',
        'ma_kh',
        'ngay_yeu_cau',
        'ly_do_chung',
        'tong_tien_hoan_tra',
        'trang_thai',
        'trang_thai_hoan_tien',
        'nguoi_duyet',
        'ngay_duyet',
        'ghi_chu_admin',
        'minh_chung_image',
    ];

    protected $casts = [
        'ngay_yeu_cau' => 'date',
        'ngay_duyet' => 'date',
    ];

    public function donHang()
    {
        return $this->belongsTo(DonHang::class, 'ma_don_hang', 'ma_don_hang');
    }

    public function khachHang()
    {
        return $this->belongsTo(KhachHang::class, 'ma_kh', 'ma_kh');
    }

    public function chiTiet()
    {
        return $this->hasMany(ChiTietTraHang::class, 'ma_tra_hang', 'ma_tra_hang');
    }

    /**
     * Các giao dịch hoàn tiền
     */
    public function thanhToans()
    {
        return $this->hasMany(ThanhToan::class, 'ma_tra_hang', 'ma_tra_hang');
    }

    public function nguoiDung()
    {
        return $this->belongsTo(NguoiDung::class, 'nguoi_duyet', 'ma_nguoi_dung');
    }
}
