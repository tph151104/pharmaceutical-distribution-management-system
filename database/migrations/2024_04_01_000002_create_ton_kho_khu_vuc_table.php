<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ton_kho_khu_vuc', function (Blueprint $table) {
            $table->id();
            $table->string('ma_thuoc', 50);
            $table->string('ma_phieu_nhap', 50);
            $table->string('so_lo', 100);
            $table->string('ma_khu_vuc', 50);
            $table->integer('so_luong')->default(0);
            $table->timestamps();

            $table->foreign(['ma_thuoc', 'ma_phieu_nhap', 'so_lo'])->references(['ma_thuoc', 'ma_phieu_nhap', 'so_lo'])->on('ton_kho')->onDelete('cascade');
            $table->foreign('ma_khu_vuc')->references('ma_khu_vuc')->on('khu_vuc_kho')->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ton_kho_khu_vuc');
    }
};
