<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChiTietPhieuNhap extends Model
{
    protected $table = 'chi_tiet_phieu_nhap';
    public $incrementing = false;
    protected $primaryKey = ['ma_phieu_nhap', 'ma_thuoc', 'so_lo'];
    protected $keyType = 'string'; //Khóa chính dạng Chuỗi/Chữ viết

    protected $fillable = [
        'ma_phieu_nhap',
        'ma_thuoc',
        'so_lo',
        'so_lo_sx',
        'ngay_san_xuat',
        'so_dang_ky',
        'han_su_dung',
        'so_luong_nhap',
        'so_luong_thuc_te',
        'don_gia_nhap',
        'thanh_tien',
        'image',
    ];

    protected $casts = [
        'ngay_san_xuat' => 'date',
        'han_su_dung' => 'date',
        'so_luong_nhap' => 'integer',
        'so_luong_thuc_te' => 'integer',
        'don_gia_nhap' => 'decimal:2',
        'thanh_tien' => 'decimal:2',
    ];

    public function phieuNhap()
    {
        return $this->belongsTo(PhieuNhap::class, 'ma_phieu_nhap', 'ma_phieu_nhap');
    }

    public function thuoc()
    {
        return $this->belongsTo(Thuoc::class, 'ma_thuoc', 'ma_thuoc');
    }

    /**
     * Override phương thức setKeysForSaveQuery cho composite PK
     */
    protected function setKeysForSaveQuery($query)
    {
        foreach ((array) $this->primaryKey as $pk) {
            $query->where($pk, '=', $this->getAttribute($pk));
        }
        return $query;
    }
}
