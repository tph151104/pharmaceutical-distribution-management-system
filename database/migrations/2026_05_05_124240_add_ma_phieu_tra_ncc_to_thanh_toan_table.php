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
            $table->string('ma_phieu_tra_ncc', 30)->nullable()->after('ma_tra_hang');
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
            $table->dropColumn('ma_phieu_tra_ncc');
        });
    }
};
