<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TonKho;
use App\Models\TonKhoKhuVuc;

class TonKhoKhuVucBackfillSeeder extends Seeder
{
    public function run()
    {
        // Kiểm tra an toàn bằng raw query / ID filter:
        $existingTonKhoKhuVuc = TonKhoKhuVuc::select('ma_thuoc', 'ma_phieu_nhap', 'so_lo')->get()->map(function($item) {
            return $item->ma_thuoc . '_' . $item->ma_phieu_nhap . '_' . $item->so_lo;
        })->toArray();

        $tonKhos = TonKho::where('so_luong_ton', '>', 0)->get();

        foreach ($tonKhos as $tk) {
            $key = $tk->ma_thuoc . '_' . $tk->ma_phieu_nhap . '_' . $tk->so_lo;
            
            if (!in_array($key, $existingTonKhoKhuVuc)) {
                $maKhuVuc = 'KV01_TIEP_NHAN'; // Mặc định chưa duyệt thì nằm ở tiếp nhận

                if ($tk->trang_thai_lo === 'dang_ban') {
                    $maKhuVuc = 'KV03_THANH_PHAM';
                } elseif ($tk->trang_thai_lo === 'thu_hoi' || $tk->trang_thai_lo === 'loi') {
                    $maKhuVuc = 'KV04_CHO_XU_LY';
                } elseif ($tk->trang_thai_lo === 'het_han' || $tk->trang_thai_lo === 'loai_bo') {
                    $maKhuVuc = 'KV05_LOAI_BO';
                }

                TonKhoKhuVuc::create([
                    'ma_thuoc' => $tk->ma_thuoc,
                    'ma_phieu_nhap' => $tk->ma_phieu_nhap,
                    'so_lo' => $tk->so_lo,
                    'ma_khu_vuc' => $maKhuVuc,
                    'so_luong' => $tk->so_luong_ton
                ]);
                
                $existingTonKhoKhuVuc[] = $key;
            }
        }
    }
}
