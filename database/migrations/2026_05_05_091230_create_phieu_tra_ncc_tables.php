<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phieu_tra_ncc', function (Blueprint $table) {
            $table->string('ma_phieu_tra_ncc', 30)->primary();
            $table->string('ma_ncc', 20);
            $table->string('nguoi_tao', 20);
            $table->date('ngay_tao');
            $table->decimal('tong_tien', 15, 2)->default(0);
            $table->string('trang_thai', 30)->default('cho_duyet'); // cho_duyet, da_duyet, da_hoan_thanh, da_huy
            $table->text('ly_do_tra')->nullable();
            $table->text('ghi_chu')->nullable();
            $table->timestamps();

            $table->foreign('ma_ncc')->references('ma_ncc')->on('nha_cung_cap')->onDelete('cascade');
            $table->foreign('nguoi_tao')->references('ma_nguoi_dung')->on('nguoi_dung')->onDelete('cascade');
        });

        Schema::create('chi_tiet_phieu_tra_ncc', function (Blueprint $table) {
            $table->id();
            $table->string('ma_phieu_tra_ncc', 30);
            $table->string('ma_thuoc', 20);
            $table->string('ma_phieu_nhap', 30);
            $table->string('so_lo', 50);
            $table->integer('so_luong_tra');
            $table->decimal('don_gia', 15, 2);
            $table->decimal('thanh_tien', 15, 2);
            $table->timestamps();

            $table->foreign('ma_phieu_tra_ncc')->references('ma_phieu_tra_ncc')->on('phieu_tra_ncc')->onDelete('cascade');
            $table->foreign('ma_thuoc')->references('ma_thuoc')->on('thuoc')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chi_tiet_phieu_tra_ncc');
        Schema::dropIfExists('phieu_tra_ncc');
    }
};
