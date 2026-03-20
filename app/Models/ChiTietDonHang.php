<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChiTietDonHang extends Model
{
    protected $table = 'chi_tiet_don_hang';
    public $incrementing = false;

    protected $fillable = [
        'ma_don_hang',
        'ma_thuoc',
        'so_luong',
        'don_gia',
    ];

    protected $casts = [
        'so_luong' => 'integer',
        'don_gia' => 'decimal:2',
    ];

    public function donHang()
    {
        return $this->belongsTo(DonHang::class, 'ma_don_hang', 'ma_don_hang');
    }

    public function thuoc()
    {
        return $this->belongsTo(Thuoc::class, 'ma_thuoc', 'ma_thuoc');
    }

    /**
     * Thành tiền = số lượng × đơn giá
     */
    public function getThanhTienAttribute()
    {
        return $this->so_luong * $this->don_gia;
    }

    /**
     * Override cho composite PK
     */
    protected function setKeysForSaveQuery($query)
    {
        $query->where('ma_don_hang', $this->ma_don_hang);
        $query->where('ma_thuoc', $this->ma_thuoc);
        return $query;
    }
}
