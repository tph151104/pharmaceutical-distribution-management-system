<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('phieu_nhap', function (Blueprint $table) {
            $table->string('ma_phieu_nhap', 50)->primary();
            $table->string('ma_ncc', 50);
            $table->string('nguoi_nhap', 50);
            $table->date('ngay_nhap');
            $table->decimal('tong_tien', 15, 2);
            $table->enum('trang_thai_tt', ['chua_tt', 'mot_phan', 'da_tt'])->default('chua_tt');
            $table->enum('trang_thai_phieu_nhap', ['cho_nhap_kho', 'da_nhap_kho', 'da_huy'])->default('cho_nhap_kho');
            $table->timestamps();

            $table->foreign('ma_ncc')->references('ma_ncc')->on('nha_cung_cap')->onDelete('restrict');
            $table->foreign('nguoi_nhap')->references('ma_nguoi_dung')->on('nguoi_dung')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('phieu_nhap');
    }
};
