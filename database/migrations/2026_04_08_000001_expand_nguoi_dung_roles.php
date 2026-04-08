<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Cập nhật comment cột role để phản ánh 5 roles
        DB::statement("ALTER TABLE `nguoi_dung` MODIFY COLUMN `role` INT NOT NULL COMMENT '1: Admin, 2: Nhân viên kho, 3: Nhân viên bán hàng, 4: Kế toán, 5: Trưởng kho'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE `nguoi_dung` MODIFY COLUMN `role` INT NOT NULL COMMENT '1: Admin, 2: Nhân viên kho, 3: Nhân viên bán hàng'");
    }
};
