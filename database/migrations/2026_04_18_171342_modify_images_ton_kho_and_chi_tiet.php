<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 1. ton_kho: Xoá image2, image3 (chỉ giữ image1 là ảnh tổng lô hàng)
     * 2. chi_tiet_phieu_nhap: Thêm cột image (ảnh riêng từng lô sản phẩm)
     */
    public function up(): void
    {
        Schema::table('ton_kho', function (Blueprint $table) {
            $table->dropColumn(['image2', 'image3']);
        });

        Schema::table('chi_tiet_phieu_nhap', function (Blueprint $table) {
            $table->string('image')->nullable()->after('thanh_tien');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ton_kho', function (Blueprint $table) {
            $table->string('image2')->default('')->after('image1');
            $table->string('image3')->default('')->after('image2');
        });

        Schema::table('chi_tiet_phieu_nhap', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }
};
