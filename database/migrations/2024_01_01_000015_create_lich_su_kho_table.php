<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('lich_su_kho', function (Blueprint $table) {
            $table->string('ma_log', 50)->primary();
            $table->string('ma_thuoc', 50);
            $table->string('so_lo', 100);
            $table->string('nguoi_thuc_hien', 50);
            $table->string('ma_chung_tu', 50)->comment('Mã phiếu nhập, phiếu xuất hoặc đơn hàng liên quan');
            $table->enum('loai_giao_dich', ['nhap', 'xuat', 'dieu_chinh']);
            $table->enum('nguon_giao_dich', ['phieu_nhap', 'phieu_xuat', 'don_hang', 'kiem_kho']);
            $table->integer('so_luong');
            $table->integer('ton_truoc');
            $table->integer('ton_sau');
            $table->decimal('don_gia', 15, 2);
            $table->dateTime('thoi_gian');
            $table->text('ghi_chu')->nullable();
            $table->timestamps();

            $table->foreign('ma_thuoc')->references('ma_thuoc')->on('thuoc')->onDelete('restrict');
            $table->foreign('nguoi_thuc_hien')->references('ma_nguoi_dung')->on('nguoi_dung')->onDelete('restrict');

            // Index để truy vấn nhanh theo thuốc + lô
            $table->index(['ma_thuoc', 'so_lo']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('lich_su_kho');
    }
};
