<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PhieuXuat;
use App\Models\DonHang;
use Carbon\Carbon;

class AutoUpdateOrderCompletion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:auto-complete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tự động cập nhật Đơn Hàng thành đã hoàn thành nếu Phiếu Xuất đã giao xong 3 ngày trước nhưng khách chưa xác nhận';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $threeDaysAgo = Carbon::now()->subDays(3);

        // Lấy các Phiếu Xuất đã hoàn thành > 3 ngày
        $phieuXuats = PhieuXuat::where('trang_thai_phieu_xuat', 'da_hoan_thanh')
            ->where('updated_at', '<=', $threeDaysAgo)
            ->whereNotNull('ma_don_hang')
            ->get();
 
        $count = 0;

        foreach ($phieuXuats as $px) {
            $donHang = DonHang::find($px->ma_don_hang);
            if ($donHang && $donHang->trang_thai_dh !== 'da_hoan_thanh') {
                $donHang->trang_thai_dh = 'da_hoan_thanh';
                $donHang->save();
                $count++;
            }
        }
        
        $this->info("Đã tự động chốt hoàn thành {$count} đơn hàng.");
    }
}
