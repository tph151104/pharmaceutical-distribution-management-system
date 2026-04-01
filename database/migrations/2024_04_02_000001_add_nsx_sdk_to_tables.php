<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('chi_tiet_phieu_nhap', function (Blueprint $table) {
            $table->date('ngay_san_xuat')->nullable()->after('so_lo_sx');
            $table->string('so_dang_ky')->nullable()->after('ngay_san_xuat');
        });

        Schema::table('ton_kho', function (Blueprint $table) {
            $table->date('ngay_san_xuat')->nullable()->after('so_lo');
        });

        // Cập nhật dữ liệu cũ mặc định
        DB::table('chi_tiet_phieu_nhap')->update([
            'ngay_san_xuat' => '2023-01-01',
            'so_dang_ky' => 'DK-OLD-2023'
        ]);

        DB::table('ton_kho')->update([
            'ngay_san_xuat' => '2023-01-01'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('chi_tiet_phieu_nhap', function (Blueprint $table) {
            $table->dropColumn(['ngay_san_xuat', 'so_dang_ky']);
        });

        Schema::table('ton_kho', function (Blueprint $table) {
            $table->dropColumn('ngay_san_xuat');
        });
    }
};
