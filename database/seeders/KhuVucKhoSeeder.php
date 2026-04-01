<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\KhuVucKho;

class KhuVucKhoSeeder extends Seeder
{
    public function run()
    {
        $khuVucs = [
            [
                'ma_khu_vuc' => 'KV01_TIEP_NHAN',
                'ten_khu_vuc' => 'Kho Tiếp nhận (Receiving Area)',
                'loai_khu_vuc' => 'tiep_nhan',
                'mo_ta' => 'Nơi kiểm tra khi hàng mới về trước khi chuyển sang biệt trữ',
                'trang_thai' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'ma_khu_vuc' => 'KV02_BIET_TRU',
                'ten_khu_vuc' => 'Kho Biệt trữ (Quarantine Area)',
                'loai_khu_vuc' => 'biet_tru',
                'mo_ta' => 'Nơi lưu trữ chờ kết quả kiểm soát chất lượng (QC)',
                'trang_thai' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'ma_khu_vuc' => 'KV03_THANH_PHAM',
                'ten_khu_vuc' => 'Kho Thành phẩm (Ready for Sale)',
                'loai_khu_vuc' => 'san_sang',
                'mo_ta' => 'Khu vực chứa hàng đã sẵn sàng để phân phối xuất bán',
                'trang_thai' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'ma_khu_vuc' => 'KV04_CHO_XU_LY',
                'ten_khu_vuc' => 'Kho Chờ xử lý / Trả về (Return Area)',
                'loai_khu_vuc' => 'tra_ve',
                'mo_ta' => 'Lưu trữ hàng thu hồi, hàng lỗi chờ quyết định xử lý',
                'trang_thai' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'ma_khu_vuc' => 'KV05_LOAI_BO',
                'ten_khu_vuc' => 'Kho Loại bỏ (Disposal Area)',
                'loai_khu_vuc' => 'loai_bo',
                'mo_ta' => 'Chứa hàng không còn giá trị chờ xử lý tiêu hủy',
                'trang_thai' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        foreach ($khuVucs as $kv) {
            KhuVucKho::updateOrCreate(['ma_khu_vuc' => $kv['ma_khu_vuc']], $kv);
        }
    }
}
