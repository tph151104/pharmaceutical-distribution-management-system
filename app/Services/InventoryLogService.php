<?php

namespace App\Services;

use App\Models\LichSuKho;
use Illuminate\Support\Str;
use Carbon\Carbon;

class InventoryLogService
{
    /**
     * Ghi log biến động kho
     *
     * @param string $maThuoc
     * @param string $soLo
     * @param string $nguoiThucHien
     * @param string $maChungTu
     * @param string $loaiGiaoDich ('nhap', 'xuat', 'dieu_chinh')
     * @param string $nguonGiaoDich ('phieu_nhap', 'phieu_xuat', 'don_hang', 'kiem_kho')
     * @param int $soLuong Số lượng thay đổi (dương)
     * @param int $tonTruoc
     * @param int $tonSau
     * @param float $donGia
     * @param string|null $ghiChu
     * @return LichSuKho
     */
    public static function logMovement(
        $maThuoc,
        $soLo,
        $nguoiThucHien,
        $maChungTu,
        $loaiGiaoDich,
        $nguonGiaoDich,
        $soLuong,
        $tonTruoc,
        $tonSau,
        $donGia,
        $ghiChu = null
    ) {
        $maLog = 'LOG-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(4));

        return LichSuKho::create([
            'ma_log' => $maLog,
            'ma_thuoc' => $maThuoc,
            'so_lo' => $soLo,
            'nguoi_thuc_hien' => $nguoiThucHien,
            'ma_chung_tu' => $maChungTu,
            'loai_giao_dich' => $loaiGiaoDich,
            'nguon_giao_dich' => $nguonGiaoDich,
            'so_luong' => $soLuong,
            'ton_truoc' => $tonTruoc,
            'ton_sau' => $tonSau,
            'don_gia' => $donGia,
            'thoi_gian' => now(),
            'ghi_chu' => $ghiChu,
        ]);
    }
}
