<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FeatureToggle;

class FeatureToggleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $features = [
            ['ma_chuc_nang' => 'imports',   'ten_chuc_nang' => 'Quản lý Nhập kho'],
            ['ma_chuc_nang' => 'sales',     'ten_chuc_nang' => 'Quản lý Xuất kho'],
            ['ma_chuc_nang' => 'transfers', 'ten_chuc_nang' => 'Điều chuyển kho'],
            ['ma_chuc_nang' => 'batches',   'ten_chuc_nang' => 'Tồn kho & Lô hàng'],
            ['ma_chuc_nang' => 'orders',    'ten_chuc_nang' => 'Đơn đặt hàng'],
            ['ma_chuc_nang' => 'returns',   'ten_chuc_nang' => 'Khách trả hàng'],
            ['ma_chuc_nang' => 'products',  'ten_chuc_nang' => 'Danh mục Thuốc'],
            ['ma_chuc_nang' => 'suppliers', 'ten_chuc_nang' => 'Nhà cung cấp'],
            ['ma_chuc_nang' => 'customers', 'ten_chuc_nang' => 'Khách hàng'],
            ['ma_chuc_nang' => 'payments',  'ten_chuc_nang' => 'Thanh toán & Công nợ'],
            ['ma_chuc_nang' => 'reports',   'ten_chuc_nang' => 'Báo cáo thống kê'],
        ];

        foreach ($features as $f) {
            FeatureToggle::updateOrCreate(
                ['ma_chuc_nang' => $f['ma_chuc_nang']],
                [
                    'ten_chuc_nang' => $f['ten_chuc_nang'],
                    'trang_thai'    => true, // Default to true (active)
                    'mo_ta'         => 'Sử dụng để bật hoặc tắt tính năng ' . $f['ten_chuc_nang'],
                ]
            );
        }
    }
}
