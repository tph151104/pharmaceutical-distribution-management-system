<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Định nghĩa lịch trình chạy lệnh (schedule) của ứng dụng.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Tự động kiểm tra và chuyển hàng hết hạn về KV05 (GSP)
        $schedule->command('inventory:check-expired')->dailyAt('00:00');

        // Tự động hoàn thành Đơn Hàng nếu Phiếu Xuất đã giao > 3 ngày
        $schedule->command('order:auto-complete')->dailyAt('00:30');
    }

    /**
     * Đăng ký các lệnh (commands) cho ứng dụng.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
