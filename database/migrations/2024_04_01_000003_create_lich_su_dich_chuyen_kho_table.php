<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('lich_su_dich_chuyen_kho', function (Blueprint $table) {
            $table->string('ma_phieu_chuyen', 50)->primary();
            $table->string('ma_thuoc', 50);
            $table->string('ma_phieu_nhap', 50);
            $table->string('so_lo', 100);
            $table->string('tu_khu_vuc', 50)->nullable();
            $table->string('den_khu_vuc', 50);
            $table->integer('so_luong_chuyen');
            $table->string('nguoi_thuc_hien', 50);
            $table->dateTime('ngay_chuyen');
            $table->text('ly_do_chuyen')->nullable();
            $table->timestamps();

            $table->foreign(['ma_thuoc', 'ma_phieu_nhap', 'so_lo'])->references(['ma_thuoc', 'ma_phieu_nhap', 'so_lo'])->on('ton_kho')->onDelete('cascade');
            $table->foreign('tu_khu_vuc')->references('ma_khu_vuc')->on('khu_vuc_kho')->onDelete('set null');
            $table->foreign('den_khu_vuc')->references('ma_khu_vuc')->on('khu_vuc_kho')->onDelete('restrict');
            $table->foreign('nguoi_thuc_hien')->references('ma_nguoi_dung')->on('nguoi_dung')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('lich_su_dich_chuyen_kho');
    }
};
