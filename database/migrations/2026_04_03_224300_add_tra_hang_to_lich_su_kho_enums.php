<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Mở rộng enum loai_giao_dich: thêm 'tra_hang'
        DB::statement("ALTER TABLE `lich_su_kho` MODIFY COLUMN `loai_giao_dich` ENUM('nhap', 'xuat', 'dieu_chinh', 'tra_hang')");

        // Mở rộng enum nguon_giao_dich: thêm 'tra_hang'
        DB::statement("ALTER TABLE `lich_su_kho` MODIFY COLUMN `nguon_giao_dich` ENUM('phieu_nhap', 'phieu_xuat', 'don_hang', 'kiem_kho', 'tra_hang')");
    }

    public function down()
    {
        DB::statement("ALTER TABLE `lich_su_kho` MODIFY COLUMN `loai_giao_dich` ENUM('nhap', 'xuat', 'dieu_chinh')");
        DB::statement("ALTER TABLE `lich_su_kho` MODIFY COLUMN `nguon_giao_dich` ENUM('phieu_nhap', 'phieu_xuat', 'don_hang', 'kiem_kho')");
    }
};
