<?php

namespace App\Console\Commands;

use App\Models\TonKho;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CapNhatLoHetHan extends Command
{
    /**
     * Tên lệnh artisan
     */
    protected $signature = 'tonkho:cap-nhat-het-han';

    /**
     * Mô tả lệnh
     */
    protected $description = 'Tự động cập nhật trạng thái lô thuốc đã hết hạn sử dụng';

    public function handle()
    {
        // Tìm các lô chưa đánh dấu hết hạn nhưng đã quá hạn sử dụng
        $loHetHan = TonKho::hetHanChuaCapNhat()->get();

        if ($loHetHan->isEmpty()) {
            $this->info('Không có lô nào hết hạn cần cập nhật.');
            Log::info('[CapNhatLoHetHan] Không có lô hết hạn.');
            return 0;
        }

        $soLuongCapNhat = 0;

        foreach ($loHetHan as $lo) {
            $trangThaiCu = $lo->trang_thai_lo;
            $lo->trang_thai_lo = 'het_han';
            $lo->save();
            $soLuongCapNhat++;

            Log::warning("[CapNhatLoHetHan] Lô {$lo->so_lo} - Thuốc {$lo->ma_thuoc}: {$trangThaiCu} → het_han (HSD: {$lo->han_su_dung->format('d/m/Y')})");
        }

        $this->info("Đã cập nhật {$soLuongCapNhat} lô thuốc hết hạn.");
        Log::info("[CapNhatLoHetHan] Đã cập nhật {$soLuongCapNhat} lô thuốc hết hạn.");

        return 0;
    }
}
