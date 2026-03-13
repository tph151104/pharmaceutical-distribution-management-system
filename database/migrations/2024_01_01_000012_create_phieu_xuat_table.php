<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('phieu_xuat', function (Blueprint $table) {
            $table->string('ma_phieu_xuat', 50)->primary();
            $table->string('ma_kh', 50);
            $table->string('ma_don_hang', 50)->nullable();
            $table->string('nguoi_tao_phieu', 50);
            $table->date('ngay_xuat');
            $table->decimal('tong_tien', 15, 2);
            $table->enum('trang_thai_tt', ['chua_tt', 'mot_phan', 'da_tt'])->default('chua_tt');
            $table->enum('trang_thai_phieu_xuat', ['dang_chuan_bi', 'da_xuat_kho', 'da_van_chuyen', 'da_huy'])->default('dang_chuan_bi');
            $table->string('image1', 255)->comment('Hình ảnh phiếu xuất 1');
            $table->string('image2', 255)->comment('Hình ảnh phiếu xuất 2');
            $table->string('giay_to_an_toan', 255)->comment('giấy tờ an toàn về thuốc');
            $table->string('tai_lieu_lien_quan', 255)->comment('tài liệu liên quan');
            $table->timestamps();

            $table->foreign('ma_kh')->references('ma_kh')->on('khach_hang')->onDelete('restrict');
            $table->foreign('ma_don_hang')->references('ma_don_hang')->on('don_hang')->onDelete('restrict');
            $table->foreign('nguoi_tao_phieu')->references('ma_nguoi_dung')->on('nguoi_dung')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('phieu_xuat');
    }
};
