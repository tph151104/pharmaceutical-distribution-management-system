<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('nguoi_dung', function (Blueprint $table) {
            $table->string('ma_nguoi_dung', 50)->primary();
            $table->string('ten_dang_nhap', 191)->unique();
            $table->string('mat_khau', 255);
            $table->integer('role')->comment('1: Admin, 2: Nhân viên kho, 3: Nhân viên bán hàng');
            $table->string('email', 191)->unique();
            $table->string('sdt', 20);
            $table->enum('trang_thai', ['cho_phep_hd', 'vo_hieu_hoa'])->default('cho_phep_hd');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('nguoi_dung');
    }
};
