<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TonKho;
use App\Models\TonKhoKhuVuc;
use Carbon\Carbon;
use App\Services\InventoryLogService;
use App\Models\LichSuDichChuyenKho;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class CheckExpiredBatches extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inventory:check-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kiểm tra và tự động cách ly các lô hàng hết hạn vào phân khu Loại Bỏ (KV05)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::now()->toDateString();

        // Lấy tất cả các lô có tồn kho, trạng thái chưa phải là hết hạn, và ngày hết hạn < hoặc = hôm nay
        $expiredBatches = TonKho::where('so_luong_ton', '>', 0)
            ->where('trang_thai_lo', '!=', 'het_han')
            ->where('han_su_dung', '<=', $today)
            ->get();

        if ($expiredBatches->isEmpty()) {
            $this->info('Không có lô nào hết hạn cần cách ly.');
            Log::info('[CheckExpiredBatches] Không có lô hết hạn.');
            return 0;
        }

        $count = 0;

        foreach ($expiredBatches as $batch) {
            $oldStatus = $batch->trang_thai_lo;
            
            // 1. Cập nhật trạng thái lô
            $batch->trang_thai_lo = 'het_han';
            $batch->save();

            Log::warning("[CheckExpiredBatches] Lô {$batch->so_lo} - Thuốc {$batch->ma_thuoc}: {$oldStatus} → het_han (HSD: {$batch->han_su_dung})");

            // 2. Chuyển vị trí vật lý sang KV05_LOAI_BO
            $tkvs = TonKhoKhuVuc::where('ma_thuoc', $batch->ma_thuoc)
                ->where('ma_phieu_nhap', $batch->ma_phieu_nhap)
                ->where('so_lo', $batch->so_lo)
                ->where('so_luong', '>', 0)
                ->get();

            $totalMoved = 0;
            foreach ($tkvs as $tkv) {
                if ($tkv->ma_khu_vuc !== 'KV05_LOAI_BO') {
                    $soLuongChuyen = $tkv->so_luong;
                    $totalMoved += $soLuongChuyen;

                    // Ghi log chi tiết từng khu vực bị dời đi
                    LichSuDichChuyenKho::create([
                        'ma_phieu_chuyen' => 'CHCK-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(4)),
                        'ma_thuoc' => $batch->ma_thuoc,
                        'ma_phieu_nhap' => $batch->ma_phieu_nhap,
                        'so_lo' => $batch->so_lo,
                        'tu_khu_vuc' => $tkv->ma_khu_vuc,
                        'den_khu_vuc' => 'KV05_LOAI_BO',
                        'so_luong_chuyen' => $soLuongChuyen,
                        'nguoi_thuc_hien' => 'SYSTEM_BOT',
                        'ngay_chuyen' => Carbon::now(),
                        'ly_do_chuyen' => '[Auto-Expiry] BOT tự động gom hàng hết hạn từ ' . $tkv->ma_khu_vuc . ' vào KV05.',
                    ]);

                    $tkv->so_luong = 0;
                    $tkv->save();
                }
            }

            if ($totalMoved > 0) {
                $targetTkv = TonKhoKhuVuc::where('ma_thuoc', $batch->ma_thuoc)
                    ->where('ma_phieu_nhap', $batch->ma_phieu_nhap)
                    ->where('so_lo', $batch->so_lo)
                    ->where('ma_khu_vuc', 'KV05_LOAI_BO')
                    ->first();

                if ($targetTkv) {
                    $targetTkv->so_luong += $totalMoved;
                    $targetTkv->save();
                    
                } else {
                    TonKhoKhuVuc::create([
                        'ma_thuoc' => $batch->ma_thuoc,
                        'ma_phieu_nhap' => $batch->ma_phieu_nhap,
                        'so_lo' => $batch->so_lo,
                        'ma_khu_vuc' => 'KV05_LOAI_BO',
                        'so_luong' => $totalMoved
                    ]);
                }
                
                // 3. Ghi log
                InventoryLogService::logMovement(
                    $batch->ma_thuoc,
                    $batch->so_lo,
                    'SYSTEM_BOT',
                    $batch->ma_phieu_nhap,
                    'dieu_chinh',
                    'kiem_kho',
                    $totalMoved,
                    $batch->so_luong_ton,
                    $batch->so_luong_ton,
                    0,
                    '[Auto-Expiry] Hàng hóa đã hết hạn. Hệ thống tự động cách ly vào KV05_LOAI_BO.'
                );
            }

            $count++;
        }

        $this->info("Đã quét và cách ly thành công {$count} lô hàng hết hạn.");
        Log::info("[CheckExpiredBatches] Đã quét và cách ly thành công {$count} lô hàng hết hạn.");
    }
}
