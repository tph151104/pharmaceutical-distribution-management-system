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
     * Chi tiết các lần thanh toán của phiếu xuất này.
     */
    public function cacThanhToan()
    {
        return $this->hasMany(ThanhToan::class, 'ma_phieu_xuat', 'ma_phieu_xuat');
    }
}
