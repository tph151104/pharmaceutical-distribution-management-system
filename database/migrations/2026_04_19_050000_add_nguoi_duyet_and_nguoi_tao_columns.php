<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Thêm cột nguoi_duyet vào bảng don_hang
        Schema::table('don_hang', function (Blueprint $table) {
            $table->string('nguoi_duyet', 50)->nullable()->after('trang_thai_dh');
            $table->foreign('nguoi_duyet')->references('ma_nguoi_dung')->on('nguoi_dung')->onDelete('set null');
        });

        // Thêm cột nguoi_tao vào bảng khach_tra_hang (NV BH tạo thay KH)
        Schema::table('khach_tra_hang', function (Blueprint $table) {
            $table->string('nguoi_tao', 50)->nullable()->after('nguoi_duyet');
            $table->foreign('nguoi_tao')->references('ma_nguoi_dung')->on('nguoi_dung')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('don_hang', function (Blueprint $table) {
            $table->dropForeign(['nguoi_duyet']);
            $table->dropColumn('nguoi_duyet');
        });

        Schema::table('khach_tra_hang', function (Blueprint $table) {
            $table->dropForeign(['nguoi_tao']);
            $table->dropColumn('nguoi_tao');
        });
    }
};
