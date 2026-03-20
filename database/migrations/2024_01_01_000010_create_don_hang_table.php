<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('don_hang', function (Blueprint $table) {
            $table->string('ma_don_hang', 50)->primary();
            $table->string('ma_kh', 50);
            $table->date('ngay_dat');
            $table->enum('trang_thai_dh', ['cho_xu_ly', 'da_duyet', 'dang_xuat_kho', 'da_hoan_thanh', 'da_huy','dang_van_chuyen'])->default('cho_xu_ly');
            $table->decimal('tong_tien', 15, 2);
            $table->string('image1', 255)->comment('Hình ảnh đơn hàng 1');
            $table->string('image2', 255)->comment('Hình ảnh đơn hàng 2');
            $table->string('image3', 255)->comment('Hình ảnh đơn hàng 3');
            $table->timestamps();

            $table->foreign('ma_kh')->references('ma_kh')->on('khach_hang')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('don_hang');
    }
};
