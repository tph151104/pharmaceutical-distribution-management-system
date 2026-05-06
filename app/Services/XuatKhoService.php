<?php

namespace App\Services;

use App\Models\TonKho;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class XuatKhoService
{
    /**
     * Kiểm tra một lô cụ thể có thể xuất bán với số lượng yêu cầu không
     *
     * @param string $maThuoc   Mã thuốc
     * @param string $soLo      Số lô
     * @param string $maPhieuNhap  Mã phiếu nhập
     * @param int    $soLuongXuat  Số lượng cần xuất
     * @return array ['hop_le' => bool, 'loi' => string|null, 'ton_kho' => TonKho|null]
     */
    public function kiemTraLoTruocKhiXuat(string $maThuoc, string $soLo, string $maPhieuNhap, int $soLuongXuat): array
    {
        // Tìm lô trong kho
        $lo = TonKho::where('ma_thuoc', $maThuoc)
            ->where('so_lo', $soLo)
            ->where('ma_phieu_nhap', $maPhieuNhap)
            ->first();

        if (!$lo) {
            return [
                'hop_le' => false,
                'loi' => "Không tìm thấy lô {$soLo} của thuốc {$maThuoc} trong kho.",
                'ton_kho' => null,
            ];
        }

        // Kiểm tra ràng buộc nghiệp vụ
        $loi = $lo->layLoiXuatBan($soLuongXuat);

        return [
            'hop_le' => $loi === null,
            'loi' => $loi,
            'ton_kho' => $lo,
        ];
    }

    /**
     * Kiểm tra toàn bộ danh sách chi tiết phiếu xuất trước khi xử lý
     *
     * @param array $danhSachChiTiet  Mảng [['ma_thuoc', 'so_lo', 'ma_phieu_nhap', 'so_luong_xuat'], ...]
     * @return array ['hop_le' => bool, 'loi' => array]
     */
    public function kiemTraDanhSachXuat(array $danhSachChiTiet): array
    {
        $danhSachLoi = [];

        foreach ($danhSachChiTiet as $index => $chiTiet) {
            $ketQua = $this->kiemTraLoTruocKhiXuat(
                $chiTiet['ma_thuoc'],
                $chiTiet['so_lo'],
                $chiTiet['ma_phieu_nhap'],
                $chiTiet['so_luong_xuat']
            );

            if (!$ketQua['hop_le']) {
                $danhSachLoi[] = "Dòng " . ($index + 1) . ": " . $ketQua['loi'];
            }
        }

        return [
            'hop_le' => empty($danhSachLoi),
            'loi' => $danhSachLoi,
        ];
    }

    /**
     * Thực hiện xuất kho: trừ tồn kho + cộng số lượng đã xuất
     * Gọi hàm này SAU KHI đã kiểm tra hợp lệ
     *
     * @param string $maThuoc
     * @param string $soLo
     * @param string $maPhieuNhap
     * @param int    $soLuongXuat
     * @return bool
     */
    public function xuatKho(string $maThuoc, string $soLo, string $maPhieuNhap, int $soLuongXuat): bool
    {
        return DB::transaction(function () use ($maThuoc, $soLo, $maPhieuNhap, $soLuongXuat) {
            // Khóa hàng (Lock row) để tránh tranh chấp dữ liệu (race condition)
            $lo = TonKho::where('ma_thuoc', $maThuoc)
                ->where('so_lo', $soLo)
                ->where('ma_phieu_nhap', $maPhieuNhap)
                ->lockForUpdate()
                ->first();

            // Kiểm tra lại sau khi lock
            if (!$lo || !$lo->coTheXuatBan($soLuongXuat)) {
                Log::error("[XuatKho] Xuất thất bại - Thuốc: {$maThuoc}, Lô: {$soLo}, SL: {$soLuongXuat}");
                return false;
            }

            // Cập nhật tồn kho
            $lo->so_luong_ton -= $soLuongXuat;
            $lo->so_luong_da_xuat += $soLuongXuat;
            $lo->save();

            Log::info("[XuatKho] Xuất thành công - Thuốc: {$maThuoc}, Lô: {$soLo}, SL: {$soLuongXuat}, Tồn còn: {$lo->so_luong_ton}");

            return true;
        });
    }
}
