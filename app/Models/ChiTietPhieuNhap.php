<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChiTietPhieuNhap extends Model
{
    protected $table = 'chi_tiet_phieu_nhap';
    public $incrementing = false;
    // Bảng này có khóa chính composite: ma_phieu_nhap, ma_thuoc, so_lo
    protected $primaryKey = ['ma_phieu_nhap', 'ma_thuoc', 'so_lo'];
    protected $keyType = 'string';

    protected $fillable = [
        'ma_phieu_nhap',
        'ma_thuoc',
        'so_lo',
        'so_lo_sx',
        'han_su_dung',
        'so_luong_nhap',
        'so_luong_thuc_te',
        'don_gia_nhap',
        'thanh_tien',
    ];

    protected $casts = [
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
