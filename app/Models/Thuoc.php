<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Thuoc extends Model
{
    protected $table = 'thuoc';
    public $incrementing = false;
    protected $primaryKey = 'ma_thuoc';
    protected $keyType = 'string';

    protected $fillable = [
        'ma_thuoc',
        'ten_thuoc',
        'ma_nhom',
        'ma_dvt',
        'nguon_goc',
        'gia_ban_de_xuat',
        'thanh_phan',
        'ham_luong',
        'cong_dung',
        'cach_dung',
        'bao_quan',
        'chong_chi_dinh',
        'dang_bao_che',
        'gia_ban',
        'image1',
        'image2',
        'image3',
    ];

    public function nhomThuoc()
    {
        return $this->belongsTo(NhomThuoc::class, 'ma_nhom', 'ma_nhom');
    }

    public function donViTinh()
    {
        return $this->belongsTo(DonViTinh::class, 'ma_dvt', 'ma_dvt');
    }

    public function tonKho()
    {
        return $this->hasMany(TonKho::class, 'ma_thuoc', 'ma_thuoc');
    }

    /**
     * Tổng tồn kho khả dụng (tất cả các lô đang bán, chưa hết hạn)
     */
    public function getTongTonKhoAttribute()
    {
        return \App\Models\TonKhoKhuVuc::join('ton_kho', function ($join) {
                $join->on('ton_kho_khu_vuc.ma_thuoc', '=', 'ton_kho.ma_thuoc')
                     ->on('ton_kho_khu_vuc.ma_phieu_nhap', '=', 'ton_kho.ma_phieu_nhap')
                     ->on('ton_kho_khu_vuc.so_lo', '=', 'ton_kho.so_lo');
            })
            ->where('ton_kho_khu_vuc.ma_thuoc', $this->ma_thuoc)
            ->where('ton_kho_khu_vuc.ma_khu_vuc', 'KV03_THANH_PHAM')
            ->where('ton_kho.trang_thai_lo', 'dang_ban')
            ->where('ton_kho.han_su_dung', '>=', now()->toDateString())
            ->sum('ton_kho_khu_vuc.so_luong');
    }
}
