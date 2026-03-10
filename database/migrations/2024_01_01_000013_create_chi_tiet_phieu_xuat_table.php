<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('chi_tiet_phieu_xuat', function (Blueprint $table) {
            $table->string('ma_phieu_xuat', 50);
            $table->string('ma_thuoc', 50);
            $table->string('so_lo', 100);
            $table->date('han_su_dung');
            $table->integer('so_luong_xuat');
            $table->decimal('don_gia_ban', 15, 2);
            $table->decimal('thanh_tien', 15, 2);
            $table->timestamps();

            $table->primary(['ma_phieu_xuat', 'ma_thuoc', 'so_lo']);
            $table->foreign('ma_phieu_xuat')->references('ma_phieu_xuat')->on('phieu_xuat')->onDelete('restrict');
            $table->foreign('ma_thuoc')->references('ma_thuoc')->on('thuoc')->onDelete('restrict');
        });

        // Ràng buộc miền giá trị
        DB::statement('ALTER TABLE chi_tiet_phieu_xuat ADD CONSTRAINT chk_so_luong_xuat CHECK (so_luong_xuat > 0)');
        DB::statement('ALTER TABLE chi_tiet_phieu_xuat ADD CONSTRAINT chk_don_gia_ban CHECK (don_gia_ban >= 0)');
        DB::statement('ALTER TABLE chi_tiet_phieu_xuat ADD CONSTRAINT chk_thanh_tien_xuat CHECK (thanh_tien >= 0)');
    }

    public function down()
    {
        Schema::dropIfExists('chi_tiet_phieu_xuat');
    }
};
