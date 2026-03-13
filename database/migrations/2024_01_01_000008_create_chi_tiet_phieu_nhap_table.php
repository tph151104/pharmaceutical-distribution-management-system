<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('chi_tiet_phieu_nhap', function (Blueprint $table) {
            $table->string('ma_phieu_nhap', 50);
            $table->string('ma_thuoc', 50);
            $table->string('so_lo', 100);
            $table->string('so_lo_sx', 100);
            $table->date('han_su_dung');
            $table->integer('so_luong_nhap');
            $table->integer('so_luong_thuc_te')->default(0);
            $table->decimal('don_gia_nhap', 15, 2);
            $table->decimal('thanh_tien', 15, 2);
            $table->timestamps();

            $table->primary(['ma_phieu_nhap', 'ma_thuoc', 'so_lo']);
            $table->foreign('ma_phieu_nhap')->references('ma_phieu_nhap')->on('phieu_nhap')->onDelete('restrict');
            $table->foreign('ma_thuoc')->references('ma_thuoc')->on('thuoc')->onDelete('restrict');
        });

        // Ràng buộc miền giá trị
        DB::statement('ALTER TABLE chi_tiet_phieu_nhap ADD CONSTRAINT chk_so_luong_nhap CHECK (so_luong_nhap > 0)');
        DB::statement('ALTER TABLE chi_tiet_phieu_nhap ADD CONSTRAINT chk_so_luong_thuc_te CHECK (so_luong_thuc_te >= 0)');
        DB::statement('ALTER TABLE chi_tiet_phieu_nhap ADD CONSTRAINT chk_don_gia_nhap CHECK (don_gia_nhap >= 0)');
        DB::statement('ALTER TABLE chi_tiet_phieu_nhap ADD CONSTRAINT chk_thanh_tien_nhap CHECK (thanh_tien >= 0)');
    }

    public function down()
    {
        Schema::dropIfExists('chi_tiet_phieu_nhap');
    }
};
