<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('khach_hang', function (Blueprint $table) {
            $table->string('ma_kh', 50)->primary();
            $table->string('ten_dang_nhap', 191)->unique();
            $table->string('mat_khau', 255);
            $table->string('ten_kh', 255);
            $table->enum('loai_kh', ['nha_thuoc', 'dai_ly', 'phong_kham', 'benh_vien']);
            $table->string('dia_chi', 255);
            $table->string('ma_so_thue', 50)->nullable();
            $table->string('giay_phep_hoat_dong', 255)->nullable()->comment('Số giấy phép kinh doanh dược');
            $table->string('nguoi_dai_dien', 255)->nullable()->comment('Tên người đại diện');
            $table->enum('trang_thai_tk', ['cho_duyet', 'hoat_dong', 'vo_hieu_hoa'])->default('cho_duyet');
            $table->string('dien_thoai', 20)->nullable();
            $table->string('email', 191)->nullable();
            $table->text('ghi_chu')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('khach_hang');
    }
};
