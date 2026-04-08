<?php

namespace Database\Seeders;

use App\Models\NguoiDung;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Seed tài khoản test cho mỗi role.
     */
    public function run()
    {
        $users = [
            [
                'ma_nguoi_dung' => 'ND001',
                'ten_dang_nhap' => 'admin',
                'mat_khau'      => Hash::make('admin123'),
                'ho_ten_nd'     => 'Quản trị viên',
                'role'          => NguoiDung::ROLE_ADMIN,
                'email'         => 'admin@pharma.vn',
                'sdt'           => '0901000001',
                'trang_thai'    => 'cho_phep_hd',
            ],
            [
                'ma_nguoi_dung' => 'ND002',
                'ten_dang_nhap' => 'truongkho',
                'mat_khau'      => Hash::make('123456'),
                'ho_ten_nd'     => 'Nguyễn Văn Trưởng',
                'role'          => NguoiDung::ROLE_TRUONG_KHO,
                'email'         => 'truongkho@pharma.vn',
                'sdt'           => '0901000005',
                'trang_thai'    => 'cho_phep_hd',
            ],
            [
                'ma_nguoi_dung' => 'ND003',
                'ten_dang_nhap' => 'nvkho',
                'mat_khau'      => Hash::make('123456'),
                'ho_ten_nd'     => 'Trần Thị Kho',
                'role'          => NguoiDung::ROLE_NV_KHO,
                'email'         => 'nvkho@pharma.vn',
                'sdt'           => '0901000002',
                'trang_thai'    => 'cho_phep_hd',
            ],
            [
                'ma_nguoi_dung' => 'ND004',
                'ten_dang_nhap' => 'nvbanhang',
                'mat_khau'      => Hash::make('123456'),
                'ho_ten_nd'     => 'Lê Văn Bán',
                'role'          => NguoiDung::ROLE_NV_BAN_HANG,
                'email'         => 'nvbanhang@pharma.vn',
                'sdt'           => '0901000003',
                'trang_thai'    => 'cho_phep_hd',
            ],
            [
                'ma_nguoi_dung' => 'ND005',
                'ten_dang_nhap' => 'ketoan',
                'mat_khau'      => Hash::make('123456'),
                'ho_ten_nd'     => 'Phạm Thị Toán',
                'role'          => NguoiDung::ROLE_KE_TOAN,
                'email'         => 'ketoan@pharma.vn',
                'sdt'           => '0901000004',
                'trang_thai'    => 'cho_phep_hd',
            ],
        ];

        foreach ($users as $userData) {
            NguoiDung::updateOrCreate(
                ['ten_dang_nhap' => $userData['ten_dang_nhap']],
                $userData
            );
        }

        $this->command->info('Đã tạo ' . count($users) . ' tài khoản test:');
        $this->command->table(
            ['Username', 'Password', 'Role'],
            [
                ['admin', 'admin123', 'Admin'],
                ['truongkho', '123456', 'Trưởng kho'],
                ['nvkho', '123456', 'NV Kho'],
                ['nvbanhang', '123456', 'NV Bán hàng'],
                ['ketoan', '123456', 'Kế toán'],
            ]
        );
    }
}
