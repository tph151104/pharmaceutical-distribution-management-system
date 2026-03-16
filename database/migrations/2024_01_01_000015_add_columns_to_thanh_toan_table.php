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
        Schema::table('thanh_toan', function (Blueprint $table) {
            $table->decimal('tong_tien', 15, 2)->after('ma_phieu_xuat')->default(0);
            $table->decimal('so_tien_con_no', 15, 2)->after('so_tien_tt')->default(0);
            $table->enum('trang_thai_tt', ['da_du', 'con_no'])->default('con_no')->after('so_tien_con_no');
            $table->string('phuong_thuc_tt', 50)->nullable()->after('trang_thai_tt');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('thanh_toan', function (Blueprint $table) {
            $table->dropColumn(['tong_tien', 'so_tien_con_no', 'trang_thai_tt', 'phuong_thuc_tt']);
        });
    }
};
