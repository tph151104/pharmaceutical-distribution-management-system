<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class KhachHang extends Authenticatable
{
    protected $table = 'khach_hang';
    public $incrementing = false;
    protected $primaryKey = 'ma_kh';
    protected $keyType = 'string';

    protected $fillable = [
        'ma_kh',
        'ten_dang_nhap',
        'mat_khau',
        'ten_kh',
        'loai_kh',
        'dia_chi',
        'ma_so_thue',
        'giay_phep_hd_image',
        'hinh_dai_dien',
        'trang_thai_tk',
        'dien_thoai',
        'email',
        'ghi_chu',
    ];

    /**
     * Tự động hash mật khẩu trước khi lưu
     */
    public function setMatKhauAttribute($value)
    {
        $this->attributes['mat_khau'] = $value;
    }

    /**
     * Ghi đè trường mật khẩu cho Laravel Auth
     */
    public function getAuthPassword()
    {
        return $this->mat_khau;
    }

    /**
     * Các đơn hàng của khách hàng
     */
    public function donHangs()
    {
        return $this->hasMany(DonHang::class, 'ma_kh', 'ma_kh');
    }

    /**
     * Các yêu cầu trả hàng của khách hàng
     */
    public function khachTraHangs()
    {
        return $this->hasMany(KhachTraHang::class, 'ma_kh', 'ma_kh');
    }
}
