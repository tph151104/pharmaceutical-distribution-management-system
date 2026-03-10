<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ton_kho', function (Blueprint $table) {
            $table->string('ma_thuoc', 50);
            $table->string('ma_phieu_nhap', 50);
            $table->string('so_lo', 100);
            $table->date('han_su_dung');
            $table->integer('so_luong_ton')->default(0);
            $table->integer('so_luong_da_xuat')->default(0);
            $table->enum('trang_thai_lo', ['cho_duyet', 'dang_ban', 'het_han', 'thu_hoi', 'ngung_ban'])->default('cho_duyet');
            $table->timestamps();
            $table->primary(['ma_thuoc', 'ma_phieu_nhap', 'so_lo']);
            $table->foreign('ma_thuoc')->references('ma_thuoc')->on('thuoc')->onDelete('restrict');
            $table->foreign('ma_phieu_nhap')->references('ma_phieu_nhap')->on('phieu_nhap')->onDelete('restrict');
        });

        // Ràng buộc miền giá trị
        DB::statement('ALTER TABLE ton_kho ADD CONSTRAINT chk_so_luong_ton CHECK (so_luong_ton >= 0)');
        DB::statement('ALTER TABLE ton_kho ADD CONSTRAINT chk_so_luong_da_xuat CHECK (so_luong_da_xuat >= 0)');
    }

    public function down()
    {
        Schema::dropIfExists('ton_kho');
    }
};
