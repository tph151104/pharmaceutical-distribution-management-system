<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChiTietPhieuXuat extends Model
{
    protected $table = 'chi_tiet_phieu_xuat';
    
    // Composite PK handle
    public $incrementing = false;
    protected $primaryKey = ['ma_phieu_xuat', 'ma_thuoc', 'so_lo'];
    protected $keyType = 'string';

    protected $fillable = [
        'ma_phieu_xuat',
        'ma_thuoc',
        'so_lo',
        'han_su_dung',
        'so_luong_xuat',
        'don_gia_ban',
        'thanh_tien'
    ];

    protected $casts = [
        'han_su_dung' => 'date',
        'so_luong_xuat' => 'integer',
        'don_gia_ban' => 'decimal:2',
        'thanh_tien' => 'decimal:2',
    ];

    /**
     * Set the keys for a save update query.
     */
    protected function setKeysForSaveQuery($query)
    {
        foreach ((array) $this->primaryKey as $pk) {
            $query->where($pk, '=', $this->getAttribute($pk));
        }
        return $query;
    }

    public function phieuXuat()
    {
        return $this->belongsTo(PhieuXuat::class, 'ma_phieu_xuat', 'ma_phieu_xuat');
    }

    public function thuoc()
    {
        return $this->belongsTo(Thuoc::class, 'ma_thuoc', 'ma_thuoc');
    }
}
