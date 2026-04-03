<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('khach_tra_hang', function (Blueprint $table) {
            $table->string('ma_tra_hang', 50)->primary();
            $table->string('ma_don_hang', 50);
            $table->string('ma_kh', 50);
            $table->date('ngay_yeu_cau');
            $table->text('ly_do_chung')->nullable();
            $table->decimal('tong_tien_hoan_tra', 15, 2)->default(0);
            $table->string('trang_thai', 50)->default('cho_duyet'); // cho_duyet, da_duyet_nhap_kho, tu_choi
            $table->string('nguoi_duyet', 50)->nullable();
            $table->date('ngay_duyet')->nullable();
            $table->text('ghi_chu_admin')->nullable();
            $table->timestamps();

            // Khoá ngoại
            $table->foreign('ma_don_hang')->references('ma_don_hang')->on('don_hang')->onDelete('cascade');
            $table->foreign('ma_kh')->references('ma_kh')->on('khach_hang')->onDelete('cascade');
        });

        Schema::create('chi_tiet_tra_hang', function (Blueprint $table) {
            $table->id();
            $table->string('ma_tra_hang', 50);
            $table->string('ma_thuoc', 50);
            $table->integer('so_luong_tra');
            $table->decimal('don_gia_tra', 15, 2)->default(0);
            $table->decimal('thanh_tien', 15, 2)->default(0);
            $table->text('ly_do_chi_tiet')->nullable();
            $table->string('image_minh_chung')->nullable();
            $table->timestamps();

            $table->foreign('ma_tra_hang')->references('ma_tra_hang')->on('khach_tra_hang')->onDelete('cascade');
            $table->foreign('ma_thuoc')->references('ma_thuoc')->on('thuoc')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('chi_tiet_tra_hang');
        Schema::dropIfExists('khach_tra_hang');
    }
};
