<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('thanh_toan', function (Blueprint $table) {
            $table->string('ma_thanh_toan', 50)->primary();
            $table->enum('loai_thanh_toan', ['nhap', 'xuat']);
            $table->string('ma_phieu_nhap', 50)->nullable();
            $table->string('ma_phieu_xuat', 50)->nullable();
            $table->decimal('so_tien_tt', 15, 2);
            $table->date('ngay_thanh_toan');
            $table->text('ghi_chu')->nullable();
            $table->timestamps();

            $table->foreign('ma_phieu_nhap')->references('ma_phieu_nhap')->on('phieu_nhap')->onDelete('restrict');
            $table->foreign('ma_phieu_xuat')->references('ma_phieu_xuat')->on('phieu_xuat')->onDelete('restrict');
        });

        // Ràng buộc: phải có ít nhất 1 trong 2 mã phiếu
        DB::statement('ALTER TABLE thanh_toan ADD CONSTRAINT chk_phieu_thanh_toan CHECK (ma_phieu_nhap IS NOT NULL OR ma_phieu_xuat IS NOT NULL)');
        DB::statement('ALTER TABLE thanh_toan ADD CONSTRAINT chk_so_tien_tt CHECK (so_tien_tt > 0)');
    }

    public function down()
    {
        Schema::dropIfExists('thanh_toan');
    }
};
