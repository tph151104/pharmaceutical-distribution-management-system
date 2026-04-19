<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DonHang;
use App\Models\KhachTraHang;
use App\Models\NguoiDung;

class UpdateOrderReturnDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Lấy danh sách nhân viên có quyền duyệt (Admin = 1, NV Bán Hàng = 3, Trưởng Kho = 5)
        $nhanViens = NguoiDung::whereIn('role', [1, 3, 5])->pluck('ma_nguoi_dung')->toArray();

        if (empty($nhanViens)) {
            $nhanViens = ['NV001']; // Default fallback if no users found
        }

        // Cập nhật người duyệt cho các đơn hàng đã được duyệt, đang xử lý xuất, vân chuyển, hoàn thành
        $donHangs = DonHang::whereNotNull('trang_thai_dh')
            ->whereIn('trang_thai_dh', ['da_duyet', 'dang_xuat_kho', 'dang_van_chuyen', 'da_hoan_thanh'])
            ->whereNull('nguoi_duyet')
            ->get();

        $orderCount = 0;
        foreach ($donHangs as $dh) {
            $dh->update(['nguoi_duyet' => $nhanViens[array_rand($nhanViens)]]);
            $orderCount++;
        }

        // Cập nhật người tạo cho các đơn khách trả hàng
        $traHangs = KhachTraHang::whereNull('nguoi_tao')->get();
        $returnCount = 0;
        foreach ($traHangs as $th) {
            $th->update(['nguoi_tao' => $nhanViens[array_rand($nhanViens)]]);
            $returnCount++;
        }

        $this->command->info("Đã cập nhật {$orderCount} đơn hàng có người duyệt, {$returnCount} đơn khách trả hàng có người tạo.");
    }
}
