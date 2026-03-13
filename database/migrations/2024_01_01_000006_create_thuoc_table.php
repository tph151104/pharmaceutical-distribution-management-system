<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('thuoc', function (Blueprint $table) {
            $table->string('ma_thuoc', 50)->primary();
            $table->string('ten_thuoc', 255);
            $table->string('ma_nhom', 50);
            $table->string('ma_dvt', 50);
            $table->string('nguon_goc', 255)->nullable();
            $table->text('thanh_phan')->nullable();
            $table->string('ham_luong', 100)->nullable();
            $table->text('cong_dung')->nullable();
            $table->text('cach_dung')->nullable();
            $table->text('bao_quan')->nullable();
            $table->text('chong_chi_dinh')->nullable();
            $table->string('dang_bao_che', 100)->nullable();
            $table->decimal('gia_ban_de_xuat', 15, 2)->nullable();
            $table->string('image1', 255)->comment('Hình ảnh thuốc 1');
            $table->string('image2', 255)->comment('Hình ảnh thuốc 2');
            $table->string('image3', 255)->comment('Hình ảnh thuốc 3');
            $table->timestamps();

            $table->foreign('ma_nhom')->references('ma_nhom')->on('nhom_thuoc')->onDelete('restrict');
            $table->foreign('ma_dvt')->references('ma_dvt')->on('don_vi_tinh')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('thuoc');
    }
};
